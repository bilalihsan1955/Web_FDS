import { spawn } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';

const CHROME = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const OUT_DIR = 'C:\\Users\\BILALIHSAN\\.gemini\\antigravity\\brain\\ae4c1f82-adc5-4176-8536-5b8d0fecf760\\scratch';
const PROFILE_DIR = path.join(OUT_DIR, 'chrome-profile-ftr');
const PORT = 9249;

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

  async function inspectPage(url, shotName = null) {
    await cdp.send('Emulation.setDeviceMetricsOverride', {
      width: 1440, height: 900, deviceScaleFactor: 1, mobile: false,
    }, sessionId);
    await cdp.send('Page.navigate', { url }, sessionId);
    await new Promise(r => setTimeout(r, 900));
    await cdp.send('Runtime.evaluate', {
      expression: `new Promise(r => { if (document.readyState === 'complete') setTimeout(r, 300); else window.addEventListener('load', () => setTimeout(r, 300)); })`,
      awaitPromise: true,
    }, sessionId);

    const data = await cdp.send('Runtime.evaluate', {
      returnByValue: true,
      expression: `(() => {
        const footer = document.querySelector('footer');
        const main = document.querySelector('main') || document.body;
        const footerCs = footer ? getComputedStyle(footer) : null;
        const footerTop = footer ? footer.getBoundingClientRect().top + window.scrollY : 9e9;

        // Collect rendered top-level sections/wrappers in <main> above footer
        const blocks = [];
        const walkTarget = main.children.length === 1 && main.children[0].tagName === 'DIV' ? main.children[0] : main;
        for (const el of walkTarget.children) {
          if (el.tagName === 'FOOTER' || el.tagName === 'HEADER' || el.tagName === 'SCRIPT' || el.tagName === 'STYLE') continue;
          const r = el.getBoundingClientRect();
          if (r.height < 20) continue;
          const innerSections = Array.from(el.querySelectorAll(':scope > section'));
          const list = innerSections.length > 0 ? innerSections : [el];
          for (const node of list) {
            const nr = node.getBoundingClientRect();
            const cs = getComputedStyle(node);
            blocks.push({
              tag: node.tagName.toLowerCase(),
              id: node.id || el.id || '',
              bg: cs.backgroundColor,
              hasBgImg: cs.backgroundImage !== 'none',
              h: Math.round(nr.height),
              top: Math.round(nr.top + window.scrollY),
            });
          }
        }
        // Filter out floating sticky bars
        const flowBlocks = blocks.filter(b => b.id !== 'fds-mobile-sticky-cta' && b.id !== 'fds-snackbar' && b.top < footerTop + 5);
        const lastBlock = flowBlocks[flowBlocks.length - 1] || null;

        // Grab first blog URL if on /blog/
        const firstPostLink = document.querySelector('a[href*="/20"], article a.fds-stretched-link')?.href || null;

        let consecutiveSameLight = [];
        for (let i = 1; i < flowBlocks.length; i++) {
          const prev = flowBlocks[i - 1];
          const curr = flowBlocks[i];
          if (!prev.hasBgImg && !curr.hasBgImg && prev.bg === curr.bg) {
            consecutiveSameLight.push({ i, prev: prev.id || prev.tag, curr: curr.id || curr.tag, bg: curr.bg });
          }
        }

        const footerBg = footerCs ? footerCs.backgroundColor : null;
        const matchesFooter = lastBlock ? (!lastBlock.hasBgImg && lastBlock.bg === footerBg) : null;

        return {
          statusOk: document.body.innerText.trim().length > 0,
          overflowPx: Math.max(0, document.documentElement.scrollWidth - window.innerWidth),
          footerBg,
          blockCount: flowBlocks.length,
          lastBlock,
          matchesFooter,
          consecutiveSameLight,
          firstPostLink,
        };
      })()`,
    }, sessionId);

    const val = data.result.value;
    if (shotName) {
      await cdp.send('Runtime.evaluate', {
        expression: `(() => {
          const footer = document.querySelector('footer');
          if (footer) window.scrollTo({ top: Math.max(0, footer.offsetTop - 420), behavior: 'instant' });
        })()`,
      }, sessionId);
      await new Promise(r => setTimeout(r, 450));
      const { data: png } = await cdp.send('Page.captureScreenshot', { format: 'png' }, sessionId);
      fs.writeFileSync(path.join(OUT_DIR, shotName), Buffer.from(png, 'base64'));
    }
    return val;
  }

  const routes = [
    ['home', 'http://fds.local/', null],
    ['produk', 'http://fds.local/produk/', null],
    ['kat_agri', 'http://fds.local/kategori-drone/agrikultur/', 'verify-footer-kat.png'],
    ['kat_pemetaan', 'http://fds.local/kategori-drone/pemetaan-gis/', null],
    ['kat_kargo', 'http://fds.local/kategori-drone/kargo/', null],
    ['drone_detail', 'http://fds.local/drone/ferto-10l/', null],
    ['bandingkan', 'http://fds.local/bandingkan/', null],
    ['layanan_hub', 'http://fds.local/layanan/', 'verify-footer-hub.png'],
    ['layanan_sektor', 'http://fds.local/layanan/pertambangan/', 'verify-footer-sektor.png'],
    ['layanan_kat', 'http://fds.local/layanan/jasa-foto-udara-lidar/', 'verify-footer-katlayanan.png'],
    ['tentang_kami', 'http://fds.local/tentang-kami/', null],
    ['kontak', 'http://fds.local/kontak/', null],
    ['terima_kasih', 'http://fds.local/terima-kasih/', 'verify-footer-tk.png'],
    ['blog', 'http://fds.local/blog/', null],
    ['search', 'http://fds.local/?s=ferto', null],
    ['privacy', 'http://fds.local/privacy-policy/', 'verify-footer-privacy.png'],
    ['err404', 'http://fds.local/halaman-uji-404-tidak-ada/', 'verify-footer-404.png'],
  ];

  const results = {};
  for (const [key, url, shot] of routes) {
    results[key] = await inspectPage(url, shot);
  }
  if (results.blog?.firstPostLink) {
    results.single_post = await inspectPage(results.blog.firstPostLink, null);
  }

  results.runtimeErrors = runtimeErrors;
  console.log(JSON.stringify(results, null, 2));

  cdp.close();
  chrome.kill();
}

run().catch((err) => {
  console.error(err);
  chrome.kill();
  process.exit(1);
});
