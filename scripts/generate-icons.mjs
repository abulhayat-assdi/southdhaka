/**
 * Exports the site's favicons, app icons and the header mark from the real
 * South City logo at src/assets/logo.jpg.
 *
 * Only the EMBLEM is used (gold arc + SD towers + houses + tree). The gold
 * ribbon underneath carries the wordmark "South Dhaka Properties &
 * Developments Ltd.", which is not the company's registered name — the site
 * renders the correct name ("… & Housing Ltd.") as text instead. Once the logo
 * artwork is corrected, drop the new file in and re-run:
 *
 *   npm run icons
 *
 * If the new artwork has different proportions, re-measure EMBLEM below.
 */
import sharp from 'sharp';
import { mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const SOURCE = join(root, 'src', 'assets', 'logo.jpg');
const pub = join(root, 'public');
const img = join(root, 'src', 'assets', 'img');
mkdirSync(pub, { recursive: true });
mkdirSync(img, { recursive: true });

/** Emblem box inside the 1402×1122 logo lockup — excludes the ribbon wordmark. */
const EMBLEM = { left: 280, top: 15, width: 850, height: 725 };

/** Square, white-backed emblem at the requested size. */
const square = (size) =>
  sharp(SOURCE)
    .extract(EMBLEM)
    .resize({ width: size, height: size, fit: 'contain', background: '#ffffff', kernel: 'lanczos3' })
    .flatten({ background: '#ffffff' });

const icons = [
  ['favicon-32.png', 32],
  ['favicon-48.png', 48],
  ['apple-touch-icon.png', 180],
  ['icon-512.png', 512],
];

for (const [file, size] of icons) {
  // small sizes lose the emblem's fine detail — sharpen harder as they shrink
  const info = await square(size)
    .sharpen({ sigma: size <= 48 ? 1.1 : 0.6 })
    .png({ compressionLevel: 9 })
    .toFile(join(pub, file));
  console.log(`public/${file.padEnd(22)} ${info.width}x${info.height}  ${(info.size / 1024).toFixed(1)} KB`);
}

// header mark — rendered at 36px, so 144px covers 4× device pixel ratios
const mark = await square(144)
  .sharpen({ sigma: 0.6 })
  .webp({ quality: 90, effort: 6 })
  .toFile(join(img, 'logo-mark.webp'));
console.log(`src/assets/img/logo-mark.webp  ${mark.width}x${mark.height}  ${(mark.size / 1024).toFixed(1)} KB`);

console.log('\n✅ Icons exported from', SOURCE);
