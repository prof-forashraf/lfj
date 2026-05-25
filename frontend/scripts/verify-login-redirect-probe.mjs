import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ ignoreHTTPSErrors: true });
await context.addInitScript(() => {
  window.__submitLogs = [];
});
const page = await context.newPage();

const network = [];
page.on('request', (req) => {
  if (req.url().includes('/api/') || req.url().includes('/login') || req.url().includes('/logout') || req.url().includes('/sanctum/csrf-cookie')) {
    network.push({ stage: 'request', url: req.url(), method: req.method(), resourceType: req.resourceType() });
  }
});
page.on('response', async (res) => {
  if (res.url().includes('/api/') || res.url().includes('/login') || res.url().includes('/logout') || res.url().includes('/sanctum/csrf-cookie')) {
    network.push({ stage: 'response', url: res.url(), status: res.status(), resourceType: res.request().resourceType() });
  }
});

await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'load', timeout: 30000 });
const loginUrl = page.url();
const initialEmail = await page.inputValue('input[name="email"]').catch(() => 'N/A');
const initialPassword = await page.inputValue('input[name="password"]').catch(() => 'N/A');

await page.fill('input[name="email"]', 'user@latestfashionjewellery.com');
await page.fill('input[name="password"]', 'user123');
const submitButton = await page.locator('button[type="submit"]').first();
await submitButton.click();
await page.waitForURL('**/dashboard', { timeout: 30000 }).catch(() => {});
const postLoginUrl = page.url();
const dashboardVisible = await page.locator('text=Welcome back').first().waitFor({ timeout: 10000 }).then(() => true).catch(() => false);

await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'load', timeout: 30000 }).catch(() => {});
await page.waitForTimeout(10000);
const loginAgainUrl = page.url();
const loginAgainRedirected = loginAgainUrl !== 'http://127.0.0.1:8000/login';
const loginAgainTarget = loginAgainUrl;

console.log(JSON.stringify({
  loginUrl,
  initialEmail,
  initialPassword,
  postLoginUrl,
  dashboardVisible,
  loginAgainUrl,
  loginAgainRedirected,
  loginAgainTarget,
  network,
  submitLogs: await page.evaluate(() => window.__submitLogs),
}, null, 2));

await browser.close();
