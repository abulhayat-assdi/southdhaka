<?php
/**
 * South City theme setup.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

define('SOUTH_CITY_THEME_VERSION', '1.1.2');
define('SOUTH_CITY_THEME_DIR', get_template_directory());
define('SOUTH_CITY_THEME_URI', get_template_directory_uri());

// Bump this when a rewrite rule is added/changed so it is flushed once automatically.
define('SOUTH_CITY_REWRITE_VERSION', 2);

// South City project location (Google Maps plus code J8WC+GXG, Sayedpur, South Keraniganj).
define('SOUTH_CITY_MAP_LAT', '23.6463');
define('SOUTH_CITY_MAP_LNG', '90.3224');

/**
 * Register theme supports, menus, and editor defaults.
 */
function south_city_setup(): void
{
    load_theme_textdomain('south-city', SOUTH_CITY_THEME_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 96,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'navigation-widgets',
        'script',
        'search-form',
        'style',
    ]);

    register_nav_menus([
        'primary'  => __('Primary Menu', 'south-city'),
        'footer'   => __('Footer Menu', 'south-city'),
        'language' => __('Language Switcher Menu', 'south-city'),
    ]);
}
add_action('after_setup_theme', 'south_city_setup');

/**
 * Register language rewrite support for /bn/ (used when pretty permalinks
 * and the server's rewrite rules are working correctly).
 */
function south_city_register_language_routes(): void
{
    $front_page_id = (int) get_option('page_on_front');
    $target        = $front_page_id > 0
        ? 'index.php?page_id=' . $front_page_id . '&south_city_lang=bn'
        : 'index.php?south_city_lang=bn';

    add_rewrite_rule('^bn/?$', $target, 'top');
    // /bn/about-us/ etc.: the Bangla version of any top-level page.
    add_rewrite_rule('^bn/([^/]+)/?$', 'index.php?pagename=$matches[1]&south_city_lang=bn', 'top');
}
add_action('init', 'south_city_register_language_routes');

/**
 * Flush rewrite rules once whenever SOUTH_CITY_REWRITE_VERSION changes, so a
 * theme update that adds/removes URLs works without visiting Settings > Permalinks.
 */
function south_city_maybe_flush_rewrite_rules(): void
{
    if ((int) get_option('south_city_rewrite_version', 0) >= SOUTH_CITY_REWRITE_VERSION) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('south_city_rewrite_version', SOUTH_CITY_REWRITE_VERSION);
}
add_action('init', 'south_city_maybe_flush_rewrite_rules', 100);

/**
 * Force /bn/ to load the real front page directly from the raw request
 * path, bypassing WordPress's cached rewrite-rules matching entirely.
 * This is a safety net for hosts where flush_rewrite_rules() doesn't take
 * effect (stale rewrite cache, read-only .htaccess, etc.) and the request
 * would otherwise fall through and get redirected back to "/".
 */
