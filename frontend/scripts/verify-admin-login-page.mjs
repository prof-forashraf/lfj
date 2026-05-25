import { chromium } from 'playwright';

const candidates = [
  { email: 'e2e_admin@example.com', password: 'Password123!' },
  { email: 'admin@latestfashionjewellery.com', password: 'Password123!' },
  { email: 'e2e_admin@example.com', password: 'password123' },
  { email: 'admin@latestfashionjewellery.com', password: 'password123' },
];

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ ignoreHTTPSErrors: true });
const page = await context.newPage();

for (const creds of candidates) {
  await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle', timeout: 30000 });
  await page.fill('input[type="email"], input[name="email"]', creds.email).catch(() => {});
  await page.fill('input[type="password"], input[name="password"]', creds.password).catch(() => {});
  const [loginResponse] = await Promise.all([
    page.waitForResponse((res) => res.url().includes('/api/login') && res.request().method() === 'POST', { timeout: 15000 }),
    page.click('button[type="submit"], input[type="submit"]'),
  ]);
  let responseBody = '';
  try {
    responseBody = await loginResponse.text();
  } catch (error) {
    try {
      const bodyBuffer = await loginResponse.body();
      responseBody = bodyBuffer.toString('utf-8');
    } catch (innerError) {
      responseBody = String(error);
    }
  }
  const status = loginResponse.status();
  const currentUrl = page.url();
  console.log(JSON.stringify({ creds, status, currentUrl, responseBody: responseBody.slice(0, 800) }, null, 2));
}

await browser.close();
