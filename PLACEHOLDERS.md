# 🔴 Placeholders to replace before launch

Everything below is still a stand-in. Replace the value, rebuild (or publish via
Sanity once connected), and it goes live.

Last reconciled against the **2026 South City brochure** (8 pages) — contact
details, project facts and all imagery now come from it. See
[Taken from the brochure](#-taken-from-the-brochure-no-longer-placeholders).

## Contact & identity (in `src/content/site.ts` → `settings`)

| Item | Placeholder used | Where it appears |
|---|---|---|
| Google Map pin | search query `South City Sayedpur Keraniganj Dhaka` | `settings.mapQuery` — replace with the exact plus-code/coords for a precise pin |

⚠️ **The logo artwork carries the wrong company name.** The registered name is
**"South Dhaka Properties & Housing Ltd."** (owner-confirmed; matches the
brochure and the Facebook page `/SouthDhakaHousing.Ltd`), and that is what the
site renders everywhere. But the gold ribbon inside `src/assets/logo.jpg` reads
**"South Dhaka Properties & Developments Ltd."**

Until the artwork is corrected, the site uses **only the emblem** — the gold
arc, SD towers, houses and tree — and never the ribbon wordmark, so the wrong
name is not shown anywhere. Once you have a corrected logo, replace
`src/assets/logo.jpg` and run `npm run icons` to re-export every icon.

## Commerce

| Item | Placeholder used | Where |
|---|---|---|
| Plot prices (3/5/10/20/30/40 Katha) | "Call for price" / "মূল্যের জন্য কল করুন" | Plot tabs — owner's decision, keep until prices are published |
| Booking money | "Call for details" | Plot tabs |

## Media

Hero, master plan, gallery and the Connectivity tab come from the brochure. The
**stock photos** listed below are generic licensed imagery, not South City — swap
them for real project / Keraniganj photography when it exists:

| File | Shows |
|---|---|
| `src/assets/img/landmark-education.webp` | a school campus |
| `src/assets/img/landmark-health.webp` | a doctor's consultation |
| `src/assets/img/landmark-daily.webp` | a super shop |
| `src/assets/img/amenities-bg.webp` | aerial of a planned neighbourhood (Amenities section backdrop) |
| `src/assets/img/icons/*.webp` (18) | thumbnail per amenity / trust badge |

Source: **Pexels** (free for commercial use, no attribution required). Icon files
are 256×256 centre crops; replacing one only means dropping in a square image with
the same file name — the key matches the `icon` field in `src/content/site.ts`, and
a missing file silently falls back to the inline SVG in `src/components/Icon.astro`.

One nice-to-have: `src/assets/logo.jpg` is a 1402×1122 raster. A **vector logo
(AI / SVG / EPS)** would give crisper icons at 512 px and above.

## Config

| Item | Placeholder | Where |
|---|---|---|
| Sanity project ID | `YOUR_SANITY_PROJECT_ID` / empty env | `sanity/sanity.config.ts`, `.env.example` |
| GA4 / Meta Pixel IDs | commented-out block | `src/layouts/BaseLayout.astro` `<head>` |

## Content notes (verify with the owner)

- Plot **dimensions** (36×60 ft etc.) are illustrative approximations derived
  from the Katha areas — confirm real plot dimensions.
- The brochure states plot sizes **two different ways**: the master-plan page
  (০৬) lists 3/5/10/20/30 Katha with zone names, while the Chairman's message
  (০২) and investment page (০৭) list 3/5/10/20/40. The owner confirmed **all
  six** are offered, so the site lists 3 · 5 · 10 · 20 · 30 · 40 Katha. Worth
  fixing in the next brochure reprint.
- Neighborhood tab items marked "Planned within South City" / "Within 6 km" are
  conservative phrasings — refine with real named schools, hospitals, bazaars
  when available.
- Bangla copy should be proof-read by a native reader before launch (spec §13).

---

## ✅ Taken from the brochure (no longer placeholders)

| Item | Value |
|---|---|
| Phone / WhatsApp | `+8801886175263` (display `01886-175263`) |
| Email | `info@southdhaka.com` |
| Office address | Rahman Mansion (4th Floor), 161 Motijheel C/A, Dhaka-1000 |
| Production domain | `https://www.southdhaka.com` (`astro.config.mjs`, `public/robots.txt`) |
| Tagline | "Where Your Dreams Find Their Address" / "যেখানে আপনার স্বপ্নেরা তার ঠিকানা খুঁজে পায়" |
| Project size | ~600 Bigha (was ~500) |
| Road widths | 25 / 30 / 40 / 60 ft (30 ft was missing) |
| Trust badge | "Own Purchased Land" replaced the unsourced "40% Land Already Acquired" |
| Brochure PDF | `src/assets/brochure.pdf` — the real 8-page brochure |
| Hero / master plan / gallery ×9 / Connectivity tab / OG image | cropped from the brochure pages into `src/assets/img/` and `public/og-image.png` (Education / Health / Daily Needs tabs are now stock photos — see Media above) |
| Facebook | `https://www.facebook.com/SouthDhakaHousing.Ltd` (YouTube & LinkedIn intentionally omitted) |
| Web3Forms key | live — the contact form delivers |
| Favicons / app icons / header & footer mark | exported from the real logo emblem via `npm run icons` |

⚠️ `npm run assets` regenerates **placeholder** art and would overwrite the real
brochure imagery above. It now refuses to run without `npm run assets -- --force`.
