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
$language_url   = south_city_language_switch_url($language);
$company_name   = south_city_get_locale_option('company_name', $language);
$company_name   = $company_name !== '' ? $company_name : 'South Dhaka Properties & Housing Ltd.';
$default_nav    = south_city_default_nav_items($language);
$contact_url    = is_front_page() ? '#contact' : trailingslashit($language === 'bn' ? home_url('/bn/') : home_url('/')) . '#contact';
?>
<!doctype html>
<html <?php language_attributes(); ?> data-site-language="<?php echo esc_attr($language); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        // Scroll-reveal is only hidden-by-default when JS is running. If main.js has not
        // signalled it is ready within 3s (blocked / delayed by a cache plugin), show everything.
        document.documentElement.classList.add('js');
        setTimeout(function () {
            if (!document.documentElement.classList.contains('sc-ready')) {
                document.documentElement.classList.add('sc-reveal-fallback');
            }
        }, 3000);
    </script>
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class('south-city-theme'); ?>>
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
            <?php elseif (has_site_icon()) : ?>
                <img src="<?php echo esc_url(get_site_icon_url(96)); ?>" alt="" class="h-9 w-9 shrink-0 object-contain md:h-10 md:w-10" width="40" height="40">
            <?php else : ?>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-navy text-sm font-bold text-white md:h-10 md:w-10" aria-hidden="true">SC</span>
            <?php endif; ?>
            <span class="font-display leading-tight">
                <span class="block text-lg font-extrabold tracking-wide text-navy"><?php echo esc_html(get_bloginfo('name') ?: 'SOUTH CITY'); ?></span>
                <span class="hidden text-[10px] font-medium uppercase tracking-[0.14em] text-gold md:block"><?php echo esc_html($company_name); ?></span>
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
                    <?php if ($nav_item['spy'] === 'contact') : ?>
                        <?php continue; ?>
                    <?php endif; ?>
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

        <div class="flex items-center gap-1.5 md:gap-3">
            <a
                href="<?php echo esc_url($language_url); ?>"
                class="hidden h-11 items-center gap-1.5 rounded-md border border-line bg-white px-2.5 text-xs font-semibold text-navy transition-colors hover:border-gold hover:text-gold md:inline-flex md:h-auto md:py-2"
                data-set-lang="<?php echo esc_attr($other_language); ?>"
                title="<?php echo esc_attr(south_city_translate('language_label', $language)); ?>"
            >
                <span aria-hidden="true">🌐</span>
                <span><?php echo esc_html(south_city_translate('language_short', $language)); ?></span>
            </a>

            <a
                href="<?php echo esc_url($contact_url); ?>"
                class="btn-gold hidden !min-h-[44px] !px-5 !py-2 md:inline-flex"
            >
                <span aria-hidden="true">✉</span>
                <?php echo esc_html(south_city_translate('contact', $language)); ?>
            </a>

            <button
                id="menu-btn"
                type="button"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md text-navy transition-colors hover:bg-navy/5 lg:hidden"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="<?php echo esc_attr(south_city_translate('open_menu', $language)); ?>"
            >
                <span class="menu-open text-2xl leading-none tracking-[0.08em]" aria-hidden="true">&#8942;</span>
                <span class="menu-close hidden text-3xl leading-none" aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <div
        id="mobile-menu"
        class="hidden absolute inset-x-0 top-full max-h-[calc(100dvh-56px)] overflow-y-auto bg-navy shadow-2xl lg:!hidden"
    >
        <div class="container-c flex flex-col py-2">
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
                    <?php if ($nav_item['spy'] === 'contact') : ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <a
                        href="<?php echo esc_url($nav_item['href']); ?>"
                        class="drawer-link flex min-h-[52px] items-center border-b border-white/10 text-lg font-semibold text-white transition-colors hover:text-gold-light active:bg-white/5"
                        data-spy="<?php echo esc_attr($nav_item['spy']); ?>"
                    >
                        <?php echo esc_html($nav_item['label']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <a
                href="<?php echo esc_url($language_url); ?>"
                class="drawer-link flex min-h-[52px] items-center gap-2 border-b border-white/10 text-lg font-semibold text-white transition-colors hover:text-gold-light active:bg-white/5"
                data-set-lang="<?php echo esc_attr($other_language); ?>"
            >
                <span aria-hidden="true">🌐</span>
                <?php echo esc_html(south_city_translate('language_label', $language)); ?>
            </a>

            <a
                href="<?php echo esc_url($contact_url); ?>"
                class="btn-gold mt-4 mb-2 !min-h-[48px]"
            >
                <span aria-hidden="true">✉</span>
                <?php echo esc_html(south_city_translate('contact', $language)); ?>
            </a>
        </div>
    </div>
</header>

<div class="h-14 md:h-[72px]" aria-hidden="true"></div>
