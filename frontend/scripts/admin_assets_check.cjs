const { chromium } = require('playwright');

(async () => {
  const url = process.env.ADMIN_ASSET_BASE_URL || 'http://127.0.0.1:9000/admin/login';
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  const assetRequests = [];
  const failedAssets = [];
  const consoleErrors = [];

  page.on('requestfinished', async (request) => {
    const resourceType = request.resourceType();
    if (resourceType === 'stylesheet' || resourceType === 'script') {
      const response = await request.response();
      const status = response.status();
      const headers = response.headers();
      assetRequests.push({ url: request.url(), status, headers });
      if (status >= 400) failedAssets.push({ url: request.url(), status });
    }
  });

  page.on('requestfailed', (request) => {
    const resourceType = request.resourceType();
    if (resourceType === 'stylesheet' || resourceType === 'script') {
      failedAssets.push({ url: request.url(), error: request.failure()?.errorText || 'REQUEST_FAILED' });
    }
  });

  page.on('console', (msg) => {
    if (msg.type() === 'error') consoleErrors.push(msg.text());
  });

  try {
    const resp = await page.goto(url, { waitUntil: 'load', timeout: 30000 });
    if (!resp) throw new Error('No response from server');
    const status = resp.status();
    console.log('Admin page response status:', status);

    await page.waitForTimeout(1500);

    // Check presence of filament CSS link in document
    const hasFilamentLink = await page.evaluate(() => {
      return !!document.querySelector('link[href*="/css/filament/"]');
    });

    console.log('Found filament link tag in HTML:', hasFilamentLink);

    console.log('Asset requests captured:', assetRequests.length);
    if (assetRequests.length > 0) {
      for (const a of assetRequests) {
        console.log('-', a.url, 'status=', a.status, 'content-type=', a.headers['content-type']);
      }
    }

    if (failedAssets.length > 0) {
      console.error('Failed asset requests:', JSON.stringify(failedAssets, null, 2));
    }

    if (consoleErrors.length > 0) {
      console.error('Console errors on page:', JSON.stringify(consoleErrors, null, 2));
    }

    const criticalCss = assetRequests.find((a) => a.url.includes('/css/filament/filament/app.css'));
    if (!criticalCss) {
      console.error('Critical filament CSS not requested by the page.');
      process.exit(1);
    }
    if (criticalCss.status !== 200) {
      console.error('Filament CSS returned non-200 status:', criticalCss.status);
      process.exit(1);
    }
    const ct = (criticalCss.headers['content-type'] || '').toLowerCase();
    if (!ct.includes('text/css')) {
      console.error('Filament CSS content-type incorrect:', criticalCss.headers['content-type']);
      process.exit(1);
    }

    if (failedAssets.length === 0 && consoleErrors.length === 0) {
      console.log('RESULT: Admin assets OK.');
      process.exit(0);
    }

    console.error('RESULT: Asset issues detected.');
    process.exit(1);
  } catch (err) {
    console.error('ERROR checking admin assets:', err && err.message);
    process.exit(1);
  } finally {
    await browser.close();
  }
})();
