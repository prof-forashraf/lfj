const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const FRONTEND = path.join(ROOT, 'frontend', 'src');

function readFile(filepath) {
  return fs.readFileSync(filepath, 'utf8');
}

function collectFiles(dir, patterns) {
  const out = [];
  for (const name of fs.readdirSync(dir)) {
    const full = path.join(dir, name);
    if (fs.statSync(full).isDirectory()) {
      out.push(...collectFiles(full, patterns));
    } else if (patterns.some((pat) => full.endsWith(pat))) {
      out.push(full);
    }
  }
  return out;
}

function parseRoutes() {
  const app = readFile(path.join(FRONTEND, 'App.tsx'));
  
  // Build component map from lazy imports
  const importLines = app.split(/\r?\n/).filter((line) => line.includes('const ') && line.includes('React.lazy'));
  const componentMap = {};
  for (const line of importLines) {
    const match = line.match(/const\s+(\w+)\s*=\s*React\.lazy\(/);
    const importMatch = line.match(/import\("\.\/pages\/([^"]+)"/);
    if (match && importMatch) {
      componentMap[match[1]] = importMatch[1];
    }
  }
  
  // Extract routes: handle both simple and complex Route definitions
  const routeRegex = /<Route\s+path="([^"]+)"\s+element={([^}]*)}[^>]*>/g;
  const routes = [];
  let m;
  while ((m = routeRegex.exec(app))) {
    const pathStr = m[1];
    const elementContent = m[2];
    
    // Extract the component name from element content
    const componentMatch = elementContent.match(/<(\w+)\s*(?:\/|[^>])/);
    if (componentMatch) {
      const componentName = componentMatch[1];
      const componentFile = componentMap[componentName] || componentName;
      routes.push({ path: pathStr, component: componentFile });
    }
  }
  return routes;
}

function normalizeTarget(target) {
  if (!target) return null;
  // Remove query params and hash
  const normalized = target.split(/[?#]/)[0].replace(/\/+/g, '/');
  if (normalized === '') return '/';
  if (!normalized.startsWith('/')) return null;
  return normalized;
}

function matchRoute(target, routes) {
  if (!target) return false;
  const normalized = normalizeTarget(target);
  if (!normalized) return false;
  for (const route of routes) {
    if (route.path === normalized) return true;
    const pattern = '^' + route.path.replace(/:[^/]+/g, '[^/]+') + '$';
    if (new RegExp(pattern).test(normalized)) return true;
  }
  return false;
}

function parseLinkTargets(content, filepath) {
  const results = [];
  const patterns = [
    /to=\{"([^"]+)"\}/g,
    /to=\{'([^']+)'\}/g,
    /to=\{`([^`]+)`\}/g,
    /to="([^"]+)"/g,
    /to='([^']+)'/g,
    /href=\{"([^"]+)"\}/g,
    /href=\{'([^']+)'\}/g,
    /href=\{`([^`]+)`\}/g,
    /href="([^"]+)"/g,
    /href='([^']+)'/g,
  ];
  for (const pat of patterns) {
    let match;
    while ((match = pat.exec(content))) {
      let raw = match[1];
      // Handle template variables
      if (/\$\{/.test(raw)) {
        raw = raw.replace(/\$\{[^}]+\}/g, ':param');
      }
      const target = normalizeTarget(raw);
      if (!target) continue;
      results.push({ target, filepath, line: content.substr(0, match.index).split(/\r?\n/).length });
    }
  }
  return results;
}

function isInternalLink(target) {
  if (!target) return false;
  if (!target.startsWith('/')) return false;
  if (target.startsWith('//')) return false;
  return true;
}

function buildReport() {
  const routes = parseRoutes();
  const files = collectFiles(FRONTEND, ['.ts', '.tsx']);
  const links = [];
  for (const file of files) {
    const content = readFile(file);
    links.push(...parseLinkTargets(content, path.relative(ROOT, file)));
  }
  const internalLinks = links.filter((item) => isInternalLink(item.target));

  const incoming = new Map(routes.map((r) => [r.path, []]));
  const outgoing = new Map(routes.map((r) => [r.path, []]));

  const unknownLinks = [];
  for (const item of internalLinks) {
    const target = item.target;
    let matched = false;
    for (const route of routes) {
      if (route.path === target) {
        matched = true;
        incoming.get(route.path).push(item);
        break;
      }
      if (route.path === '*') continue;
      const pattern = '^' + route.path.replace(/[.+?^${}()|[\]\\]/g, '\\$&').replace(/:[^/]+/g, '[^/]+') + '$';
      try {
        if (new RegExp(pattern).test(target)) {
          matched = true;
          incoming.get(route.path).push(item);
          break;
        }
      } catch (e) {
        // Skip invalid regex patterns
      }
    }
    if (!matched) {
      unknownLinks.push(item);
    }
  }

  for (const item of internalLinks) {
    const file = item.filepath;
    for (const route of routes) {
      const component = route.component;
      if (file.includes(component + '.tsx') || file.includes(component + '.ts')) {
        outgoing.get(route.path).push(item);
      }
    }
  }

  const orphanRoutes = routes.filter((route) => 
    incoming.get(route.path).length === 0 && 
    route.path !== '*' && 
    route.path !== '/dashboard'
  );
  const leafRoutes = routes.filter((route) => 
    outgoing.get(route.path).length === 0 && 
    route.path !== '*'
  );

  return { routes, internalLinks, unknownLinks, incoming, outgoing, orphanRoutes, leafRoutes };
}

function writeReport(report) {
  const lines = [];
  lines.push('# Link Navigation Audit');
  lines.push('');
  lines.push('## Route Definitions');
  for (const route of report.routes) {
    lines.push('- `' + route.path + '` => component `' + route.component + '`');
  }
  lines.push('');
  lines.push('## Broken / Unknown Internal Link Targets');
  if (report.unknownLinks.length === 0) {
    lines.push('- None found.');
  } else {
    for (const item of report.unknownLinks) {
      lines.push('- `' + item.target + '` referenced in `' + item.filepath + '` at line ' + item.line);
    }
  }
  lines.push('');
  lines.push('## Orphan Routes (no incoming internal links found in source)');
  if (report.orphanRoutes.length === 0) {
    lines.push('- None found.');
  } else {
    for (const route of report.orphanRoutes) {
      lines.push('- `' + route.path + '` (component: `' + route.component + '`)');
    }
  }
  lines.push('');
  lines.push('## Routes with no outgoing internal links in their component file');
  if (report.leafRoutes.length === 0) {
    lines.push('- None found.');
  } else {
    for (const route of report.leafRoutes) {
      lines.push('- `' + route.path + '` (component: `' + route.component + '`)');
    }
  }
  fs.writeFileSync(path.join(ROOT, 'deploy', 'link-navigation-report.md'), lines.join('\n'));
}

const report = buildReport();
writeReport(report);
console.log('Link navigation report written to deploy/link-navigation-report.md');
process.exit(report.unknownLinks.length === 0 ? 0 : 1);