function south_city_force_bn_homepage_query(WP $wp): void
{
    $request_path = trim((string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');
    $home_path    = trim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');

    if ($home_path !== '' && str_starts_with($request_path, $home_path)) {
        $request_path = trim(substr($request_path, strlen($home_path)), '/');
    }

    // /bn/{page-slug}/ — the Bangla version of an ordinary page (e.g. About Us).
    if (preg_match('#^bn/([^/]+)$#', $request_path, $matches)) {
        $slug = sanitize_title($matches[1]);

        if ($slug !== '' && get_page_by_path($slug, OBJECT, 'page') instanceof WP_Post) {
            $wp->query_vars = ['pagename' => $slug, 'south_city_lang' => 'bn'];
        }

        return;
    }

    if ($request_path !== 'bn') {
        return;
    }

    $front_page_id = (int) get_option('page_on_front');

    $wp->query_vars = ['south_city_lang' => 'bn'];

    if ($front_page_id > 0) {
        $wp->query_vars['page_id'] = $front_page_id;
    }
}
add_action('parse_request', 'south_city_force_bn_homepage_query');

/**
 * Without this, WordPress's own canonical-redirect logic sees /bn/ resolving
 * to the same page object as the front page and 301s back to "/" to avoid
 * "duplicate content" — which is exactly the bug this route needs to avoid.
 */
function south_city_skip_canonical_redirect_for_bn(string|false $redirect_url): string|false
{
    if (get_query_var('south_city_lang') === 'bn') {
        return false;
    }

    return $redirect_url;
}
add_filter('redirect_canonical', 'south_city_skip_canonical_redirect_for_bn');

/**
 * Allow the theme to read custom language query vars.
 */
function south_city_query_vars(array $query_vars): array
{
    $query_vars[] = 'south_city_lang';

    return $query_vars;
}
add_filter('query_vars', 'south_city_query_vars');

/**
 * Flush rewrite rules once when the theme is activated.
 */
function south_city_after_switch_theme(): void
{
    if (function_exists('south_city_register_post_types')) {
        south_city_register_post_types();
    }

    if (function_exists('south_city_register_taxonomies')) {
        south_city_register_taxonomies();
    }

    south_city_register_language_routes();

    if (function_exists('south_city_seed_default_content')) {
        south_city_seed_default_content();
    }

    flush_rewrite_rules();
}
add_action('after_switch_theme', 'south_city_after_switch_theme');

/**
 * Create the "About Us" page (using page-about.php) once, without needing a
 * theme deactivate/reactivate cycle on sites where the theme is already active.
 */
function south_city_maybe_create_about_page(): void
{
    if (get_option('south_city_about_page_created')) {
        return;
    }

    if (south_city_about_page_id() > 0) {
        update_option('south_city_about_page_created', '1');
        return;
    }

    $page_id = wp_insert_post([
        'post_title'   => 'About Us',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ]);

    if ($page_id && ! is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'page-about.php');
    }

    update_option('south_city_about_page_created', '1');
}
add_action('init', 'south_city_maybe_create_about_page', 20);

/**
 * Keep South City CPT archives sorted by admin sort order.
 */
function south_city_order_archives(WP_Query $query): void
{
    if (is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive()) {
        return;
    }

    $ordered_post_types = [
        'southcity_plot',
        'southcity_badge',
        'southcity_fact',
        'southcity_amenity',
        'southcity_landmark',
        'southcity_gallery',
    ];

    $post_type = $query->get('post_type');
    $post_type = is_array($post_type) ? reset($post_type) : $post_type;

    if (! in_array($post_type, $ordered_post_types, true)) {
        return;
    }

    $query->set('meta_key', 'order_rank');
    $query->set('orderby', [
        'meta_value_num' => 'ASC',
        'menu_order'     => 'ASC',
        'title'          => 'ASC',
    ]);
    $query->set('order', 'ASC');
}
add_action('pre_get_posts', 'south_city_order_archives');

/**
 * Enqueue compiled theme assets.
 */
function south_city_enqueue_assets(): void
{
    $css_path = SOUTH_CITY_THEME_DIR . '/assets/css/main.css';
    $wp_css_path = SOUTH_CITY_THEME_DIR . '/assets/css/wp.css';
    $js_path  = SOUTH_CITY_THEME_DIR . '/assets/js/main.js';

    wp_enqueue_style(
        'south-city-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&family=Hind+Siliguri:wght@500;600;700&family=Noto+Sans+Bengali:wght@400;500;600&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'south-city-main',
        SOUTH_CITY_THEME_URI . '/assets/css/main.css',
        ['south-city-fonts'],
        file_exists($css_path) ? (string) filemtime($css_path) : SOUTH_CITY_THEME_VERSION
    );

    wp_enqueue_style(
        'south-city-wp',
        SOUTH_CITY_THEME_URI . '/assets/css/wp.css',
        ['south-city-main'],
        file_exists($wp_css_path) ? (string) filemtime($wp_css_path) : SOUTH_CITY_THEME_VERSION
    );

    wp_enqueue_script(
        'south-city-main',
        SOUTH_CITY_THEME_URI . '/assets/js/main.js',
        [],
        file_exists($js_path) ? (string) filemtime($js_path) : SOUTH_CITY_THEME_VERSION,
        true
    );

    if (is_front_page() && south_city_turnstile_site_key() !== '') {
        wp_enqueue_script(
            'south-city-turnstile',
            'https://challenges.cloudflare.com/turnstile/v0/api.js',
            [],
            null,
            ['strategy' => 'async', 'in_footer' => true]
        );
    }
}
add_action('wp_enqueue_scripts', 'south_city_enqueue_assets');

/**
 * Show a helpful admin notice when ACF is not active.
 */
function south_city_acf_admin_notice(): void
{
    if (function_exists('acf_add_local_field_group')) {
        return;
    }

    $screen = get_current_screen();

    if (! $screen || ! current_user_can('activate_plugins')) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    echo esc_html__('South City theme requires Advanced Custom Fields to manage the converted Sanity content from WordPress Admin.', 'south-city');
    echo '</p></div>';
}
add_action('admin_notices', 'south_city_acf_admin_notice');

/**
 * Return the current front-end language.
 */
function south_city_current_language(): string
{
    $queried_language = get_query_var('south_city_lang');

    if (in_array($queried_language, ['en', 'bn'], true)) {
        return $queried_language;
    }

    $locale = determine_locale();

    return str_starts_with($locale, 'bn') ? 'bn' : 'en';
}

/**
 * URL of a page in the given language (English: normal permalink, Bangla: /bn/{slug}/).
 */
function south_city_localized_url(int $post_id, ?string $language = null): string
{
    $language = $language ?: south_city_current_language();

    if ($language !== 'bn') {
        return (string) get_permalink($post_id);
    }

    if ($post_id === (int) get_option('page_on_front')) {
        return home_url('/bn/');
    }

    $uri = get_page_uri($post_id);

    // /bn/ routing only covers top-level pages; nested pages keep their normal URL.
    if ($uri === '' || str_contains($uri, '/')) {
        return (string) get_permalink($post_id);
    }

    return home_url('/bn/' . $uri . '/');
}

/**
 * URL the language switcher should point to: the same page in the other
 * language, or the other-language homepage on non-page screens.
 */
function south_city_language_switch_url(?string $language = null): string
{
    $language = $language ?: south_city_current_language();
    $target   = $language === 'bn' ? 'en' : 'bn';

    if (is_page() && ! is_front_page()) {
        $page_id = (int) get_queried_object_id();

        if ($page_id > 0) {
            return south_city_localized_url($page_id, $target);
        }
    }

    return $target === 'bn' ? home_url('/bn/') : home_url('/');
}

/**
 * Read a bilingual ACF field using the active language.
 */
function south_city_get_locale_field(string $base_name, mixed $post_id = false, ?string $language = null): string
{
    $language         = $language ?: south_city_current_language();
    $resolved_post_id = $post_id ?: get_the_ID();
    $field_name       = $base_name . '_' . $language;
    $value            = function_exists('get_field') ? get_field($field_name, $post_id) : '';

    if ($value !== null && $value !== false && $value !== '') {
        return (string) $value;
    }

    if ($resolved_post_id) {
        $meta_value = get_post_meta((int) $resolved_post_id, $field_name, true);

        if ($meta_value !== null && $meta_value !== false && $meta_value !== '') {
            return (string) $meta_value;
        }
    }

    $fallback_language = $language === 'en' ? 'bn' : 'en';
    $fallback_name     = $base_name . '_' . $fallback_language;
    $fallback_value    = function_exists('get_field') ? get_field($fallback_name, $post_id) : '';

    if (($fallback_value === null || $fallback_value === false || $fallback_value === '') && $resolved_post_id) {
        $fallback_value = get_post_meta((int) $resolved_post_id, $fallback_name, true);
    }

    return $fallback_value ? (string) $fallback_value : '';
}

/**
 * Read a global option field safely.
 */
function south_city_get_option(string $field_name, mixed $default = ''): mixed
{
    $value = function_exists('get_field') ? get_field($field_name, 'option') : null;

    if ($value === null || $value === false || $value === '') {
        $value = get_option('options_' . $field_name, $default);
    }

    return ($value !== null && $value !== false && $value !== '') ? $value : $default;
}

/**
 * Read an ACF field and fall back to raw post meta.
 */
function south_city_get_field_or_meta(string $field_name, mixed $post_id = false, mixed $default = ''): mixed
{
    $resolved_post_id = $post_id ?: get_the_ID();

    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);

        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
    }

    if (! $resolved_post_id) {
        return $default;
    }

    $meta_value = get_post_meta((int) $resolved_post_id, $field_name, true);

    return ($meta_value !== null && $meta_value !== false && $meta_value !== '') ? $meta_value : $default;
}

