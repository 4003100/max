// Removes declarations that are provably overridden: same selector text, same at-rule
// context, same property, later declaration wins (respecting !important).
// Skips cases where the later value may be a progressive-enhancement fallback target.
const fs = require('fs');
const postcss = require('postcss');
const [, , inp, out] = process.argv;
const root = postcss.parse(fs.readFileSync(inp, 'utf8'));

const RISKY = /(dvh|svh|lvh|dvw|svw|lvw|cqw|cqh|clamp\(|min\(|max\(|color-mix|env\(|-webkit-|-moz-|-ms-|:has|fit-content|max-content|min-content|lab\(|lch\(|oklch|oklab|light-dark|anchor|round\()/i;
const norm = s => s.replace(/\s+/g, ' ').replace(/\s*([>+~,])\s*/g, '$1').trim();
function ctx(node) {
  const parts = [];
  for (let p = node.parent; p && p.type !== 'root'; p = p.parent) {
    if (p.type === 'atrule') parts.unshift('@' + p.name + ' ' + norm(p.params));
    else if (p.type === 'rule') parts.unshift(norm(p.selector)); // nesting
  }
  return parts.join(' | ');
}

const last = new Map(); // key -> decl that currently wins
let removed = 0;
const skippedAt = new Set(['keyframes', '-webkit-keyframes', 'font-face', 'page', 'property', 'counter-style']);
root.walkDecls(decl => {
  const rule = decl.parent;
  if (rule.type !== 'rule') return;
  for (let p = rule.parent; p && p.type !== 'root'; p = p.parent) if (p.type === 'atrule' && skippedAt.has(p.name.toLowerCase())) return;
  const prop = decl.prop.toLowerCase();
  if (prop.startsWith('--') === false && /^-(webkit|moz|ms|o)-/.test(prop)) return;
  const key = ctx(rule) + ' || ' + norm(rule.selector) + ' || ' + prop;
  const prev = last.get(key);
  if (!prev || !prev.parent) { last.set(key, decl); return; }
  if (prev.important && !decl.important) { decl.remove(); removed++; return; } // later loses
  if (RISKY.test(decl.value) && !RISKY.test(prev.value)) { last.set(key, decl); return; } // keep fallback
  if (prev.parent === rule) { last.set(key, decl); return; } // in-rule duplicates may be fallbacks
  prev.remove(); removed++;
  last.set(key, decl);
});
let emptied = 0;
root.walkRules(r => { if (!r.nodes.some(n => n.type === 'decl' || n.type === 'rule' || n.type === 'atrule')) { r.remove(); emptied++; } });
root.walkAtRules(a => { if (a.nodes && !a.nodes.some(n => n.type !== 'comment')) a.remove(); });
fs.writeFileSync(out, root.toString());
console.log({ removed, emptied });
