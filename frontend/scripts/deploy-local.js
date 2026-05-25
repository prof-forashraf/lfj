import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const distDir = path.resolve(__dirname, '..', 'dist');
const publicDir = path.resolve(__dirname, '..', '..', 'backend', 'public');

if (!fs.existsSync(distDir)) {
  console.error(`Error: frontend dist directory not found at ${distDir}`);
  process.exit(1);
}

if (!fs.existsSync(publicDir)) {
  console.error(`Error: backend public directory not found at ${publicDir}`);
  process.exit(1);
}

// Use the project-level safe deploy script which preserves server-side assets
const safeScript = path.resolve(__dirname, '..', '..', 'scripts', 'safe_copy_dist_and_report.js');
if (!fs.existsSync(safeScript)) {
  console.error('Safe deploy script not found at', safeScript);
  process.exit(1);
}

try {
  // Run safe deploy in apply mode to merge frontend dist into backend/public without overwriting css/js
  execSync(`node "${safeScript}" --apply`, { stdio: 'inherit' });
  console.log('✅ Frontend production assets merged to backend/public using safe deploy.');
} catch (err) {
  console.error('Deploy failed:', err && err.message);
  process.exit(1);
}