/**
 * Read a bilingual global option field using the active language.
 */
function south_city_get_locale_option(string $base_name, ?string $language = null): string
{
    $language = $language ?: south_city_current_language();
    $field_name = $base_name . '_' . $language;
    $value = function_exists('get_field') ? get_field($field_name, 'option') : null;

    if ($value === null || $value === false || $value === '') {
        $value = get_option('options_' . $field_name, '');
    }

    if ($value !== null && $value !== false && $value !== '') {
        return (string) $value;
    }

    $fallback_language = $language === 'en' ? 'bn' : 'en';
    $fallback_name     = $base_name . '_' . $fallback_language;
    $fallback_value    = function_exists('get_field') ? get_field($fallback_name, 'option') : null;

    if ($fallback_value === null || $fallback_value === false || $fallback_value === '') {
        $fallback_value = get_option('options_' . $fallback_name, '');
    }

    return $fallback_value ? (string) $fallback_value : '';
}

/**
 * Return translated UI copy used by shared templates.
 */
function south_city_translate(string $key, ?string $language = null): string
{
    $language = $language ?: south_city_current_language();
    $strings  = [
        'skip_to_content' => [
            'en' => 'Skip to content',
            'bn' => 'মূল কনটেন্টে যান',
        ],
        'overview' => [
            'en' => 'Overview',
            'bn' => 'পরিচিতি',
        ],
        'master_plan' => [
            'en' => 'Master Plan',
            'bn' => 'মাস্টার প্ল্যান',
        ],
        'plots' => [
            'en' => 'Plots',
            'bn' => 'প্লট',
        ],
        'location' => [
            'en' => 'Location',
            'bn' => 'লোকেশন',
        ],
        'amenities' => [
            'en' => 'Amenities',
            'bn' => 'সুযোগ-সুবিধা',
        ],
        'about_us' => [
            'en' => 'About Us',
            'bn' => 'আমাদের সম্পর্কে',
        ],
        'contact' => [
            'en' => 'Contact',
            'bn' => 'যোগাযোগ',
        ],
        'call_now' => [
            'en' => 'Call Now',
            'bn' => 'কল করুন',
        ],
        'open_menu' => [
            'en' => 'Open menu',
            'bn' => 'মেনু খুলুন',
        ],
        'language_label' => [
            'en' => 'ভাষা: বাংলা',
            'bn' => 'Language: English',
        ],
        'language_short' => [
            'en' => 'বাং',
            'bn' => 'EN',
        ],
        'footer_tagline' => [
            'en' => 'Building Landmark, Creating Legacy.',
            'bn' => 'Building Landmark, Creating Legacy.',
        ],
        'hero_eyebrow' => [
            'en' => 'Sayedpur · South Keraniganj · Dhaka',
            'bn' => 'সায়েদপুর · দক্ষিণ কেরানীগঞ্জ · ঢাকা',
        ],
        'whatsapp_us' => [
            'en' => 'WhatsApp Us',
            'bn' => 'হোয়াটসঅ্যাপ করুন',
        ],
        'get_plot_details' => [
            'en' => 'Get Plot Details',
            'bn' => 'প্লটের বিস্তারিত জানুন',
        ],
        'overview_eyebrow' => [
            'en' => 'Overview',
            'bn' => 'পরিচিতি',
        ],
        'overview_title' => [
            'en' => 'A Planned Township by the Dhaleshwari',
            'bn' => 'ধলেশ্বরীর তীরে পরিকল্পিত টাউনশিপ',
        ],
        'trust_eyebrow' => [
            'en' => 'Why trust South City',
            'bn' => 'কেন সাউথ সিটিতে আস্থা রাখবেন',
        ],
        'trust_title' => [
            'en' => 'Buy With Confidence',
            'bn' => 'নিশ্চিন্তে বিনিয়োগ করুন',
        ],
        'facts_eyebrow' => [
            'en' => 'The project in numbers',
            'bn' => 'এক নজরে প্রকল্প',
        ],
        'facts_title' => [
            'en' => 'Project at a Glance',
            'bn' => 'প্রকল্পের মূল তথ্য',
        ],
        'plan_eyebrow' => [
            'en' => 'Master plan',
            'bn' => 'মাস্টার প্ল্যান',
        ],
        'plan_title' => [
            'en' => 'Four Sectors, One Complete City',
            'bn' => 'চার সেক্টরে একটি পূর্ণাঙ্গ শহর',
        ],
        'plots_eyebrow' => [
            'en' => 'Plot sizes & pricing',
            'bn' => 'প্লট সাইজ ও মূল্য',
        ],
        'plots_title' => [
            'en' => 'Choose Your Plot Size',
            'bn' => 'আপনার প্লট সাইজ বেছে নিন',
        ],
        'plots_note' => [
            'en' => '1 Bigha = 20 Katha',
            'bn' => '১ বিঘা = ২০ কাঠা',
        ],
        'location_eyebrow' => [
            'en' => 'Location & connectivity',
            'bn' => 'লোকেশন ও যোগাযোগ',
        ],
        'location_title' => [
            'en' => 'Minutes From the Expressway',
            'bn' => 'এক্সপ্রেসওয়ে থেকে মাত্র কয়েক মিনিট',
        ],
        'landmarks_eyebrow' => [
            'en' => 'Neighborhood',
            'bn' => 'আশপাশের এলাকা',
        ],
        'landmarks_title' => [
            'en' => 'Everything You Need, Nearby',
            'bn' => 'প্রয়োজনীয় সবকিছু, হাতের কাছে',
        ],
        'amenities_eyebrow' => [
            'en' => 'Amenities & facilities',
            'bn' => 'সুযোগ-সুবিধা',
        ],
        'amenities_title' => [
            'en' => 'Designed for Family Living',
            'bn' => 'পরিবারের জন্য পরিকল্পিত জীবন',
        ],
        'gallery_eyebrow' => [
            'en' => 'Gallery',
            'bn' => 'গ্যালারি',
        ],
        'gallery_title' => [
            'en' => 'Master Plan & Project Renders',
            'bn' => 'মাস্টার প্ল্যান ও প্রকল্পের রেন্ডার',
        ],
        'contact_eyebrow' => [
            'en' => 'Get plot details',
            'bn' => 'প্লটের বিস্তারিত',
        ],
        'contact_title' => [
            'en' => 'Talk to Our Sales Team',
            'bn' => 'আমাদের সেলস টিমের সাথে কথা বলুন',
        ],
        'contact_subtitle' => [
            'en' => 'Leave your details — we will call you back with plot availability and pricing.',
            'bn' => 'আপনার তথ্য দিন — প্লটের প্রাপ্যতা ও মূল্যসহ আমরা আপনাকে কল করব।',
        ],
        'download_brochure' => [
            'en' => 'Download Brochure (PDF)',
            'bn' => 'ব্রোশিওর ডাউনলোড করুন (PDF)',
        ],
        'area' => [
            'en' => 'Area',
            'bn' => 'আয়তন',
        ],
        'price' => [
            'en' => 'Price',
            'bn' => 'মূল্য',
        ],
        'booking_money' => [
            'en' => 'Booking money',
            'bn' => 'বুকিং মানি',
        ],
        'installments' => [
            'en' => 'Installments',
            'bn' => 'কিস্তি সুবিধা',
        ],
        'reserve_plot' => [
            'en' => 'Reserve this plot',
            'bn' => 'এই প্লটটি রিজার্ভ করুন',
        ],
        'call_for_price' => [
            'en' => 'Call for price',
            'bn' => 'মূল্যের জন্য কল করুন',
        ],
        'show_map' => [
            'en' => 'Show map',
            'bn' => 'ম্যাপ দেখুন',
        ],
        'get_directions' => [
            'en' => 'Get directions',
            'bn' => 'ডিরেকশন দেখুন',
        ],
        'distances_title' => [
            'en' => 'Distances that matter',
            'bn' => 'গুরুত্বপূর্ণ দূরত্বসমূহ',
        ],
        'boundaries' => [
            'en' => 'Project boundaries',
            'bn' => 'প্রকল্পের সীমানা',
        ],
        'form_name' => [
            'en' => 'Your name',
            'bn' => 'আপনার নাম',
        ],
        'form_phone' => [
            'en' => 'Phone number (BD)',
            'bn' => 'ফোন নম্বর',
        ],
        'form_phone_hint' => [
            'en' => 'e.g. 01XXXXXXXXX',
            'bn' => 'যেমন: 01XXXXXXXXX',
        ],
        'form_plot_size' => [
            'en' => 'Preferred plot size',
            'bn' => 'পছন্দের প্লট সাইজ',
        ],
        'form_plot_any' => [
            'en' => 'Not sure yet',
            'bn' => 'এখনও ঠিক করিনি',
        ],
        'form_message' => [
            'en' => 'Message (optional)',
            'bn' => 'বার্তা (ঐচ্ছিক)',
        ],
        'form_submit' => [
            'en' => 'Request a Call Back',
            'bn' => 'কল ব্যাক চাই',
        ],
        'form_whatsapp' => [
            'en' => 'Send on WhatsApp instead',
            'bn' => 'হোয়াটসঅ্যাপে পাঠান',
        ],
        'sticky_call' => [
            'en' => 'Call',
            'bn' => 'কল করুন',
        ],
        'sticky_whatsapp' => [
            'en' => 'WhatsApp',
            'bn' => 'হোয়াটসঅ্যাপ',
        ],
        'quick_contact' => [
            'en' => 'Quick contact',
            'bn' => 'দ্রুত যোগাযোগ',
        ],
        'quick_links' => [
            'en' => 'Quick Links',
            'bn' => 'কুইক লিংক',
        ],
        'corporate_office' => [
            'en' => 'Corporate Office',
            'bn' => 'কর্পোরেট অফিস',
        ],
        'copyright' => [
            'en' => '© 2026 South City · South Dhaka Properties & Housing Ltd. All rights reserved.',
            'bn' => '© ২০২৬ সাউথ সিটি · সাউথ ঢাকা প্রপার্টিজ অ্যান্ড হাউজিং লিমিটেড। সর্বস্বত্ব সংরক্ষিত।',
        ],
        'legacy_strip' => [
            'en' => 'Building Landmark, Creating Legacy',
            'bn' => 'Building Landmark, Creating Legacy',
        ],
        'website' => [
            'en' => 'Website',
            'bn' => 'ওয়েবসাইট',
        ],
        'chairman_eyebrow' => [
            'en' => 'Commitment is our greatest strength',
            'bn' => 'প্রতিশ্রুতি — আমাদের সর্বশ্রেষ্ঠ শক্তি',
        ],
        'chairman_title' => [
            'en' => "Chairman's Message",
            'bn' => 'চেয়ারম্যানের বার্তা',
        ],
        'chairman_role' => [
            'en' => 'Chairman',
            'bn' => 'চেয়ারম্যান',
        ],
        'summary_title' => [
            'en' => 'Project at a Glance',
            'bn' => 'প্রকল্পের সংক্ষিপ্ত বিবরণ',
        ],
        'summary_footnote' => [
            'en' => 'We do not just sell plots — we build relationships, from one generation to the next.',
            'bn' => 'আমরা শুধু প্লট বিক্রি করি না, আমরা সম্পর্ক গড়ে তুলি — প্রজন্ম থেকে প্রজন্মে।',
        ],
        'md_eyebrow' => [
            'en' => 'Commitment is our greatest strength',
            'bn' => 'অঙ্গীকারই আমাদের সর্বশ্রেষ্ঠ শক্তি',
        ],
        'md_title' => [
            'en' => "Managing Director's Message",
            'bn' => 'ব্যবস্থাপনা পরিচালকের বার্তা',
        ],
        'md_role' => [
            'en' => 'Managing Director',
            'bn' => 'ব্যবস্থাপনা পরিচালক',
        ],
        'profile_eyebrow' => [
            'en' => 'Who we are',
            'bn' => 'আমরা কারা',
        ],
        'profile_title' => [
            'en' => 'Company Profile',
            'bn' => 'কোম্পানির প্রোফাইল',
        ],
        'profile_about' => [
            'en' => 'About Us',
            'bn' => 'আমাদের সম্পর্কে',
        ],
        'profile_vision' => [
            'en' => 'Vision',
            'bn' => 'ভিশন',
        ],
        'profile_mission' => [
            'en' => 'Mission',
            'bn' => 'মিশন',
        ],
        'profile_values' => [
            'en' => 'Core Values',
            'bn' => 'মূল মান',
        ],
        'why_eyebrow' => [
            'en' => 'A sound choice to live, a smart decision to invest',
            'bn' => 'বসবাসের জন্য সঠিক পছন্দ, বিনিয়োগের জন্য বিচক্ষণ সিদ্ধান্ত',
        ],
        'why_title' => [
            'en' => 'Why South City?',
            'bn' => 'সাউথ সিটি কেন?',
        ],
        'investment_eyebrow' => [
            'en' => 'Own today, prosper tomorrow',
            'bn' => 'আজই মালিক হোন, আগামীতে সমৃদ্ধি লাভ করুন',
        ],
        'investment_title' => [
            'en' => 'Intelligent Investment. Secure Future.',
            'bn' => 'বুদ্ধিদীপ্ত বিনিয়োগ। সুরক্ষিত ভবিষ্যৎ।',
        ],
        'process_eyebrow' => [
            'en' => 'Simple, transparent steps',
            'bn' => 'সহজ, স্বচ্ছ ধাপ',
        ],
        'process_title' => [
            'en' => 'Ownership Process',
            'bn' => 'মালিকানা প্রক্রিয়া',
        ],
        'legend_title' => [
            'en' => 'Sector Guide',
            'bn' => 'সেক্টর গাইড',
        ],
        'amenities_group_core' => [
            'en' => 'World-Class Facilities',
            'bn' => 'বিশ্বমানের সুবিধা',
        ],
        'amenities_group_infrastructure' => [
            'en' => 'Modern Infrastructure',
            'bn' => 'আধুনিক অবকাঠামো',
        ],
        'amenities_group_security' => [
            'en' => 'Security & Community',
            'bn' => 'নিরাপত্তা ও কমিউনিটি',
        ],
    ];

    return $strings[$key][$language] ?? $strings[$key]['en'] ?? $key;
}

