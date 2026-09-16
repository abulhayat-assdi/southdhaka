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

Run the Tailwind build again after changing PHP template classes:

```bash
npx tailwindcss -i ./src/styles/global.css -o ./southdhaka/assets/css/main.css --content './southdhaka/**/*.php' './southdhaka/assets/js/**/*.js' './src/**/*.{astro,ts,js}'
```
