<?php
/**
 * South City theme setup.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

define('SOUTH_CITY_THEME_VERSION', '1.0.0');
define('SOUTH_CITY_THEME_DIR', get_template_directory());
define('SOUTH_CITY_THEME_URI', get_template_directory_uri());

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
    add_theme_support('automatic-feed-links');
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
 * Register widget areas used by global templates.
 */
function south_city_widgets_init(): void
{
    register_sidebar([
        'name'          => __('Footer Column 1', 'south-city'),
        'id'            => 'footer-1',
        'description'   => __('First footer widget column.', 'south-city'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => __('Footer Column 2', 'south-city'),
        'id'            => 'footer-2',
        'description'   => __('Second footer widget column.', 'south-city'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => __('Footer Column 3', 'south-city'),
        'id'            => 'footer-3',
        'description'   => __('Third footer widget column.', 'south-city'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'south_city_widgets_init');

/**
 * Register language rewrite support for /bn/.
 */
function south_city_register_language_routes(): void
{
    add_rewrite_rule('^bn/?$', 'index.php?south_city_lang=bn', 'top');
}
add_action('init', 'south_city_register_language_routes');

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
        'https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&family=Hind+Siliguri:wght@500;600;700&family=Noto+Sans+Bengali:wght@400;500;600&display=swap',
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
            'en' => '1 Katha = 720 sq ft ≈ 66.9 m² · 1 Bigha = 20 Katha',
            'bn' => '১ কাঠা = ৭২০ বর্গফুট ≈ ৬৬.৯ বর্গমিটার · ১ বিঘা = ২০ কাঠা',
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
        'dimensions' => [
            'en' => 'Approx. dimensions',
            'bn' => 'আনুমানিক মাপ',
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
    ];

    return $strings[$key][$language] ?? $strings[$key]['en'] ?? $key;
}

/**
 * Return the fixed section links used when no WordPress menu is assigned.
 */
function south_city_default_nav_items(?string $language = null): array
{
    $language = $language ?: south_city_current_language();

    return [
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
        [
            'href' => '#contact',
            'label' => south_city_translate('contact', $language),
            'spy' => 'contact',
        ],
    ];
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

require_once SOUTH_CITY_THEME_DIR . '/inc/cpt.php';
require_once SOUTH_CITY_THEME_DIR . '/inc/acf.php';
require_once SOUTH_CITY_THEME_DIR . '/inc/default-content.php';