/**
 * Return the fixed section links used when no WordPress menu is assigned.
 */
function south_city_default_nav_items(?string $language = null): array
{
    $language = $language ?: south_city_current_language();
    $items    = [
        [
            'href' => '#overview',
            'label' => south_city_translate('overview', $language),
            'spy' => 'overview',
        ],
        [
            'href' => '#master-plan',
            'label' => south_city_translate('master_plan', $language),
            'spy' => 'master-plan',
        ],
        [
            'href' => '#plots',
            'label' => south_city_translate('plots', $language),
            'spy' => 'plots',
        ],
        [
            'href' => '#location',
            'label' => south_city_translate('location', $language),
            'spy' => 'location',
        ],
        [
            'href' => '#amenities',
            'label' => south_city_translate('amenities', $language),
            'spy' => 'amenities',
        ],
    ];

    $about_page_id = south_city_about_page_id();

    if ($about_page_id > 0) {
        $items[] = [
            'href' => south_city_localized_url($about_page_id, $language),
            'label' => south_city_translate('about_us', $language),
            'spy' => '',
        ];
    }

    $items[] = [
        'href' => '#contact',
        'label' => south_city_translate('contact', $language),
        'spy' => 'contact',
    ];

    if (! is_front_page()) {
        $home_url = trailingslashit($language === 'bn' ? home_url('/bn/') : home_url('/'));

        foreach ($items as &$item) {
            if (str_starts_with($item['href'], '#')) {
                $item['href'] = $home_url . $item['href'];
            }
        }
        unset($item);
    }

    return $items;
}

