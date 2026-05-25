import { chromium } from 'playwright';

const candidates = [
  { email: 'admin@test.com', password: 'password' },
  { email: 'admin@test.com', password: 'password123' },
  { email: 'admin@example.com', password: 'password' },
  { email: 'admin@example.com', password: 'password123' },
];

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ ignoreHTTPSErrors: true });
const page = await context.newPage();

for (const creds of candidates) {
  await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'load', timeout: 30000 });
  const response = await page.request.get('http://127.0.0.1:8000/sanctum/csrf-cookie');
  const loginResponse = await page.request.post('http://127.0.0.1:8000/api/login', {
    data: {
      email: creds.email,
      password: creds.password,
      remember: false,
    },
    headers: {
      'Content-Type': 'application/json',
    },
  });
  const body = await loginResponse.text();
  console.log(JSON.stringify({ creds, status: loginResponse.status(), body }, null, 2));
}
await browser.close();
