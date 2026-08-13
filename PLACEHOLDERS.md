# 🔴 Placeholders to replace before launch

Everything below is still a stand-in. Replace the value, rebuild (or publish via
Sanity once connected), and it goes live.

Last reconciled against the **2026 South City brochure** (8 pages) — contact
details, project facts and all imagery now come from it. See
[Taken from the brochure](#-taken-from-the-brochure-no-longer-placeholders).

## Contact & identity (in `src/content/site.ts` → `settings`)

| Item | Placeholder used | Where it appears |
|---|---|---|
| Social links | Bare `facebook.com` / `youtube.com` / `linkedin.com` | Footer icons — the brochure's QR code links to the real Facebook/WhatsApp, decode it and paste the URLs |
| Google Map pin | search query `South City Sayedpur Keraniganj Dhaka` | `settings.mapQuery` — replace with the exact plus-code/coords for a precise pin |

## Commerce

| Item | Placeholder used | Where |
|---|---|---|
| Plot prices (3/5/10/20/30/40 Katha) | "Call for price" / "মূল্যের জন্য কল করুন" | Plot tabs |
| Booking money | "Call for details" | Plot tabs |
| Web3Forms access key | `YOUR_WEB3FORMS_ACCESS_KEY` | `src/content/site.ts` (or set env `PUBLIC_WEB3FORMS_KEY`) — form will NOT deliver email until replaced |

## Media

| Item | File | Note |
|---|---|---|
| Logo / favicon | `public/favicon.svg`, `favicon-32.png`, `favicon-48.png`, `apple-touch-icon.png`, `icon-512.png` | Still the generated "SD" emblem. The brochure only carries the logo as flattened raster — ask the owner for the **vector logo** (AI/SVG/EPS) and export these from it |

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
| Hero / master plan / gallery ×8 / landmark ×4 / OG image | cropped from the brochure pages into `src/assets/img/` and `public/og-image.png` |

⚠️ `npm run assets` regenerates **placeholder** art and would overwrite the real
brochure imagery above. It now refuses to run without `npm run assets -- --force`.