/**
 * Find the published page using the About Us template, if one exists.
 */
function south_city_about_page_id(): int
{
    static $page_id = null;

    if ($page_id !== null) {
        return $page_id;
    }

    $pages = get_posts([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-about.php',
        'fields'         => 'ids',
    ]);

    $page_id = ! empty($pages) ? (int) $pages[0] : 0;

    return $page_id;
}

/**
 * Build the WhatsApp deep link from editable settings.
 */
function south_city_whatsapp_url(?string $language = null): string
{
    $language = $language ?: south_city_current_language();
    $number   = preg_replace('/\D+/', '', (string) south_city_get_option('whatsapp', '8801886175263'));
    $message  = south_city_get_locale_option('whatsapp_message', $language);

    if ($message === '') {
        $message = $language === 'bn'
            ? 'আসসালামু আলাইকুম, আমি সাউথ সিটির প্লট সম্পর্কে জানতে আগ্রহী।'
            : "Assalamu Alaikum, I'm interested in South City plots.";
    }

    return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
}

/**
 * Return a usable URL from an ACF image/file field.
 */
function south_city_asset_url(mixed $asset, string $size = 'large'): string
{
    if (is_array($asset)) {
        if (isset($asset['sizes'][$size])) {
            return (string) $asset['sizes'][$size];
        }

        if (isset($asset['url'])) {
            return (string) $asset['url'];
        }
    }

    if (is_numeric($asset)) {
        $url = wp_get_attachment_image_url((int) $asset, $size);

        return $url ? (string) $url : '';
    }

    if (is_string($asset) && preg_match('#/assets/img/([A-Za-z0-9._/-]+\.(?:webp|png|jpe?g|svg))$#', $asset, $match) && file_exists(SOUTH_CITY_THEME_DIR . '/assets/img/' . $match[1])) {
        // Seeded content stores an absolute theme URL; re-base it on the current theme
        // location so images survive a theme rename, domain change or http/https switch.
        return SOUTH_CITY_THEME_URI . '/assets/img/' . $match[1];
    }

    return is_string($asset) ? $asset : '';
}

