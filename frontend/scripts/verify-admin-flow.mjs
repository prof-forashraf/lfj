import { chromium } from 'playwright';

const admin = {
  email: 'e2e_admin@example.com',
  password: 'Password123!',
};

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext({ ignoreHTTPSErrors: true });
const page = await context.newPage();

console.log('Navigating to login page...');
await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle', timeout: 30000 });

await page.fill('input[type="email"], input[name="email"]', admin.email);
await page.fill('input[type="password"], input[name="password"]', admin.password);

console.log('Submitting login form...');
const navigationPromise = page.waitForNavigation({ waitUntil: 'networkidle', timeout: 30000 }).catch(() => null);
const [loginResponse] = await Promise.all([
  page.waitForResponse((res) => res.url().includes('/api/login') && res.request().method() === 'POST', { timeout: 15000 }),
  page.click('button[type="submit"], input[type="submit"]'),
]);
await navigationPromise;

let loginBody = '';
try {
  loginBody = await loginResponse.text();
} catch (error) {
  try {
    const buf = await loginResponse.body();
    loginBody = buf.toString('utf-8');
  } catch {
    loginBody = '<no body available>';
  }
}
console.log('Login response status:', loginResponse.status());
console.log('Login response body:', loginBody);

try {
  await page.waitForURL('**/admin**', { timeout: 20000 });
} catch (error) {
  console.warn('Admin redirect did not occur automatically. Current URL:', page.url());
}
await page.waitForLoadState('networkidle', { timeout: 20000 }).catch(() => {});
console.log('Post-login URL:', page.url());

const userResponse = await page.evaluate(async () => {
  const res = await fetch('/api/user', { credentials: 'include' });
  const text = await res.text();
  return { status: res.status, body: text };
});
console.log('API /api/user response:', userResponse);

const pageText = await page.textContent('body');
console.log('Page body snippet:', pageText?.slice(0, 500) ?? '<empty>');

console.log('Attempting to find logout control...');
let logoutLocator = page.locator('button:has-text("Log out"), button:has-text("Logout"), a:has-text("Log out"), a:has-text("Logout")');
let logoutCount = await logoutLocator.count();
if (logoutCount === 0) {
  logoutLocator = page.locator('text=/log\s*out/i');
  logoutCount = await logoutLocator.count();
}
console.log('Logout controls found:', logoutCount);
if (logoutCount > 0) {
  await logoutLocator.first().click();
  await page.waitForLoadState('networkidle', { timeout: 20000 }).catch(() => {});
  console.log('Logged out, current URL:', page.url());
} else {
  console.warn('No logout control found, attempting API logout');
  const logoutResult = await page.evaluate(async () => {
    const res = await fetch('/logout', { method: 'POST', credentials: 'include', headers: { 'Content-Type': 'application/json' } });
    return { status: res.status, body: await res.text() };
  });
  console.log('API logout result:', logoutResult);
}

console.log('Verifying admin page protection after logout...');
const adminResponse = await page.goto('http://127.0.0.1:8000/admin', { waitUntil: 'networkidle', timeout: 30000 });
const finalUrl = page.url();
const adminStatus = adminResponse?.status();
const adminBody = await page.textContent('body');
console.log('Admin access after logout - URL:', finalUrl, 'status:', adminStatus);
console.log('Admin page body snippet:', adminBody?.slice(0, 500) ?? '<empty>');

await browser.close();
