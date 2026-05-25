const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();

  await page.evaluateOnNewDocument(() => {
    window.__submitLogs = [];
    const origSubmit = HTMLFormElement.prototype.submit;
    HTMLFormElement.prototype.submit = function (...args) {
      window.__submitLogs.push('proto-submit');
      return origSubmit.apply(this, args);
    };
    const origAddEventListener = HTMLFormElement.prototype.addEventListener;
    HTMLFormElement.prototype.addEventListener = function (type, listener, options) {
      if (type === 'submit') {
        window.__submitLogs.push('addEventListener-submit');
      }
      return origAddEventListener.call(this, type, listener, options);
    };
    document.addEventListener('submit', (event) => {
      window.__submitLogs.push('submit-event');
    }, true);
  });

  const network = [];
  page.on('request', (req) => {
    if (req.url().includes('/api/login') || req.url().includes('/api/user') || req.url().includes('/sanctum/csrf-cookie')) {
      network.push({ url: req.url(), method: req.method(), type: req.resourceType() });
    }
  });
  page.on('response', async (res) => {
    if (res.url().includes('/api/login') || res.url().includes('/api/user') || res.url().includes('/sanctum/csrf-cookie')) {
      network.push({ url: res.url(), status: res.status(), type: res.request().resourceType() });
    }
  });

  await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'load', timeout: 30000 });
  const loginUrl = page.url();
  const email = await page.inputValue('input[name="email"]').catch(() => 'N/A');
  const password = await page.inputValue('input[name="password"]').catch(() => 'N/A');
  const formAction = await page.$eval('form', (form) => ({ action: form.getAttribute('action'), method: form.getAttribute('method'), autocomplete: form.getAttribute('autocomplete') })).catch(() => null);
  const inputs = await page.$$eval('form input', (elems) => elems.map((el) => ({ name: el.name, type: el.type, value: el.value, autocomplete: el.autocomplete, defaultValue: el.defaultValue })));
  await page.waitForTimeout(8000);
  const urlAfterWait = page.url();
  const submitLogs = await page.evaluate(() => window.__submitLogs);

  console.log(JSON.stringify({ loginUrl, urlAfterWait, email, password, formAction, inputs, network, submitLogs }, null, 2));
  await browser.close();
})();