/**
 * Return a bundled icon photo URL when available.
 */
function south_city_icon_image_url(string $icon): string
{
    $icon = sanitize_file_name($icon);

    if ($icon === '') {
        return '';
    }

    $path = SOUTH_CITY_THEME_DIR . '/assets/img/icons/' . $icon . '.webp';

    return file_exists($path) ? SOUTH_CITY_THEME_URI . '/assets/img/icons/' . $icon . '.webp' : '';
}

/**
 * Return a bundled landmark image URL by key.
 */
function south_city_landmark_image_url(string $image_key): string
{
    $image_key = sanitize_file_name($image_key);

    if ($image_key === '') {
        return '';
    }

    $path = SOUTH_CITY_THEME_DIR . '/assets/img/landmark-' . $image_key . '.webp';

    return file_exists($path) ? SOUTH_CITY_THEME_URI . '/assets/img/landmark-' . $image_key . '.webp' : '';
}

/**
 * Return an inline SVG line icon for the brochure-driven sections.
 *
 * Icons inherit color via `currentColor` and scale to the parent font size.
 */
function south_city_inline_icon(string $key): string
{
    $paths = [
        'location'  => '<path d="M12 21s-7-6.3-7-11a7 7 0 1 1 14 0c0 4.7-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
        'map'       => '<path d="m9 4 6 2 5.5-2v14L15 20l-6-2-5.5 2V6L9 4Z"/><path d="M9 4v14M15 6v14"/>',
        'road'      => '<path d="M7 21 9 3M17 21 15 3M12 5v2M12 11v2M12 17v2"/>',
        'growth'    => '<path d="M4 19h16M6 16l4-5 4 3 5-8"/><path d="M19 6v4M19 6h-4"/>',
        'leaf'      => '<path d="M20 4S8 4 6 12c-1.5 6 3 8 3 8M20 4c0 8-4 12-11 12M20 4c-.5 4-2 6-2 6"/>',
        'shield'    => '<path d="M12 3 5 6v6c0 4.5 3 7.5 7 9 4-1.5 7-4.5 7-9V6l-7-3Z"/><path d="m9 12 2 2 4-4.5"/>',
        'family'    => '<circle cx="8" cy="7" r="2.5"/><circle cx="16" cy="7" r="2.5"/><path d="M4 20v-2a4 4 0 0 1 4-4M20 20v-2a4 4 0 0 0-4-4M10 20v-2a4 4 0 0 1 4-4"/>',
        'doc'       => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>',
        'wallet'    => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M16 14h2"/>',
        'crane'     => '<path d="M6 21V4l14 3-14 2M6 8h5M9 8v13M6 21h8"/>',
        'home'      => '<path d="M4 11 12 4l8 7M6 10v10h5v-6h2v6h5V10"/>',
        'handshake' => '<path d="m8 12 3 3 2-2 3 3M4 10l4-4 4 4 4-4 4 4M4 10v4l6 6 4-4M20 10v4l-3 3"/>',
        'target'    => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="1"/>',
        'eye'       => '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
        'building'  => '<rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h2M13 7h2M9 11h2M13 11h2M9 15h2M13 15h2M10 21v-3h4v3"/>',
        'sparkle'   => '<path d="M12 3v6M12 15v6M3 12h6M15 12h6M6 6l3 3M15 15l3 3M18 6l-3 3M9 15l-3 3"/>',
        'landplot'  => '<path d="M4 8 12 4l8 4v8l-8 4-8-4V8Z"/><path d="m4 8 8 4 8-4M12 12v8"/>',
        'stamp'     => '<path d="M9 3h6l-1 6h-4L9 3ZM6 15h12l-1-4H7l-1 4ZM4 21h16v-2H4v2Z"/>',
        'scale'     => '<path d="M12 4v16M6 20h12M4 8h16M8 8l-3 6a3 3 0 0 0 6 0L8 8ZM16 8l-3 6a3 3 0 0 0 6 0l-3-6Z"/>',
        'calendar'  => '<rect x="4" y="5" width="16" height="16" rx="2"/><path d="M4 10h16M9 3v4M15 3v4"/>',
        'route'     => '<circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="6" r="2.5"/><path d="M8 16.5 16 7.5M9 6H7a3 3 0 0 0 0 6h10a3 3 0 0 1 0 6h-2"/>',
        'water'     => '<path d="M4 14c2 0 2 2 4 2s2-2 4-2 2 2 4 2 2-2 4-2M4 18c2 0 2 2 4 2s2-2 4-2 2 2 4 2 2-2 4-2M12 3s5 5 5 8a5 5 0 0 1-10 0c0-3 5-8 5-8Z"/>',
        'check'     => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5L16 9"/>',
        'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
    ];

    $inner = $paths[$key] ?? $paths['check'];

    return '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $inner . '</svg>';
}

