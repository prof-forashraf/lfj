// CommonJS wrapper for setting globalThis.crypto and running Vite build
if (typeof globalThis.crypto === 'undefined') {
  try {
    const nodeCrypto = require('crypto');
    if (nodeCrypto && nodeCrypto.webcrypto) {
      globalThis.crypto = nodeCrypto.webcrypto;
    }
  } catch (e) {
    // ignore
  }
}

try {
  const { build } = require('vite');
  // build returns a Promise
  build().catch(err => {
    console.error(err);
    process.exit(1);
  });
} catch (err) {
  console.error(err);
  process.exit(1);
}
