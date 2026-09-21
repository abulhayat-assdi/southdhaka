<?php
/**
 * SEO basics: page title, meta description, canonical, hreflang, Open Graph /
 * Twitter cards and Organization schema for the homepage and About Us page.
 *
 * When a dedicated SEO plugin (Yoast, Rank Math, AIOSEO, SEOPress) is active,
 * this file steps aside and only fixes the <html lang> attribute.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * True when a full SEO plugin already owns the head output.
 */
function south_city_seo_plugin_active(): bool
{
    return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION') || defined('SEOPRESS_VERSION');
}

/**
 * The pages this file writes SEO tags for.
 */
function south_city_seo_is_target_page(): bool
{
    return is_front_page() || is_page_template('page-about.php');
}

/**
 * Read a bilingual global option for exactly one language (no cross-language fallback).
 */
function south_city_seo_option(string $base, string $language): string
{
    $name  = $base . '_' . $language;
    $value = function_exists('get_field') ? get_field($name, 'option') : null;

    if (! is_string($value) || $value === '') {
        $value = get_option('options_' . $name, '');
    }

    return is_string($value) ? trim($value) : '';
}

/**
 * Trim text to a meta-description length without cutting Bangla characters in half.
 */
function south_city_seo_trim(string $text, int $length = 160): string
{
    $text = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags($text)) ?? '');

    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return rtrim(mb_substr($text, 0, $length - 1)) . '…';
}

/**
 * Page title for the current language.
 */
function south_city_seo_title(string $language): string
{
    if (is_front_page()) {
        $custom = south_city_seo_option('seo_title', $language);

        if ($custom !== '') {
            return $custom;
        }

        return $language === 'bn'
            ? 'সাউথ সিটি | সৈয়দপুর, দক্ষিণ কেরানীগঞ্জে পরিকল্পিত প্লট'
            : 'South City | Planned Plots in Sayedpur, South Keraniganj, Dhaka';
    }

    return south_city_translate('about_us', $language) . ' | ' . ($language === 'bn' ? 'সাউথ সিটি' : 'South City');
}

/**
 * Meta description for the current language.
 */
function south_city_seo_description(string $language): string
{
    if (is_front_page()) {
        $custom = south_city_seo_option('seo_description', $language);

        if ($custom !== '') {
            return $custom;
        }

        return $language === 'bn'
            ? 'সৈয়দপুর, দক্ষিণ কেরানীগঞ্জে প্রায় ৮০০ বিঘার পরিকল্পিত টাউনশিপ — ঢাকা-মাওয়া এক্সপ্রেসওয়ে থেকে মাত্র ২ মিনিট। ৩–৪০ কাঠার প্লট, ৫ বছর পর্যন্ত সহজ কিস্তি।'
            : 'Planned ~800-bigha township in Sayedpur, South Keraniganj, 2 minutes from the Dhaka-Mawa Expressway. 3–40 katha plots with easy installments up to 5 years.';
    }

    $front_id = (int) get_option('page_on_front');

    return south_city_seo_trim(south_city_get_locale_field('about_text', $front_id, $language));
}

/**
 * Social-share image: the "Social share image" setting, else the hero image.
 */
function south_city_seo_image_url(): string
{
    $image = south_city_asset_url(south_city_get_option('og_image', ''), 'full');

    if ($image === '') {
        $image = south_city_asset_url(south_city_get_field_or_meta('hero_image', (int) get_option('page_on_front')), 'large');
    }

    if ($image === '' && file_exists(SOUTH_CITY_THEME_DIR . '/assets/img/hero.webp')) {
        $image = SOUTH_CITY_THEME_URI . '/assets/img/hero.webp';
    }

    return $image;
}

/**
 * Document <title>.
 */
function south_city_seo_document_title(string $title): string
{
    if (south_city_seo_plugin_active() || ! south_city_seo_is_target_page()) {
        return $title;
    }

    return south_city_seo_title(south_city_current_language());
}
add_filter('pre_get_document_title', 'south_city_seo_document_title');

/**
 * The Bangla pages (/bn/...) must canonicalise to themselves, not to the English URL.
 *
 * @param string|false $canonical
 * @return string|false
 */
