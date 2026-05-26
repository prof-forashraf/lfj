#!/usr/bin/env node
// Safe copy of frontend/dist into backend/public with a dry-run report.
// Usage: node scripts/safe_copy_dist_and_report.js --apply
const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const APPLY = args.includes('--apply');

const repoRoot = path.resolve(__dirname, '..');
const distDir = path.join(repoRoot, 'frontend', 'dist');
const publicDir = path.join(repoRoot, 'backend', 'public');

function listFiles(dir) {
  const results = [];
  if (!fs.existsSync(dir)) return results;
  const items = fs.readdirSync(dir, { withFileTypes: true });
  for (const item of items) {
    const full = path.join(dir, item.name);
    if (item.isDirectory()) {
      const nested = listFiles(full).map(p => path.join(item.name, p));
      results.push(...nested);
    } else if (item.isFile()) {
      results.push(item.name);
    }
  }
  return results;
}

function walkAndCopy(src, dst) {
  const entries = fs.readdirSync(src, { withFileTypes: true });
  let copied = 0;
  for (const entry of entries) {
    const srcPath = path.join(src, entry.name);
    const dstPath = path.join(dst, entry.name);
    if (entry.isDirectory()) {
      if (!fs.existsSync(dstPath)) {
        if (APPLY) fs.mkdirSync(dstPath, { recursive: true });
      }
      const c = walkAndCopy(srcPath, dstPath);
      copied += c;
    } else if (entry.isFile()) {
      const existing = fs.existsSync(dstPath);
      if (APPLY) {
        fs.copyFileSync(srcPath, dstPath);
      }
      copied += 1;
      console.log(`${APPLY ? 'COPIED' : 'DRY-RUN'}: ${path.relative(repoRoot, dstPath)}${existing ? ' (overwritten)' : ''}`);
    }
  }
  return copied;
}

if (!fs.existsSync(distDir)) {
  console.error(`frontend dist directory not found at ${distDir}`);
  process.exit(2);
}
if (!fs.existsSync(publicDir)) {
  console.error(`backend public directory not found at ${publicDir}`);
  process.exit(2);
}

console.log(`Mode: ${APPLY ? 'apply' : 'dry-run'}`);
console.log(`Source: ${distDir}`);
console.log(`Target: ${publicDir}`);

const total = walkAndCopy(distDir, publicDir);
console.log(`\nFiles processed: ${total}`);
if (!APPLY) {
  console.log('Run with --apply to perform the copy.');
}

process.exit(0);
