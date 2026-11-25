import { spawn } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const OUT_DIR = 'C:\\Users\\BILALIHSAN\\.gemini\\antigravity\\brain\\ae4c1f82-adc5-4176-8536-5b8d0fecf760\\scratch';
const PROFILE_DIR = path.join(OUT_DIR, 'chrome-profile-mega');
const PORT = 9251;

fs.mkdirSync(OUT_DIR, { recursive: true });
fs.mkdirSync(PROFILE_DIR, { recursive: true });

const chrome = spawn(CHROME, [
  '--headless=new',
  '--disable-gpu',
  '--no-first-run',
  '--no-default-browser-check',
  `--remote-debugging-port=${PORT}`,
  `--user-data-dir=${PROFILE_DIR}`,
  'about:blank',
], { stdio: 'ignore' });

async function getWsUrl() {
  for (let i = 0; i < 40; i++) {
    try {
      const r = await fetch(`http://127.0.0.1:${PORT}/json/version`);
      if (r.ok) {
        const j = await r.json();
        if (j.webSocketDebuggerUrl) return j.webSocketDebuggerUrl;
      }
    } catch {}
    await new Promise(r => setTimeout(r, 200));
  }
  throw new Error('Chrome CDP not ready');
}

function createCdpClient(wsUrl) {
  return new Promise((resolve, reject) => {
    const ws = new WebSocket(wsUrl);
    let nextId = 1;
    const pending = new Map();
    ws.addEventListener('open', () => resolve({
      send(method, params = {}, sessionId) {
        const id = nextId++;
        const payload = { id, method, params };
        if (sessionId) payload.sessionId = sessionId;
        ws.send(JSON.stringify(payload));
        return new Promise((res, rej) => pending.set(id, { res, rej }));
      },
      close() { ws.close(); },
    }));
    ws.addEventListener('message', (msg) => {
      const data = JSON.parse(msg.data);
      if (data.id && pending.has(data.id)) {
        const { res, rej } = pending.get(data.id);
        pending.delete(data.id);
        if (data.error) rej(new Error(`${data.error.message} (${data.error.code})`));
        else res(data.result);
      }
    });
    ws.addEventListener('error', reject);
  });
}

async function run() {
  const browserWs = await getWsUrl();
  const cdp = await createCdpClient(browserWs);
  const { targetId } = await cdp.send('Target.createTarget', { url: 'about:blank' });
  const { sessionId } = await cdp.send('Target.attachToTarget', { targetId, flatten: true });
  await cdp.send('Page.enable', {}, sessionId);
  await cdp.send('Runtime.enable', {}, sessionId);
  await cdp.send('Emulation.setDeviceMetricsOverride', {
    width: 1440, height: 900, deviceScaleFactor: 1, mobile: false,
  }, sessionId);

  await cdp.send('Page.navigate', { url: 'http://fds.local/' }, sessionId);
  await new Promise(r => setTimeout(r, 1100));

  async function inspectPane(triggerId, paneId, shotFile) {
    await cdp.send('Runtime.evaluate', {
      returnByValue: true,
      expression: `(() => {
        const trig = document.getElementById(${JSON.stringify(triggerId)});
        if (trig) {
          trig.dispatchEvent(new MouseEvent('mouseenter', { bubbles: true }));
        }
        return true;
      })()`,
    }, sessionId);
    await new Promise(r => setTimeout(r, 400));

    const info = await cdp.send('Runtime.evaluate', {
      returnByValue: true,
      expression: `(() => {
        const pane = document.getElementById(${JSON.stringify(paneId)});
        if (!pane) return null;
        const lastCol = pane.querySelector('.grid > div:last-child');
        const footerBox = lastCol?.querySelector('.border-t');
        const links = footerBox ? Array.from(footerBox.querySelectorAll('a')).map(a => {
          const r = a.getBoundingClientRect();
          return { text: a.innerText.trim(), left: Math.round(r.left), top: Math.round(r.top), bottom: Math.round(r.bottom), color: getComputedStyle(a).color };
        }) : [];
        return {
          paneVisible: getComputedStyle(pane).display !== 'none' && parseFloat(getComputedStyle(pane).opacity) > 0.5,
          stackedVertically: links.length === 2 && links[1].top >= links[0].bottom && Math.abs(links[1].left - links[0].left) <= 2,
          hasHubLayananText: pane.innerText.includes('Hub Layanan'),
          col1HeadTop: Math.round(pane.querySelector('.grid > div:first-child')?.getBoundingClientRect().top ?? 0),
          col2HeadTop: Math.round(pane.querySelector('.grid > div:nth-child(2)')?.getBoundingClientRect().top ?? 0),
          links,
        };
      })()`,
    }, sessionId);

    const { data } = await cdp.send('Page.captureScreenshot', { format: 'png' }, sessionId);
    fs.writeFileSync(path.join(OUT_DIR, shotFile), Buffer.from(data, 'base64'));
    return info.result.value;
  }

  const produk = await inspectPane('produk-trigger', 'mega-pane-produk', 'verify-mega-produk.png');
  const layanan = await inspectPane('layanan-trigger', 'mega-pane-layanan', 'verify-mega-layanan.png');

  console.log(JSON.stringify({ produk, layanan }, null, 2));
  cdp.close();
  chrome.kill();
}

run().catch((err) => {
  console.error(err);
  chrome.kill();
  process.exit(1);
});
