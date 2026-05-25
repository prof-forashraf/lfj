const { chromium } = require('playwright');

const urls = [
  'http://127.0.0.1:8000/',
  'http://127.0.0.1:8000/category/fine-jewellery',
];

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();

  for (const url of urls) {
    const page = await context.newPage();
    const requests = [];
    const failed = [];
    const finished = [];

    page.on('request', (req) => {
      requests.push({ url: req.url(), method: req.method(), resourceType: req.resourceType(), start: Date.now() });
    });
    page.on('requestfailed', (req) => {
      failed.push({ url: req.url(), error: req.failure()?.errorText || 'REQUEST_FAILED' });
    });
    page.on('requestfinished', async (req) => {
      const response = await req.response();
      finished.push({ url: req.url(), status: response?.status() ?? null, type: req.resourceType() });
    });

    console.log('=== DEBUG', url, '===');
    try {
      const response = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 45000 });
      if (response) console.log('resolved status', response.status());
      console.log('readyState', await page.evaluate(() => document.readyState));
    } catch (err) {
      console.error('goto failed:', err.message);
    }

    await page.waitForTimeout(5000);
    console.log('Requests started:', requests.length);
    console.log('Requests finished:', finished.length);
    console.log('Requests failed:', failed.length);
    console.log('Failed list:', JSON.stringify(failed, null, 2));
    console.log('Long-running requests:');
    for (const req of requests) {
      const matching = finished.find((f) => f.url === req.url);
      if (!matching) {
        console.log('- pending:', req.url, req.resourceType, req.method);
      }
    }
    console.log('\n');
    await page.close();
  }

  await browser.close();
})();
