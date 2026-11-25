import { spawn } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const OUT_DIR = 'C:\\Users\\BILALIHSAN\\.gemini\\antigravity\\brain\\ae4c1f82-adc5-4176-8536-5b8d0fecf760\\scratch';
const PROFILE_DIR = path.join(OUT_DIR, 'chrome-profile-btn');
const PORT = 9248;

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
    const listeners = new Map();
    ws.addEventListener('open', () => resolve({
      send(method, params = {}, sessionId) {
        const id = nextId++;
        const payload = { id, method, params };
        if (sessionId) payload.sessionId = sessionId;
        ws.send(JSON.stringify(payload));
        return new Promise((res, rej) => pending.set(id, { res, rej }));
      },
      on(ev, fn) {
        if (!listeners.has(ev)) listeners.set(ev, new Set());
        listeners.get(ev).add(fn);
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
      } else if (data.method && listeners.has(data.method)) {
        for (const fn of listeners.get(data.method)) fn(data);
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
  await cdp.send('DOM.enable', {}, sessionId);

  const runtimeErrors = [];
  cdp.on('Runtime.exceptionThrown', (ev) => {
    runtimeErrors.push(ev.params?.exceptionDetails?.text || 'exception');
  });

  async function openRoute(url, width = 1440, height = 900) {
    await cdp.send('Emulation.setDeviceMetricsOverride', {
      width, height, deviceScaleFactor: 1, mobile: width < 768,
    }, sessionId);
    await cdp.send('Page.navigate', { url }, sessionId);
    await new Promise(r => setTimeout(r, 1000));
    await cdp.send('Runtime.evaluate', {
      expression: `new Promise(r => { if (document.readyState === 'complete') setTimeout(r, 350); else window.addEventListener('load', () => setTimeout(r, 350)); })`,
      awaitPromise: true,
    }, sessionId);
  }

  async function shotSelector(selector, filename) {
    await cdp.send('Runtime.evaluate', {
      expression: `(() => {
        const el = document.querySelector(${JSON.stringify(selector)});
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
      })()`,
    }, sessionId);
    await new Promise(r => setTimeout(r, 550));
    const { data } = await cdp.send('Page.captureScreenshot', { format: 'png' }, sessionId);
    fs.writeFileSync(path.join(OUT_DIR, filename), Buffer.from(data, 'base64'));
  }

  async function measureCentering(containerSelector) {
    const res = await cdp.send('Runtime.evaluate', {
      returnByValue: true,
      expression: `(() => {
        const wrap = document.querySelector(${JSON.stringify(containerSelector)});
        if (!wrap) return null;
        const left = wrap.children[0]?.getBoundingClientRect();
        const right = wrap.children[1]?.getBoundingClientRect();
        if (!left || !right) return null;
        const leftMid = left.top + left.height / 2;
        const rightMid = right.top + right.height / 2;
        return {
          alignItems: getComputedStyle(wrap).alignItems,
          leftH: Math.round(left.height),
          rightH: Math.round(right.height),
          centerDiffPx: Math.round((rightMid - leftMid) * 10) / 10,
          overflowPx: Math.max(0, document.documentElement.scrollWidth - window.innerWidth),
        };
      })()`,
    }, sessionId);
    return res.result.value;
  }

  const out = {};

  await openRoute('http://fds.local/');
  out.homeSolusi = await measureCentering('#solusi-header-container > div');
  out.homeProduk = await measureCentering('#produk > div > div:first-child');
  out.homeBlog = await measureCentering('#news-header-container > div');
  await shotSelector('#solusi', 'verify-home-solusi.png');
  await shotSelector('#produk', 'verify-home-produk.png');
  await shotSelector('#blog', 'verify-home-blog.png');

  await openRoute('http://fds.local/', 390, 844);
  out.homeMobileOverflowPx = (await cdp.send('Runtime.evaluate', {
    returnByValue: true,
    expression: `Math.max(0, document.documentElement.scrollWidth - window.innerWidth)`,
  }, sessionId)).result.value;

  await openRoute('http://fds.local/produk/');
  out.produkAgri = await measureCentering('#kategori-agrikultur > div > div:first-child');
  out.produkPeta = await measureCentering('#kategori-pemetaan > div > div:first-child');
  out.produkKargo = await measureCentering('#kategori-kargo > div > div:first-child');
  await shotSelector('#kategori-agrikultur', 'verify-produk-sec.png');

  await openRoute('http://fds.local/layanan/pertambangan/');
  out.sektorSplit = await measureCentering('.fds-sec-head--split');
  await shotSelector('.fds-sec-head--split', 'verify-sektor-split.png');

  await openRoute('http://fds.local/kontak/');
  out.kontakMap = await measureCentering('.fds-kontak-map-head');
  await shotSelector('#lokasi-workshop', 'verify-kontak-map.png');

  out.runtimeErrors = runtimeErrors;
  console.log(JSON.stringify(out, null, 2));

  cdp.close();
  chrome.kill();
}

run().catch((err) => {
  console.error(err);
  chrome.kill();
  process.exit(1);
});
