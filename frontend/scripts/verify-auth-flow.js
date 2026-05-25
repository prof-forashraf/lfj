import { chromium } from 'playwright';

const homepage = 'http://127.0.0.1:8000';
const loginUrl = `${homepage}/login`;
const dashboardUrl = `${homepage}/dashboard`;
const credentials = {
  email: 'user@latestfashionjewellery.com',
  password: 'user123',
};

const capture = {
  console: [],
  failedRequests: [],
  network: [],
};

const recordResponse = async (response) => {
  try {
    const url = response.url();
    const status = response.status();
    const headers = response.headers();
    const request = response.request();
    const method = request.method();
    if (url.includes('/login') || url.includes('/logout') || url.includes('/user') || url.includes('/sanctum/csrf-cookie') || url.includes('/api')) {
      capture.network.push({ url, status, method, contentType: headers['content-type'] || null });
    }
  } catch (error) {
    capture.console.push({ type: 'error', text: `Response capture failed: ${error.message}` });
  }
};

const isTruthy = (value) => value !== null && value !== undefined && value !== '';

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ ignoreHTTPSErrors: true });
  const page = await context.newPage();

  page.on('console', (msg) => {
    capture.console.push({ type: msg.type(), text: msg.text() });
  });

  page.on('requestfailed', (request) => {
    const errorText = request.failure()?.errorText;
    if (errorText === 'net::ERR_ABORTED') {
      return;
    }
    capture.failedRequests.push({ url: request.url(), method: request.method(), failure: errorText });
  });

  page.on('response', recordResponse);

  try {
    await page.goto(homepage, { waitUntil: 'domcontentloaded', timeout: 30000 });
    const signInLink = page.locator('a[href="/login"], a:has-text("Sign In"), button:has-text("Sign In")').first();
    await signInLink.waitFor({ timeout: 30000 });
    const signInCount = await signInLink.count();
    if (!signInCount) {
      const plainCount = await page.locator('text=Sign In').count();
      console.log('HOME PAGE TEXT SIGN IN COUNT', plainCount);
      console.log('HOME PAGE HEAD HTML', await page.content());
      throw new Error('Sign In button/link not found on homepage');
    }

    await Promise.all([
      page.waitForURL('**/login', { timeout: 15000 }),
      signInLink.click(),
    ]);

    await page.waitForLoadState('load');

    const emailValueBefore = await page.inputValue('input[name="email"]');
    const passwordValueBefore = await page.inputValue('input[name="password"]');

    if (isTruthy(emailValueBefore) || isTruthy(passwordValueBefore)) {
      capture.console.push({ type: 'warn', text: 'Login inputs are pre-filled before manual typing' });
    }

    await page.fill('input[name="email"]', credentials.email);
    await page.fill('input[name="password"]', credentials.password);

    const loginButton = page.locator('button[type="submit"]').first();
    const loginButtonCount = await loginButton.count();
    const loginButtonText = await loginButton.textContent();
    console.log('LOGIN BUTTON COUNT', loginButtonCount);
    console.log('LOGIN BUTTON TEXT', loginButtonText?.trim());
    if (!loginButtonCount) {
      throw new Error('Login button not found on login page');
    }
    await Promise.all([
      page.waitForResponse((response) => response.url().endsWith('/api/login') && response.status() < 500, { timeout: 30000 }),
      loginButton.click(),
    ]);

    let finalUrl = page.url();
    if (finalUrl === loginUrl) {
      // The login submission may not navigate immediately, wait for dashboard signal
      await page.waitForTimeout(3000);
      finalUrl = page.url();
    }

    if (!finalUrl.includes('/dashboard')) {
      await page.waitForURL('**/dashboard', { timeout: 15000 }).catch(() => {});
      finalUrl = page.url();
    }

    const cookiesAfterLogin = await context.cookies(homepage);
    const sanctumCookie = cookiesAfterLogin.find((cookie) => cookie.name.includes('XSRF-TOKEN') || cookie.name.includes('laravel_session') || cookie.name.includes('_session'));

    await page.waitForLoadState('load');

    const dashboardVisible = await page.locator('text=Welcome back').first().isVisible().catch(() => false);
    const pageContent = await page.textContent('body');

    // Verify session persistence by reload
    await page.reload({ waitUntil: 'load', timeout: 20000 });
    const afterReloadUrl = page.url();
    const afterReloadDashboardVisible = await page.locator('text=Welcome back').first().waitFor({ timeout: 10000 }).then(() => true).catch(() => false);

    // Logout flow
    const menuToggle = page.locator('button.tsqd-open-btn').first();
    if (await menuToggle.count()) {
      await menuToggle.click();
      const logoutButton = page.getByText(/^Logout$/i).first();
      await logoutButton.waitFor({ timeout: 10000 });
      await Promise.all([
        page.waitForURL('**/login', { timeout: 15000 }).catch(() => {}),
        logoutButton.click(),
      ]).catch(() => {});
    } else {
      const logoutButton = page.getByText(/^Logout$/i).first();
      await Promise.all([
        page.waitForURL('**/login', { timeout: 15000 }).catch(() => {}),
        logoutButton.click(),
      ]).catch(() => {});
    }
    await page.waitForLoadState('load');
    const logoutUrl = page.url();
    const cookiesAfterLogout = await context.cookies(homepage);
    const sessionCookieAfterLogout = cookiesAfterLogout.find((cookie) => cookie.name.includes('laravel_session') || cookie.name.includes('_session') || cookie.name.includes('XSRF-TOKEN'));

    // Protected route redirect after logout
    let protectedRedirectUrl = page.url();
    await page.goto(dashboardUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForURL('**/login', { timeout: 15000 }).catch(() => {});
    protectedRedirectUrl = page.url();
    const loginFormVisibleAfterLogout = await page.locator('input[name="email"]').first().waitFor({ timeout: 10000 }).then(() => true).catch(() => false);

    // Direct login page after logout
    try {
      await page.goto(loginUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
    } catch (error) {
      // If navigation is interrupted by a background redirect, keep current URL
    }
    const directLoginUrl = page.url();

    // Log results
    console.log(JSON.stringify({
      homepageUrl: homepage,
      loginPageUrl: page.url(),
      finalRedirectUrl: finalUrl,
      afterReloadUrl,
      logoutUrl,
      protectedRedirectUrl,
      directLoginUrl,
      emailValueBefore,
      passwordValueBefore,
      sanctumCookie: sanctumCookie ? { name: sanctumCookie.name, value: sanctumCookie.value, domain: sanctumCookie.domain, path: sanctumCookie.path, expires: sanctumCookie.expires } : null,
      sessionCookieAfterLogout: sessionCookieAfterLogout ? { name: sessionCookieAfterLogout.name, value: sessionCookieAfterLogout.value, domain: sessionCookieAfterLogout.domain, path: sessionCookieAfterLogout.path, expires: sessionCookieAfterLogout.expires } : null,
      dashboardVisible,
      afterReloadDashboardVisible,
      network: capture.network,
      console: capture.console,
      failedRequests: capture.failedRequests,
    }, null, 2));
  } catch (error) {
    console.error('ERROR', error.message);
  } finally {
    await browser.close();
  }
})();
