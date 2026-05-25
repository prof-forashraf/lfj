import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ ignoreHTTPSErrors: true });
const page = await context.newPage();

let loginResponseBody = null;
page.on('response', async (res) => {
  if (res.url().endsWith('/api/login')) {
    try {
      loginResponseBody = await res.json();
    } catch (error) {
      loginResponseBody = { error: error.message };
    }
  }
});

await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'load', timeout: 30000 });
await page.fill('input[name="email"]', 'user@latestfashionjewellery.com');
await page.fill('input[name="password"]', 'user123');
const submitButton = await page.locator('button[type="submit"]').first();
await Promise.all([
  page.waitForResponse((response) => response.url().endsWith('/api/login') && response.status() < 500, { timeout: 30000 }),
  submitButton.click(),
]);

await page.waitForURL('**/dashboard', { timeout: 30000 }).catch(() => {});
console.log(JSON.stringify({ loginResponseBody, finalUrl: page.url() }, null, 2));
await browser.close();
