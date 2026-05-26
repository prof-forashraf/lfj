// Sets a Node polyfill for the Web Crypto API before running Vite build
if (typeof globalThis.crypto === 'undefined') {
  try {
    // Node 16+ exposes webcrypto at require('crypto').webcrypto
    const nodeCrypto = require('crypto');
    if (nodeCrypto && nodeCrypto.webcrypto) {
      globalThis.crypto = nodeCrypto.webcrypto;
    }
  } catch (e) {
    // ignore — if this fails, Vite build may still fail downstream
  }
}

// Run Vite build programmatically so the polyfill is applied before modules are loaded
(async () => {
  try {
    const { build } = require('vite');
    await build();
  } catch (err) {
    console.error(err);
    process.exit(1);
  }
})();