/**
 * Return the official WhatsApp glyph as an inline SVG (fills currentColor).
 */
function south_city_whatsapp_icon(string $classes = 'h-5 w-5'): string
{
    return '<svg class="' . esc_attr($classes) . '" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true" focusable="false">'
        . '<path d="M16.001 3C9.096 3 3.5 8.596 3.5 15.5c0 2.31.63 4.474 1.727 6.33L3 29l7.353-2.184a12.42 12.42 0 0 0 5.648 1.35h.005c6.905 0 12.5-5.596 12.5-12.5S22.906 3 16.001 3Zm0 22.7h-.004a10.36 10.36 0 0 1-5.283-1.447l-.379-.225-3.94 1.17 1.052-3.842-.247-.394a10.34 10.34 0 0 1-1.6-5.462c0-5.723 4.658-10.38 10.405-10.38 2.78 0 5.392 1.083 7.354 3.05a10.32 10.32 0 0 1 3.046 7.35c0 5.723-4.657 10.38-10.404 10.38Zm5.697-7.777c-.312-.156-1.848-.912-2.134-1.016-.286-.104-.494-.156-.702.156-.208.312-.806 1.016-.988 1.225-.182.208-.364.234-.676.078-.312-.156-1.318-.486-2.51-1.55-.928-.828-1.555-1.85-1.737-2.162-.182-.312-.02-.481.137-.636.14-.14.312-.364.468-.546.156-.182.208-.312.312-.52.104-.208.052-.39-.026-.546-.078-.156-.702-1.693-.962-2.319-.253-.61-.51-.527-.702-.537-.182-.008-.39-.01-.598-.01a1.15 1.15 0 0 0-.832.39c-.286.312-1.092 1.068-1.092 2.605s1.118 3.02 1.274 3.228c.156.208 2.2 3.36 5.33 4.712.745.322 1.325.514 1.778.658.747.238 1.427.204 1.965.124.6-.09 1.848-.756 2.108-1.485.26-.73.26-1.354.182-1.485-.078-.13-.286-.208-.598-.364Z"/>'
        . '</svg>';
}

