const { chromium } = require('playwright');

(async () => {
  const url = 'http://127.0.0.1:8000/';
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  const imageRequests = [];
  const failedImages = [];
  const invalidHostImageRequests = [];

  page.on('requestfinished', async (request) => {
    if (request.resourceType() === 'image') {
      const response = await request.response();
      const status = response.status();
      imageRequests.push({ url: request.url(), status });
      if (status >= 400) {
        failedImages.push({ url: request.url(), status });
      }
      if (request.url().includes('localhost.local') || request.url().endsWith('.local')) {
        invalidHostImageRequests.push(request.url());
      }
    }
  });

  page.on('requestfailed', (request) => {
    if (request.resourceType() === 'image') {
      failedImages.push({ url: request.url(), error: request.failure()?.errorText || 'REQUEST_FAILED' });
    }
  });

  try {
    await page.goto(url, { waitUntil: 'networkidle' });

    const performanceTiming = await page.evaluate(() => {
      const nav = performance.getEntriesByType('navigation')[0] || {};
      const paint = performance.getEntriesByType('paint');
      return {
        url: location.href,
        domContentLoaded: nav.domContentLoadedEventEnd || performance.timing.domContentLoadedEventEnd,
        loadEventEnd: nav.loadEventEnd || performance.timing.loadEventEnd,
        responseEnd: nav.responseEnd || performance.timing.responseEnd,
        domInteractive: nav.domInteractive || performance.timing.domInteractive,
        firstPaint: paint.find((entry) => entry.name === 'first-paint')?.startTime || null,
        firstContentfulPaint: paint.find((entry) => entry.name === 'first-contentful-paint')?.startTime || null,
      };
    });

    const images = await page.evaluate(() => {
      return Array.from(document.querySelectorAll('img')).map((img) => ({
        src: img.currentSrc || img.src || '',
        naturalWidth: img.naturalWidth,
        naturalHeight: img.naturalHeight,
        complete: img.complete,
        alt: img.alt || null,
      }));
    });

    const missingImgElements = images.filter((img) => !img.src || img.naturalWidth === 0 || img.naturalHeight === 0);

    console.log('HOMEPAGE PERFORMANCE CHECK');
    console.log('URL:', url);
    console.log('Navigation timing:', performanceTiming);
    console.log('Total image elements found:', images.length);
    console.log('Images with natural size 0 or missing src:', missingImgElements.length);
    if (missingImgElements.length > 0) {
      console.log('Missing or broken image elements:', JSON.stringify(missingImgElements, null, 2));
    }
    console.log('Total image network requests:', imageRequests.length);
    console.log('Failed image requests:', JSON.stringify(failedImages, null, 2));
    if (invalidHostImageRequests.length > 0) {
      console.log('Image requests using invalid .local hostname:', JSON.stringify(invalidHostImageRequests, null, 2));
    }

    if (failedImages.length === 0 && missingImgElements.length === 0) {
      console.log('RESULT: Homepage images loaded successfully.');
      process.exit(0);
    }
    console.error('RESULT: Some homepage images failed to load or are missing.');
    process.exit(1);
  } catch (error) {
    console.error('ERROR loading homepage:', error.message || error);
    process.exit(1);
  } finally {
    await browser.close();
  }
})();
