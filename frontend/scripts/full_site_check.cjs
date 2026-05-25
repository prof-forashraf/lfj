const { chromium } = require('playwright');

const baseUrl = process.env.FULL_SITE_BASE_URL || 'http://127.0.0.1:9000';
const routes = [
  '/',
  '/category/fine-jewellery',
  '/tag/gold',
  '/tools/gold-prices',
  '/shop',
  '/shop/collection/new-arrivals',
  '/shop/category/necklaces',
  '/shop/advanced-search',
  '/about',
  '/contact',
  '/events',
  '/events/sample-event',
  '/terms',
  '/privacy',
  '/shipping',
  '/jewellery-studio',
  '/videos',
  '/blog',
  '/blog/sample-post',
  '/quality',
  '/trends',
  '/tools',
  '/tools/virtual-try-on',
  '/tools/carat-converter',
  '/tools/ring-size-converter',
  '/tools/gold-resale-calculator',
  '/tools/diamond-estimator',
  '/tools/custom-cost-estimator',
  '/tools/zakat-calculator',
  '/tools/care-guide',
  '/wonders-of-gold',
  '/cosmic-origins',
  '/modern-technology',
  '/future-frontiers',
  '/wishlist',
  '/login',
  '/register',
  '/forgot-password',
  '/dashboard',
  '/does-not-exist',
];

const summary = {
  pageErrors: [],
  consoleErrors: [],
  failedRequests: [],
  statusFailures: [],
};

const ignoredFailedRequestHosts = [
  'youtube.com',
  'google-analytics.com',
  'googlesyndication.com',
  'doubleclick.net',
  'fonts.googleapis.com',
  'fonts.gstatic.com',
  'images.unsplash.com',
  'fonts.bunny.net',
  'sentry.io',
];

function shouldIgnoreRequest(url) {
  return ignoredFailedRequestHosts.some((host) => url.includes(host));
}

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();

  for (const route of routes) {
    const url = `${baseUrl}${route}`;
    const page = await context.newPage();
    const errors = [];
    const failedRequests = [];
    const consoleErrors = [];
    const pageRequests = [];

    page.on('pageerror', (error) => {
      errors.push(error.message || String(error));
    });

    page.on('console', (msg) => {
      if (msg.type() === 'error') {
        consoleErrors.push(msg.text());
      }
    });

    page.on('requestfailed', (request) => {
      const url = request.url();
      if (shouldIgnoreRequest(url)) return;
      failedRequests.push({ url, status: null, error: request.failure()?.errorText || 'REQUEST_FAILED' });
    });

    page.on('requestfinished', async (request) => {
      const response = await request.response();
      if (!response) return;
      const url = request.url();
      if (shouldIgnoreRequest(url)) return;
      const status = response.status();
      const resourceType = request.resourceType();
      pageRequests.push({ url, resourceType, status });
      if (status >= 400) {
        failedRequests.push({ url, status, resourceType });
      }
    });

    try {
      const response = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 45000 });
      const status = response ? response.status() : null;
      const title = await page.title().catch(() => '');
      await page.waitForTimeout(1000);

      console.log('===', route, '===');
      console.log('Status:', status);
      console.log('Title:', title);
      console.log('Total asset requests:', pageRequests.length);

      if (errors.length) {
        console.log('Page errors:', JSON.stringify(errors, null, 2));
        summary.pageErrors.push({ route, errors });
      }
      if (consoleErrors.length) {
        console.log('Console errors:', JSON.stringify(consoleErrors, null, 2));
        summary.consoleErrors.push({ route, consoleErrors });
      }
      if (failedRequests.length) {
        console.log('Failed/errored requests:', JSON.stringify(failedRequests, null, 2));
        summary.failedRequests.push({ route, failedRequests });
      }
      if (status === null || status >= 500) {
        summary.statusFailures.push({ route, status });
      }
      console.log('');
    } catch (err) {
      console.log('===', route, 'ERROR ===');
      console.log(err && err.message);
      summary.pageErrors.push({ route, errors: [err.message || String(err)] });
    } finally {
      await page.close();
    }
  }

  await browser.close();

  console.log('=== SUMMARY ===');
  console.log('Page errors:', summary.pageErrors.length);
  console.log('Console errors:', summary.consoleErrors.length);
  console.log('Failed requests:', summary.failedRequests.length);
  console.log('Status failures:', summary.statusFailures.length);

  if (summary.pageErrors.length || summary.consoleErrors.length || summary.failedRequests.length || summary.statusFailures.length) {
    process.exit(1);
  }

  process.exit(0);
})();
