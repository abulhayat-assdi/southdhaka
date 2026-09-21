# South Dhaka WordPress Theme

Traditional PHP WordPress theme converted from the Astro, Tailwind CSS, and Sanity CMS South City project.

## Install

1. Copy the `southdhaka` folder into `wp-content/themes/`.
2. Activate **South City** from WordPress Admin > Appearance > Themes.
3. Install and activate **Advanced Custom Fields**. ACF Pro is recommended because the theme uses an ACF options page for global settings.
4. Go to Settings > Permalinks and click **Save Changes** once if `/bn/` does not load after activation.
5. Assign menus under Appearance > Menus:
   - Primary Menu
   - Footer Menu
   - Language Switcher Menu

## Content Model

Global content is managed from **South City Settings**:

- Company legal name
- Phone, WhatsApp, email, office address
- Social links
- Brochure PDF
- Google Maps query
- Default WhatsApp messages

Homepage content is managed on the page assigned as the static front page:

- Hero headline, subline, image, chips
- Overview paragraph and counters
- Master plan image and hotspots
- Location distances and boundaries

Repeatable Sanity documents are now WordPress custom post types:

- `southcity_plot` for plot sizes and pricing
- `southcity_badge` for trust badges
- `southcity_fact` for project facts
- `southcity_amenity` for amenities
- `southcity_landmark` for neighborhood tabs
- `southcity_gallery` for gallery images

## Assets

The theme loads:

- CSS from `assets/css/main.css`
- JS from `assets/js/main.js`
- Fallback images from `assets/img/`
- Brochure PDF is uploaded via the Media Library and set on the Brochure PDF field in South City Settings

## Notes for developers

- **Seed content runs once.** `inc/default-content.php` fills a brand-new site on first activation only. It never runs again on a site that already has content, so it cannot overwrite the client's edits. After handover, change content in WordPress, not in that file.
- **Custom post types are not public** (no `/plots/`, `/gallery/` ... pages). Content is shown only through the homepage. They are edited from wp-admin or the /manage dashboard.
- **Bangla pages** live under `/bn/` (`/bn/` and `/bn/{page-slug}/`). The language switcher on inner pages points to the same page in the other language.
- **CSS.** `assets/css/main.css` is compiled Tailwind output and the Tailwind source is not in this repository, so it cannot be rebuilt from here. Add any new utility classes by hand to `assets/css/wp.css` (see the block at the bottom of that file).
- **Enquiry emails** use `wp_mail()`. On shared hosting install an SMTP plugin (e.g. WP Mail SMTP) so messages reach the inbox.