function south_city_seo_canonical($canonical, $post)
{
    if (south_city_seo_plugin_active()) {
        return $canonical;
    }

    if (get_query_var('south_city_lang') === 'bn' && $post instanceof WP_Post && $post->post_type === 'page') {
        return south_city_localized_url((int) $post->ID, 'bn');
    }

    return $canonical;
}
add_filter('get_canonical_url', 'south_city_seo_canonical', 10, 2);

/**
 * <html lang="..."> must say bn-BD on the Bangla pages (screen readers, search engines).
 */
function south_city_seo_language_attributes(string $output): string
{
    if (south_city_current_language() !== 'bn') {
        return $output;
    }

    $replaced = preg_replace('/lang="[^"]*"/', 'lang="bn-BD"', $output, 1, $count);

    return $count > 0 ? (string) $replaced : trim($output . ' lang="bn-BD"');
}
add_filter('language_attributes', 'south_city_seo_language_attributes');

/**
 * Description, hreflang, Open Graph, Twitter card and schema.
 */
function south_city_seo_head(): void
{
    if (south_city_seo_plugin_active() || ! south_city_seo_is_target_page()) {
        return;
    }

    $language    = south_city_current_language();
    $page_id     = (int) get_queried_object_id();
    $title       = south_city_seo_title($language);
    $description = south_city_seo_description($language);
    $url         = south_city_localized_url($page_id, $language);
    $image       = south_city_seo_image_url();

    if ($description !== '') {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }

    // Language alternates.
    echo '<link rel="alternate" hreflang="en" href="' . esc_url(south_city_localized_url($page_id, 'en')) . '">' . "\n";
    echo '<link rel="alternate" hreflang="bn" href="' . esc_url(south_city_localized_url($page_id, 'bn')) . '">' . "\n";
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url(south_city_localized_url($page_id, 'en')) . '">' . "\n";

    // Open Graph (Facebook, WhatsApp, LinkedIn) and Twitter/X.
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:site_name" content="South City">' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr($language === 'bn' ? 'bn_BD' : 'en_US') . '">' . "\n";
    echo '<meta property="og:locale:alternate" content="' . esc_attr($language === 'bn' ? 'en_US' : 'bn_BD') . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";

    if ($description !== '') {
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    }

    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";

    if ($image !== '') {
        echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
    } else {
        echo '<meta name="twitter:card" content="summary">' . "\n";
    }

    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";

    if ($description !== '') {
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    }

    if (is_front_page()) {
        south_city_seo_output_schema($image);
    }
}
add_action('wp_head', 'south_city_seo_head', 1);

/**
 * Organization + WebSite structured data (homepage only).
 */
function south_city_seo_output_schema(string $image): void
{
    $company = south_city_seo_option('company_name', 'en');
    $company = $company !== '' ? $company : 'South Dhaka Properties & Housing Ltd.';
    $address = south_city_seo_option('address', 'en');
    $address = $address !== '' ? $address : 'Rahman Mansion (4th Floor), 161 Motijheel C/A, Dhaka-1000, Bangladesh';

    $same_as = array_values(array_filter([
        (string) south_city_get_option('facebook_url', ''),
        (string) south_city_get_option('youtube_url', ''),
        (string) south_city_get_option('linkedin_url', ''),
    ], static fn (string $link): bool => $link !== ''));

    $organization = [
        '@type'     => 'RealEstateAgent',
        '@id'       => home_url('/#organization'),
        'name'      => $company,
        'url'       => home_url('/'),
        'telephone' => (string) south_city_get_option('phone', '+8801886175263'),
        'email'     => (string) south_city_get_option('email', 'info@southdhaka.com'),
        'address'   => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $address,
            'addressLocality' => 'Dhaka',
            'addressCountry'  => 'BD',
        ],
    ];

    if (has_custom_logo()) {
        $logo = wp_get_attachment_image_url((int) get_theme_mod('custom_logo'), 'full');

        if ($logo) {
            $organization['logo'] = $logo;
        }
    }

    if ($image !== '') {
        $organization['image'] = $image;
    }

    if ($same_as !== []) {
        $organization['sameAs'] = $same_as;
    }

    $data = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            $organization,
            [
                '@type'      => 'WebSite',
                '@id'        => home_url('/#website'),
                'url'        => home_url('/'),
                'name'       => 'South City',
                'inLanguage' => ['en', 'bn'],
                'publisher'  => ['@id' => home_url('/#organization')],
            ],
        ],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) . '</script>' . "\n";
}
