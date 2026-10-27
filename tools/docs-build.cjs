// Turns the design's pg-content.js into resources/docs/pages.json, filling the company details we know.
const fs = require('fs');
const src = fs.readFileSync('tools/pg-content.src.js', 'utf8');
const window = {};
new Function('window', src)(window);

const fill = {
  '[Registered business address]': '200 Orient Ave STE 2B, Jersey City, NJ 07305',
  '[support email]': 'info@peytonghalib.com', '[legal email]': 'info@peytonghalib.com', '[privacy email]': 'info@peytonghalib.com',
  '[dmca email]': 'info@peytonghalib.com', '[accessibility email]': 'info@peytonghalib.com',
  '[support phone]': '+1 (929) 469-9864',
  '[Confirm how card-processing costs are handled before publishing.]': 'Card-processing fees (currently 2.9% + $0.30 per order) are deducted from each order\'s payout.',
};
// Programs that are not built yet (seller referrals are built), and sample copy (job openings) the business has not confirmed.
const skip = new Set(['careers', 'press']);
const sub = (s) => Object.entries(fill).reduce((t, [k, v]) => t.split(k).join(v), s);

const out = {};
for (const [slug, d] of Object.entries(window.PG_DOCS)) {
  if (skip.has(slug)) continue;
  out[slug] = { ...d, title: sub(d.title), intro: sub(d.intro), sections: d.sections.map((x) => ({ h: sub(x.h), p: sub(x.p) })) };
}
fs.writeFileSync('resources/docs/pages.json', JSON.stringify(out, null, 1));
console.log(Object.keys(out).join(', '));
