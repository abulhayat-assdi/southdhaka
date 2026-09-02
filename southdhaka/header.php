<?php
/**
 * Global header template.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

$language       = south_city_current_language();
$other_language = $language === 'bn' ? 'en' : 'bn';
$language_url   = $language === 'bn' ? home_url('/') : home_url('/bn/');
$phone          = (string) south_city_get_option('phone', '+8801886175263');
$phone_display  = (string) south_city_get_option('phone_display', '01886-175263');
$company_name   = south_city_get_locale_option('company_name', $language);
$company_name   = $company_name !== '' ? $company_name : 'South Dhaka Properties & Housing Ltd.';
$default_nav    = south_city_default_nav_items($language);
?>
<!doctype html>
<html <?php language_attributes(); ?> data-site-language="<?php echo esc_attr($language); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class('south-city-theme pb-14 md:pb-0'); ?>>
<?php wp_body_open(); ?>

<a
    href="#main"
    class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-navy focus:px-4 focus:py-2 focus:text-white"
>
    <?php echo esc_html(south_city_translate('skip_to_content', $language)); ?>
</a>

<header
    id="site-header"
    class="fixed inset-x-0 top-0 z-50 h-14 bg-white transition-shadow duration-300 md:h-[72px]"
>
    <div class="container-c flex h-full items-center justify-between gap-4">
        <a href="<?php echo esc_url(home_url($language === 'bn' ? '/bn/' : '/')); ?>" class="flex shrink-0 items-center gap-2.5" aria-label="<?php echo esc_attr(get_bloginfo('name') . ' home'); ?>">
            <?php if (has_custom_logo()) : ?>
                <span class="site-logo h-9 w-9 md:h-10 md:w-10">
                    <?php the_custom_logo(); ?>
                </span>
            <?php else : ?>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy text-sm font-bold text-white md:h-10 md:w-10" aria-hidden="true">SC</span>
            <?php endif; ?>
            <span class="font-display leading-tight">
                <span class="block text-lg font-extrabold tracking-wide text-navy"><?php echo esc_html(get_bloginfo('name') ?: 'SOUTH CITY'); ?></span>
                <span class="block text-[10px] font-medium uppercase tracking-[0.14em] text-gold"><?php echo esc_html($company_name); ?></span>
            </span>
        </a>

        <nav class="hidden items-center gap-6 lg:flex" aria-label="<?php esc_attr_e('Primary', 'south-city'); ?>">
            <?php if (has_nav_menu('primary')) : ?>
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'flex items-center gap-6',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]);
                ?>
            <?php else : ?>
                <?php foreach ($default_nav as $nav_item) : ?>
                    <a
                        href="<?php echo esc_url($nav_item['href']); ?>"
                        class="nav-link font-display text-[15px] font-semibold text-navy transition-colors hover:text-gold"
                        data-spy="<?php echo esc_attr($nav_item['spy']); ?>"
                    >
                        <?php echo esc_html($nav_item['label']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </nav>

        <div class="flex items-center gap-2 md:gap-3">
            <div id="google_translate_element" class="inline-flex items-center rounded-md border border-line overflow-hidden max-h-[40px] px-2 py-1 text-sm bg-white"></div>

            <a
                href="tel:<?php echo esc_attr($phone); ?>"
                class="btn-gold hidden !min-h-[44px] !px-5 !py-2 md:inline-flex"
                data-track="call_click"
            >
                <span aria-hidden="true">☎</span>
                <?php echo esc_html(south_city_translate('call_now', $language)); ?>
            </a>

            <button
                id="menu-btn"
                type="button"
                class="flex h-11 w-11 items-center justify-center rounded-md text-navy lg:hidden"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="<?php echo esc_attr(south_city_translate('open_menu', $language)); ?>"
            >
                <span class="menu-open text-3xl leading-none" aria-hidden="true">≡</span>
                <span class="menu-close hidden text-3xl leading-none" aria-hidden="true">×</span>
            </button>
        </div>
    </div>

    <div
        id="mobile-menu"
        class="hidden absolute inset-x-0 top-full max-h-[calc(100dvh-56px)] overflow-y-auto bg-navy shadow-2xl lg:!hidden"
    >
        <div class="container-c flex flex-col py-4">


            <?php if (has_nav_menu('primary')) : ?>
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-list flex flex-col',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]);
                ?>
            <?php else : ?>
                <?php foreach ($default_nav as $nav_item) : ?>
                    <a
                        href="<?php echo esc_url($nav_item['href']); ?>"
                        class="drawer-link flex min-h-[48px] items-center border-b border-white/10 text-lg font-semibold text-white transition-colors hover:text-gold-light"
                        data-spy="<?php echo esc_attr($nav_item['spy']); ?>"
                    >
                        <?php echo esc_html($nav_item['label']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <a
                href="tel:<?php echo esc_attr($phone); ?>"
                class="btn-gold mt-4"
                data-track="call_click"
            >
                <span aria-hidden="true">☎</span>
                <?php echo esc_html(south_city_translate('call_now', $language)); ?> · <?php echo esc_html($phone_display); ?>
            </a>
        </div>
    </div>
</header>

<div class="h-14 md:h-[72px]" aria-hidden="true"></div>
