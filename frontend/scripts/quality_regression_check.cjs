const { chromium } = require('playwright');

(async () => {
  const url = 'http://127.0.0.1:8000/';
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  const issues = [];

  try {
    await page.goto(url, { waitUntil: 'load', timeout: 45000 });
    
    // Check page title
    const pageTitle = await page.title();
    if (!pageTitle || pageTitle.length === 0) {
      issues.push('Page title is missing or empty');
    } else {
      console.log(`✓ Page title: "${pageTitle}"`);
    }

    // Wait for page content to be ready
    await page.waitForTimeout(3000);

    // Check main sections exist
    const sections = await page.locator('section').count();
    if (sections === 0) {
      issues.push('No sections found on homepage');
    } else {
      console.log(`✓ Found ${sections} sections`);
    }

    // Check hero section exists
    const heroSection = await page.locator('[class*="hero"], [class*="Hero"], h1').first();
    if (!(await heroSection.isVisible())) {
      issues.push('Hero section not visible');
    } else {
      console.log('✓ Hero section visible');
    }

    // Check navigation links
    const navLinks = await page.locator('nav a').count();
    if (navLinks === 0) {
      issues.push('No navigation links found');
    } else {
      console.log(`✓ Found ${navLinks} navigation links`);
    }

    // Check for console errors
    const errors = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        errors.push(msg.text());
      }
    });

    // Scroll to trigger lazy loading
    const height = await page.evaluate(() => document.documentElement.scrollHeight);
    for (let y = 0; y <= height; y += 500) {
      await page.evaluate((scrollY) => window.scrollTo(0, scrollY), y);
      await page.waitForTimeout(250);
    }
    await page.waitForTimeout(2000);

    if (errors.length > 0) {
      issues.push(`Console errors detected: ${errors.join('; ')}`);
    } else {
      console.log('✓ No console errors');
    }

    // Check all images loaded
    const images = await page.locator('img').count();
    const imagesWithContent = await page.evaluate(() => {
      return Array.from(document.querySelectorAll('img')).filter(img => img.naturalWidth > 0).length;
    });

    if (images > 0) {
      console.log(`✓ Found ${images} image elements, ${imagesWithContent} with content loaded`);
      if (imagesWithContent === 0 && images > 0) {
        issues.push('Images found but none have loaded');
      }
    }

    // Check for broken links
    const links = await page.locator('a[href]').count();
    console.log(`✓ Found ${links} links`);

    // Check buttons are interactive
    const buttons = await page.locator('button, [role="button"]').count();
    console.log(`✓ Found ${buttons} buttons`);

    // Verify responsive layout
    const viewport = await page.viewportSize();
    console.log(`✓ Viewport: ${viewport.width}x${viewport.height}`);

    // Summary
    console.log('\n--- QUALITY REGRESSION CHECK ---');
    if (issues.length === 0) {
      console.log('✅ PASSED: No quality regressions detected');
    } else {
      console.log(`❌ FAILED: ${issues.length} issue(s) found:`);
      issues.forEach((issue, i) => {
        console.log(`  ${i + 1}. ${issue}`);
      });
    }

  } catch (error) {
    console.error('ERROR:', error.message);
  } finally {
    await browser.close();
  }
})();
