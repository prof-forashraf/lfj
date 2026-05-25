import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ ignoreHTTPSErrors: true });
const page = await context.newPage();

await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'load', timeout: 30000 });
const loginResponse = await page.evaluate(async () => {
  const csrf = await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
  const login = await fetch('/api/login', {
    method: 'POST',
    credentials: 'include',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email: 'user@latestfashionjewellery.com', password: 'user123', remember: false }),
  });
  const body = await login.json().catch(() => null);
  return {
    csrfStatus: csrf.status,
    loginStatus: login.status,
    loginHeaders: {
      'content-type': login.headers.get('content-type'),
    },
    loginBody: body,
  };
});

console.log(JSON.stringify(loginResponse, null, 2));
await browser.close();
