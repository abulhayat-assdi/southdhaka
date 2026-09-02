<?php
/**
 * Global footer template.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

$language      = south_city_current_language();
$phone         = (string) south_city_get_option('phone', '+8801886175263');
$phone_display = (string) south_city_get_option('phone_display', '01886-175263');
$email         = (string) south_city_get_option('email', 'info@southdhaka.com');
$address       = south_city_get_locale_option('address', $language);
$address       = $address !== '' ? $address : 'Rahman Mansion (4th Floor), 161 Motijheel C/A, Dhaka-1000, Bangladesh';
$company_name  = south_city_get_locale_option('company_name', $language);
$company_name  = $company_name !== '' ? $company_name : 'South Dhaka Properties & Housing Ltd.';
$social_links  = [
    [
        'label' => 'Facebook',
        'url'   => (string) south_city_get_option('facebook_url', ''),
        'icon'  => 'f',
    ],
    [
        'label' => 'YouTube',
        'url'   => (string) south_city_get_option('youtube_url', ''),
        'icon'  => '▶',
    ],
    [
        'label' => 'LinkedIn',
        'url'   => (string) south_city_get_option('linkedin_url', ''),
        'icon'  => 'in',
    ],
];
$default_nav = south_city_default_nav_items($language);
?>

<footer class="bg-navy-deep text-white">
    <div class="container-c grid gap-10 py-14 md:grid-cols-3 md:gap-8">
        <div>
            <?php if (is_active_sidebar('footer-1')) : ?>
                <?php dynamic_sidebar('footer-1'); ?>
            <?php else : ?>
                <div class="flex items-center gap-3">
                    <?php if (has_custom_logo()) : ?>
                        <span class="site-logo-footer h-14 w-14 shrink-0 rounded-full bg-white p-1 ring-1 ring-gold/40">
                            <?php the_custom_logo(); ?>
                        </span>
                    <?php else : ?>
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white p-1 font-bold text-navy ring-1 ring-gold/40" aria-hidden="true">SC</span>
                    <?php endif; ?>
                    <div class="font-display leading-tight">
                        <p class="text-xl font-extrabold tracking-wide text-white"><?php echo esc_html(get_bloginfo('name') ?: 'SOUTH CITY'); ?></p>
                        <p class="text-xs font-medium text-gold-light"><?php echo esc_html($company_name); ?></p>
                    </div>
                </div>
                <p class="mt-4 font-display text-sm font-semibold italic text-gold-light">
                    <?php echo esc_html('"' . south_city_translate('footer_tagline', $language) . '"'); ?>
                </p>
            <?php endif; ?>
        </div>

        <div>
            <?php if (is_active_sidebar('footer-2')) : ?>
                <?php dynamic_sidebar('footer-2'); ?>
            <?php else : ?>
                <h2 class="font-display text-lg font-bold !text-white"><?php echo esc_html(south_city_translate('contact', $language)); ?></h2>
                <ul class="mt-4 space-y-3 text-white/85" role="list">
                    <li class="flex items-start gap-3">
                        <span class="mt-1 h-5 w-5 shrink-0 text-gold" aria-hidden="true">⌖</span>
                        <span>
                            <span class="block text-xs font-semibold uppercase tracking-wide text-gold-light">
                                <?php echo esc_html(south_city_translate('corporate_office', $language)); ?>
                            </span>
                            <?php echo esc_html($address); ?>
                        </span>
                    </li>
                    <li>
                        <a
                            href="tel:<?php echo esc_attr($phone); ?>"
                            class="flex min-h-[44px] items-center gap-3 hover:text-gold-light"
                            data-track="call_click"
                        >
                            <span class="h-5 w-5 shrink-0 text-gold" aria-hidden="true">☎</span>
                            <?php echo esc_html($phone_display); ?>
                        </a>
                    </li>
                    <li>
                        <a
                            href="mailto:<?php echo esc_attr($email); ?>"
                            class="flex min-h-[44px] items-center gap-3 break-all hover:text-gold-light"
                        >
                            <span class="h-5 w-5 shrink-0 text-gold" aria-hidden="true">✉</span>
                            <?php echo esc_html($email); ?>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>

        <div>
            <?php if (is_active_sidebar('footer-3')) : ?>
                <?php dynamic_sidebar('footer-3'); ?>
            <?php else : ?>
                <h2 class="font-display text-lg font-bold !text-white"><?php echo esc_html(south_city_translate('quick_links', $language)); ?></h2>
                <?php if (has_nav_menu('footer')) : ?>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'mt-4 grid grid-cols-2 gap-x-4',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]);
                    ?>
                <?php else : ?>
                    <ul class="mt-4 grid grid-cols-2 gap-x-4" role="list">
                        <?php foreach ($default_nav as $nav_item) : ?>
                            <li>
                                <a
                                    href="<?php echo esc_url($nav_item['href']); ?>"
                                    class="flex min-h-[40px] items-center text-white/85 transition-colors hover:text-gold-light"
                                >
                                    <?php echo esc_html($nav_item['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="mt-5 flex gap-3">
                    <?php foreach ($social_links as $social_link) : ?>
                        <?php if ($social_link['url'] === '') : ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <a
                            href="<?php echo esc_url($social_link['url']); ?>"
                            target="_blank"
                            rel="noopener"
                            aria-label="<?php echo esc_attr($social_link['label']); ?>"
                            class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 text-white/85 transition-colors hover:border-gold hover:text-gold-light"
                        >
                            <?php echo esc_html($social_link['icon']); ?>
                        </a>
                    <?php endforeach; ?>
                    <a
                        href="<?php echo esc_url(south_city_whatsapp_url($language)); ?>"
                        target="_blank"
                        rel="noopener"
                        aria-label="WhatsApp"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 text-white/85 transition-colors hover:border-whatsapp hover:text-whatsapp"
                        data-track="whatsapp_click"
                    >
                        W
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="border-t border-white/10">
        <p class="container-c py-5 text-center text-sm text-white/60">
            <?php echo esc_html(south_city_translate('copyright', $language)); ?>
        </p>
    </div>
</footer>

<nav
    class="fixed inset-x-0 bottom-0 z-50 grid h-14 grid-cols-2 md:hidden"
    aria-label="<?php echo esc_attr(south_city_translate('quick_contact', $language)); ?>"
    style="padding-bottom: env(safe-area-inset-bottom)"
>
    <a
        href="tel:<?php echo esc_attr($phone); ?>"
        class="flex items-center justify-center gap-2 bg-navy font-display text-base font-semibold text-white"
        data-track="call_click"
    >
        <span class="text-gold-light" aria-hidden="true">☎</span>
        <?php echo esc_html(south_city_translate('sticky_call', $language)); ?>
    </a>
    <a
        href="<?php echo esc_url(south_city_whatsapp_url($language)); ?>"
        target="_blank"
        rel="noopener"
        class="flex items-center justify-center gap-2 bg-whatsapp font-display text-base font-semibold text-white"
        data-track="whatsapp_click"
    >
        <span aria-hidden="true">W</span>
        <?php echo esc_html(south_city_translate('sticky_whatsapp', $language)); ?>
    </a>
</nav>

<a
    href="<?php echo esc_url(south_city_whatsapp_url($language)); ?>"
    target="_blank"
    rel="noopener"
    class="fab-pulse fixed bottom-6 right-6 z-50 hidden h-14 w-14 items-center justify-center rounded-full bg-whatsapp text-white shadow-lg transition-transform hover:scale-105 md:flex"
    aria-label="<?php echo esc_attr(south_city_translate('sticky_whatsapp', $language)); ?>"
    data-track="whatsapp_click"
>
    W
</a>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,bn', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<?php wp_footer(); ?>
</body>
</html>
