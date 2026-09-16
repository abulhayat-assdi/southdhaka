<?php
/**
 * Template Name: About Us
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();

$language       = south_city_current_language();
$content_id     = (int) get_option('page_on_front');

$about_text     = south_city_get_locale_field('about_text', $content_id, $language);
$vision_text    = south_city_get_locale_field('vision_text', $content_id, $language);
$mission_text   = south_city_get_locale_field('mission_text', $content_id, $language);
$core_values    = (array) south_city_get_field_or_meta('core_values', $content_id, []);

$chairman_body  = south_city_paragraphs(south_city_get_locale_field('chairman_body', $content_id, $language));
$chairman_name  = south_city_get_locale_field('chairman_name', $content_id, $language);
$chairman_image = south_city_asset_url(south_city_get_field_or_meta('chairman_image', $content_id), 'large');

$md_body        = south_city_paragraphs(south_city_get_locale_field('md_body', $content_id, $language));
$md_name        = south_city_get_locale_field('md_name', $content_id, $language);
$md_image       = south_city_asset_url(south_city_get_field_or_meta('md_image', $content_id), 'large');

if ($chairman_image === '' && file_exists(SOUTH_CITY_THEME_DIR . '/assets/img/hero.webp')) {
    $chairman_image = SOUTH_CITY_THEME_URI . '/assets/img/hero.webp';
}

if ($md_image === '' && file_exists(SOUTH_CITY_THEME_DIR . '/assets/img/amenities-bg.webp')) {
    $md_image = SOUTH_CITY_THEME_URI . '/assets/img/amenities-bg.webp';
}
?>

<main id="main">
    <section class="relative flex min-h-[220px] items-center overflow-hidden bg-navy-deep md:min-h-[280px]">
        <div class="absolute inset-0 bg-gradient-to-r from-navy-deep/90 via-navy/70 to-navy-deep/40" aria-hidden="true"></div>
        <div class="container-c relative z-10 py-14">
            <p class="eyebrow !text-gold-light"><?php echo esc_html(south_city_translate('profile_eyebrow', $language)); ?></p>
            <h1 class="max-w-2xl !text-white drop-shadow-md"><?php echo esc_html(south_city_translate('about_us', $language)); ?></h1>
        </div>
    </section>

    <?php if ($about_text !== '' || $vision_text !== '' || $mission_text !== '' || ! empty($core_values)) : ?>
        <section id="company-profile" class="section-pad bg-white sc-section">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('profile_eyebrow', $language)); ?></p>
                    <h2 class="sc-display"><?php echo esc_html(south_city_translate('profile_title', $language)); ?></h2>
                    <span class="sc-divider" aria-hidden="true"></span>
                </div>
                <div class="sc-profile-grid">
                    <?php if ($about_text !== '') : ?>
                        <article class="reveal card sc-profile-card">
                            <span class="sc-profile-card__icon" aria-hidden="true"><?php echo south_city_inline_icon('building'); ?></span>
                            <h3><?php echo esc_html(south_city_translate('profile_about', $language)); ?></h3>
                            <p><?php echo esc_html($about_text); ?></p>
                        </article>
                    <?php endif; ?>
                    <?php if ($vision_text !== '') : ?>
                        <article class="reveal card sc-profile-card">
                            <span class="sc-profile-card__icon" aria-hidden="true"><?php echo south_city_inline_icon('eye'); ?></span>
                            <h3><?php echo esc_html(south_city_translate('profile_vision', $language)); ?></h3>
                            <p><?php echo esc_html($vision_text); ?></p>
                        </article>
                    <?php endif; ?>
                    <?php if ($mission_text !== '') : ?>
                        <article class="reveal card sc-profile-card">
                            <span class="sc-profile-card__icon" aria-hidden="true"><?php echo south_city_inline_icon('target'); ?></span>
                            <h3><?php echo esc_html(south_city_translate('profile_mission', $language)); ?></h3>
                            <p><?php echo esc_html($mission_text); ?></p>
                        </article>
                    <?php endif; ?>
                    <?php if (! empty($core_values)) : ?>
                        <article class="reveal card sc-profile-card">
                            <span class="sc-profile-card__icon" aria-hidden="true"><?php echo south_city_inline_icon('sparkle'); ?></span>
                            <h3><?php echo esc_html(south_city_translate('profile_values', $language)); ?></h3>
                            <ul class="sc-values" role="list">
                                <?php foreach ($core_values as $value) : ?>
                                    <li><?php echo esc_html(south_city_get_locale_row_value((array) $value, 'value', $language)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </article>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (! empty($chairman_body)) : ?>
        <section id="chairman" class="section-pad bg-bg-soft sc-section">
            <div class="container-c">
                <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
                    <div class="reveal">
                        <p class="eyebrow"><?php echo esc_html(south_city_translate('chairman_eyebrow', $language)); ?></p>
                        <h2 class="sc-display"><?php echo esc_html(south_city_translate('chairman_title', $language)); ?></h2>
                        <span class="sc-divider" aria-hidden="true"></span>
                        <div class="sc-message">
                            <?php foreach ($chairman_body as $paragraph) : ?>
                                <p><?php echo esc_html($paragraph); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($chairman_name !== '') : ?>
                            <p class="sc-signoff">
                                <span class="sc-signoff__role"><?php echo esc_html(south_city_translate('chairman_role', $language)); ?></span>
                                <?php echo esc_html($chairman_name); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php if ($chairman_image !== '') : ?>
                        <div class="reveal sc-message-media">
                            <img src="<?php echo esc_url($chairman_image); ?>" alt="" class="rounded-xl border border-line object-cover shadow-card" loading="lazy" aria-hidden="true">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (! empty($md_body)) : ?>
        <section id="md-message" class="section-pad bg-white sc-section">
            <div class="container-c">
                <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
                    <?php if ($md_image !== '') : ?>
                        <div class="reveal sc-message-media lg:order-1">
                            <img src="<?php echo esc_url($md_image); ?>" alt="" class="rounded-xl border border-line object-cover shadow-card" loading="lazy" aria-hidden="true">
                        </div>
                    <?php endif; ?>
                    <div class="reveal lg:order-2">
                        <p class="eyebrow"><?php echo esc_html(south_city_translate('md_eyebrow', $language)); ?></p>
                        <h2 class="sc-display"><?php echo esc_html(south_city_translate('md_title', $language)); ?></h2>
                        <span class="sc-divider" aria-hidden="true"></span>
                        <div class="sc-message">
                            <?php foreach ($md_body as $paragraph) : ?>
                                <p><?php echo esc_html($paragraph); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($md_name !== '') : ?>
                            <p class="sc-signoff">
                                <span class="sc-signoff__role"><?php echo esc_html(south_city_translate('md_role', $language)); ?></span>
                                <?php echo esc_html($md_name); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php
get_footer();