/**
 * Read a bilingual value from an ACF repeater row.
 */
function south_city_get_locale_row_value(array $row, string $base_name, ?string $language = null): string
{
    $language = $language ?: south_city_current_language();
    $value    = $row[$base_name . '_' . $language] ?? '';

    if ($value !== null && $value !== false && $value !== '') {
        return (string) $value;
    }

    $fallback_language = $language === 'en' ? 'bn' : 'en';
    $fallback_value    = $row[$base_name . '_' . $fallback_language] ?? '';

    return $fallback_value ? (string) $fallback_value : '';
}

/**
 * Return a reusable query for ordered South City records.
 */
function south_city_ordered_query(string $post_type, int $posts_per_page = -1): WP_Query
{
    return new WP_Query([
        'post_type'      => $post_type,
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
        'meta_key'       => 'order_rank',
        'orderby'        => [
            'meta_value_num' => 'ASC',
            'menu_order'     => 'ASC',
            'title'          => 'ASC',
        ],
        'order'          => 'ASC',
    ]);
}

/**
 * Split a textarea value into trimmed paragraphs on blank lines.
 *
 * @return string[]
 */
function south_city_paragraphs(string $text): array
{
    $text  = str_replace(["\r\n", "\r"], "\n", $text);
    $parts = preg_split('/\n\s*\n/', trim($text)) ?: [];

    return array_values(array_filter(array_map('trim', $parts), static fn ($p) => $p !== ''));
}

/**
 * Amenity groups shown on the homepage, in display order.
 *
 * @return array<string,string> slug => translation key
 */
function south_city_amenity_groups(): array
{
    return [
        'core'     => 'amenities_group_core',
        'security' => 'amenities_group_security',
    ];
}

/**
 * Drop posts from an already-run WP_Query whose bilingual field value
 * (e.g. "label" or "caption") repeats an earlier post's value.
 *
 * Guards the front end against duplicate CPT rows regardless of cause
 * (a bad re-seed, manual admin entries, etc.) — the display never shows
 * the same tab/card/photo twice even if the database briefly does.
 */
function south_city_dedupe_query_by_field(WP_Query $query, string $field, ?string $language = null): void
{
    $language = $language ?: south_city_current_language();
    $seen     = [];

    $query->posts = array_values(array_filter($query->posts, static function ($post) use ($field, $language, &$seen) {
        $value = south_city_get_locale_field($field, $post->ID, $language);
        // strtolower (not mb_strtolower) on purpose: it only rewrites ASCII
        // A-Z bytes, so multibyte Bangla UTF-8 sequences pass through intact,
        // and it works even when the mbstring extension isn't available.
        $key   = strtolower(trim($value));

        if ($key === '' || isset($seen[$key])) {
            return false;
        }

        $seen[$key] = true;

        return true;
    }));

    $query->post_count   = count($query->posts);
    $query->current_post = -1;
}

/**
 * Return ordered amenities that belong to a given amenity group slug.
 */
function south_city_amenities_in_group(string $group_slug): WP_Query
{
    return new WP_Query([
        'post_type'      => 'southcity_amenity',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => [
            [
                'taxonomy' => 'southcity_amenity_group',
                'field'    => 'slug',
                'terms'    => $group_slug,
            ],
        ],
        'meta_key'       => 'order_rank',
        'orderby'        => [
            'meta_value_num' => 'ASC',
            'menu_order'     => 'ASC',
            'title'          => 'ASC',
        ],
        'order'          => 'ASC',
    ]);
}

require_once SOUTH_CITY_THEME_DIR . '/inc/cpt.php';
require_once SOUTH_CITY_THEME_DIR . '/inc/acf.php';
require_once SOUTH_CITY_THEME_DIR . '/inc/default-content.php';
require_once SOUTH_CITY_THEME_DIR . '/inc/seo.php';
require_once SOUTH_CITY_THEME_DIR . '/inc/tracking.php';

/**
 * Re-apply seed content when SOUTH_CITY_SEED_VERSION has been bumped.
 *
 * Runs after CPTs and taxonomies are registered. The function itself
 * returns early once the stored version matches, so this is cheap.
 */
add_action('init', 'south_city_seed_default_content', 99);
