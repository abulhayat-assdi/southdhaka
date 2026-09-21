<?php
/**
 * Homepage template converted from the Astro landing page.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();

$language          = south_city_current_language();
$hero_headline     = south_city_get_locale_field('hero_headline', get_the_ID(), $language);
$hero_subline      = south_city_get_locale_field('hero_subline', get_the_ID(), $language);
$hero_image        = south_city_asset_url(south_city_get_field_or_meta('hero_image', get_the_ID()), 'full');
$hero_chips        = (array) south_city_get_field_or_meta('hero_chips', get_the_ID(), []);
$overview_text     = south_city_get_locale_field('overview_paragraph', get_the_ID(), $language);
$overview_counters = (array) south_city_get_field_or_meta('overview_counters', get_the_ID(), []);
$master_plan_image = south_city_asset_url(south_city_get_field_or_meta('master_plan_image', get_the_ID()), 'full');
$hotspots          = (array) south_city_get_field_or_meta('master_plan_hotspots', get_the_ID(), []);
$distances         = (array) south_city_get_field_or_meta('location_distances', get_the_ID(), []);
$boundaries        = (array) south_city_get_field_or_meta('project_boundaries', get_the_ID(), []);
$primary_location  = function_exists('south_city_get_primary_location') ? south_city_get_primary_location() : null;

if ($primary_location) {
    $map_lat  = (string) get_field('latitude', $primary_location->ID);
    $map_lng  = (string) get_field('longitude', $primary_location->ID);
    $map_zoom = (int) (get_field('zoom', $primary_location->ID) ?: 15);
    $map_src  = 'https://www.google.com/maps?q=' . rawurlencode($map_lat . ',' . $map_lng) . '&z=' . $map_zoom . '&output=embed';
} else {
    $map_lat = SOUTH_CITY_MAP_LAT;
    $map_lng = SOUTH_CITY_MAP_LNG;
    $map_src = 'https://www.google.com/maps?q=' . rawurlencode($map_lat . ',' . $map_lng) . '&z=15&output=embed';
}
// Tapping anywhere on the map opens Google Maps with directions to the project.
$map_directions_url = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($map_lat . ',' . $map_lng);
$brochure          = south_city_get_option('brochure_pdf', '');
$brochure_url      = south_city_asset_url($brochure, 'full');

$about_text        = south_city_get_locale_field('about_text', get_the_ID(), $language);
$vision_text       = south_city_get_locale_field('vision_text', get_the_ID(), $language);
$mission_text      = south_city_get_locale_field('mission_text', get_the_ID(), $language);
$core_values       = (array) south_city_get_field_or_meta('core_values', get_the_ID(), []);
$why_points        = (array) south_city_get_field_or_meta('why_points', get_the_ID(), []);
$investment_intro  = south_city_get_locale_field('investment_intro', get_the_ID(), $language);
$investment_points = (array) south_city_get_field_or_meta('investment_points', get_the_ID(), []);
$ownership_steps   = (array) south_city_get_field_or_meta('ownership_steps', get_the_ID(), []);

if ($hero_headline === '') {
    $hero_headline = $language === 'bn' ? 'যেখানে আপনার স্বপ্নেরা তার ঠিকানা খুঁজে পায়' : 'Where Your Dreams Find Their Address';
}

if ($hero_subline === '') {
    $hero_subline = $language === 'bn'
        ? 'সৈয়দপুর ইউনিয়নে প্রায় ৮০০ বিঘার পরিকল্পিত টাউনশিপ।'
        : 'A planned township in Sayedpur Union beside the Eastern Bypass.';
}

if ($hero_image === '' && file_exists(SOUTH_CITY_THEME_DIR . '/assets/img/hero.webp')) {
    $hero_image = SOUTH_CITY_THEME_URI . '/assets/img/hero.webp';
}

if ($master_plan_image === '' && file_exists(SOUTH_CITY_THEME_DIR . '/assets/img/masterplan.webp')) {
    $master_plan_image = SOUTH_CITY_THEME_URI . '/assets/img/masterplan.webp';
}
?>

<main id="main">
    <section class="relative flex min-h-[calc(100svh-56px)] items-center overflow-hidden bg-navy-deep md:min-h-[calc(100svh-72px)]">
        <?php if ($hero_image !== '') : ?>
            <img src="<?php echo esc_url($hero_image); ?>" alt="" class="absolute inset-0 h-full w-full object-cover object-center" loading="eager" fetchpriority="high" aria-hidden="true">
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-r from-navy-deep/80 via-navy/55 to-navy-deep/30" aria-hidden="true"></div>

        <div class="container-c relative z-10 py-16 md:py-24">
            <p class="eyebrow !text-gold-light"><?php echo esc_html(south_city_translate('hero_eyebrow', $language)); ?></p>
            <h1 class="max-w-3xl !text-white drop-shadow-md"><?php echo esc_html($hero_headline); ?></h1>
            <p class="mt-4 max-w-2xl text-lg text-white/90 md:text-xl"><?php echo esc_html($hero_subline); ?></p>

            <?php if (! empty($hero_chips)) : ?>
                <ul class="mt-6 flex max-w-2xl flex-wrap gap-2" role="list">
                    <?php foreach ($hero_chips as $chip) : ?>
                        <?php $chip_text = south_city_get_locale_row_value((array) $chip, 'text', $language); ?>
                        <?php if ($chip_text === '') : ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <li class="inline-flex items-center gap-1.5 rounded-full border border-gold/60 bg-navy-deep/50 px-3.5 py-1.5 text-sm font-medium text-gold-light backdrop-blur-sm">
                            <span aria-hidden="true">✓</span>
                            <?php echo esc_html($chip_text); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:gap-4">
                <a href="<?php echo esc_url(south_city_whatsapp_url($language)); ?>" target="_blank" rel="noopener" class="btn-wa" data-track="whatsapp_click">
                    <?php echo esc_html(south_city_translate('whatsapp_us', $language)); ?>
                </a>
                <a href="#contact" class="btn-outline-light">
                    <?php echo esc_html(south_city_translate('get_plot_details', $language)); ?>
                </a>
            </div>
        </div>
    </section>

    <section id="overview" class="section-pad bg-white">
        <div class="container-c">
            <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
                <div>
                    <div class="reveal mb-8 md:mb-12">
                        <p class="eyebrow"><?php echo esc_html(south_city_translate('overview_eyebrow', $language)); ?></p>
                        <h2><?php echo esc_html(south_city_translate('overview_title', $language)); ?></h2>
                    </div>
                    <?php if ($overview_text !== '') : ?>
                        <p class="reveal max-w-prose text-[17px] leading-relaxed text-muted"><?php echo esc_html($overview_text); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (! empty($overview_counters)) : ?>
                    <div class="grid grid-cols-2 content-center gap-4 sm:gap-6">
                        <?php foreach ($overview_counters as $counter) : ?>
                            <?php
                            $counter = (array) $counter;
                            $display = south_city_get_locale_row_value($counter, 'display', $language);
                            $label   = south_city_get_locale_row_value($counter, 'label', $language);
                            ?>
                            <div class="reveal card flex flex-col items-center justify-center px-4 py-7 text-center">
                                <span
                                    class="counter font-display text-3xl font-extrabold text-navy sm:text-4xl"
                                    data-end="<?php echo esc_attr((string) ($counter['end'] ?? '')); ?>"
                                    data-final="<?php echo esc_attr($display); ?>"
                                    data-bn="<?php echo esc_attr($language === 'bn' ? '1' : ''); ?>"
                                >
                                    <?php echo esc_html($display); ?>
                                </span>
                                <span class="mt-2 h-0.5 w-8 bg-gold" aria-hidden="true"></span>
                                <span class="mt-2 text-sm font-medium text-muted"><?php echo esc_html($label); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php $facts_query = south_city_ordered_query('southcity_fact'); ?>
    <?php if ($facts_query->have_posts()) : ?>
        <section class="section-pad bg-bg-soft">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('facts_eyebrow', $language)); ?></p>
                    <h2><?php echo esc_html(south_city_translate('facts_title', $language)); ?></h2>
                </div>
                <dl class="grid gap-x-12 md:grid-cols-2">
                    <?php $fact_index = 1; ?>
                    <?php while ($facts_query->have_posts()) : ?>
                        <?php $facts_query->the_post(); ?>
                        <div class="reveal flex items-baseline gap-4 border-b border-line py-4">
                            <span class="font-display text-sm font-bold text-gold" aria-hidden="true"><?php echo esc_html(str_pad((string) $fact_index, 2, '0', STR_PAD_LEFT)); ?></span>
                            <dt class="w-32 shrink-0 text-sm font-semibold uppercase tracking-wide text-muted sm:w-40"><?php echo esc_html(south_city_get_locale_field('label', get_the_ID(), $language)); ?></dt>
                            <dd class="flex-1 font-medium text-navy"><?php echo esc_html(south_city_get_locale_field('value', get_the_ID(), $language)); ?></dd>
                        </div>
                        <?php $fact_index++; ?>
                    <?php endwhile; ?>
                </dl>
                <?php if ($brochure_url !== '') : ?>
                    <div class="reveal mt-8">
                        <a href="<?php echo esc_url($brochure_url); ?>" class="btn-gold" download data-track="brochure_download">
                            <?php echo esc_html(south_city_translate('download_brochure', $language)); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php if (! empty($why_points)) : ?>
        <section id="why" class="section-pad bg-white sc-section">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('why_eyebrow', $language)); ?></p>
                    <h2 class="sc-display"><?php echo esc_html(south_city_translate('why_title', $language)); ?></h2>
                    <span class="sc-divider" aria-hidden="true"></span>
                </div>
                <ul class="sc-why-grid" role="list">
                    <?php foreach ($why_points as $point) : ?>
                        <?php
                        $point      = (array) $point;
                        $why_title  = south_city_get_locale_row_value($point, 'title', $language);
                        $why_body   = south_city_get_locale_row_value($point, 'body', $language);
                        ?>
                        <li class="reveal sc-why-item">
                            <span class="sc-why-item__icon" aria-hidden="true"><?php echo south_city_inline_icon((string) ($point['icon'] ?? 'check')); ?></span>
                            <div>
                                <h3><?php echo esc_html($why_title); ?></h3>
                                <p><?php echo esc_html($why_body); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($about_text !== '' || $vision_text !== '' || $mission_text !== '' || ! empty($core_values)) : ?>
        <section id="company-profile" class="section-pad bg-bg-soft sc-section">
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

    <section id="master-plan" class="section-pad bg-white sc-section">
        <div class="container-c">
            <div class="reveal mb-8 md:mb-12">
                <p class="eyebrow"><?php echo esc_html(south_city_translate('plan_eyebrow', $language)); ?></p>
                <h2 class="sc-display"><?php echo esc_html(south_city_translate('plan_title', $language)); ?></h2>
                <span class="sc-divider" aria-hidden="true"></span>
            </div>
            <?php if ($master_plan_image !== '') : ?>
                <div class="reveal relative overflow-hidden rounded-xl border border-line shadow-card">
                    <img src="<?php echo esc_url($master_plan_image); ?>" alt="<?php echo esc_attr($language === 'bn' ? 'সাউথ সিটি মাস্টার প্ল্যান ও সেক্টর লেআউট' : 'South City master plan & sector layout'); ?>" class="h-auto w-full" loading="lazy">
                </div>
            <?php endif; ?>
            <?php if (! empty($hotspots)) : ?>
                <h3 class="sc-subtitle mt-8"><?php echo esc_html(south_city_translate('legend_title', $language)); ?></h3>
                <ul class="mt-3 grid gap-3 sm:grid-cols-2" role="list">
                    <?php foreach ($hotspots as $hotspot) : ?>
                        <?php $hotspot = (array) $hotspot; ?>
                        <li class="reveal card px-5 py-4">
                            <p class="font-semibold text-navy"><?php echo esc_html(south_city_get_locale_row_value($hotspot, 'name', $language)); ?></p>
                            <p class="mt-0.5 text-sm text-muted"><?php echo esc_html(south_city_get_locale_row_value($hotspot, 'desc', $language)); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>

    <?php $plots_query = south_city_ordered_query('southcity_plot'); ?>
    <?php if ($plots_query->have_posts()) : ?>
        <section id="plots" class="section-pad bg-bg-soft">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('plots_eyebrow', $language)); ?></p>
                    <h2><?php echo esc_html(south_city_translate('plots_title', $language)); ?></h2>
                    <p class="mt-3 text-sm text-muted"><?php echo esc_html(south_city_translate('plots_note', $language)); ?></p>
                </div>

                <div class="reveal" data-tabs>
                    <div class="hidden flex-wrap gap-2 sm:flex" role="tablist" aria-label="<?php echo esc_attr(south_city_translate('plots_title', $language)); ?>">
                        <?php $plot_index = 0; ?>
                        <?php while ($plots_query->have_posts()) : ?>
                            <?php $plots_query->the_post(); ?>
                            <?php $plot_id = 'plot-' . get_the_ID(); ?>
                            <button
                                type="button"
                                role="tab"
                                id="tab-<?php echo esc_attr($plot_id); ?>"
                                aria-controls="panel-<?php echo esc_attr($plot_id); ?>"
                                aria-selected="<?php echo esc_attr($plot_index === 0 ? 'true' : 'false'); ?>"
                                tabindex="<?php echo esc_attr($plot_index === 0 ? '0' : '-1'); ?>"
                                class="min-h-[48px] rounded-t-lg px-6 py-3 font-display text-base font-semibold transition-colors <?php echo esc_attr($plot_index === 0 ? 'bg-navy text-gold-light' : 'border border-b-0 border-line bg-white text-navy hover:bg-navy/5'); ?>"
                                data-tab="<?php echo esc_attr($plot_id); ?>"
                            >
                                <?php echo esc_html(south_city_get_locale_field('katha', get_the_ID(), $language)); ?>
                            </button>
                            <?php $plot_index++; ?>
                        <?php endwhile; ?>
                    </div>

                    <?php $plots_query->rewind_posts(); ?>
                    <?php $plot_index = 0; ?>
                    <?php while ($plots_query->have_posts()) : ?>
                        <?php $plots_query->the_post(); ?>
                        <?php
                        $plot_id     = 'plot-' . get_the_ID();
                        $katha       = south_city_get_locale_field('katha', get_the_ID(), $language);
                        $zone        = south_city_get_locale_field('zone', get_the_ID(), $language);
                        ?>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg border border-line bg-white px-4 py-3.5 font-display text-base font-semibold text-navy sm:hidden <?php echo esc_attr($plot_index > 0 ? 'mt-3' : ''); ?>"
                            data-acc="<?php echo esc_attr($plot_id); ?>"
                            aria-expanded="<?php echo esc_attr($plot_index === 0 ? 'true' : 'false'); ?>"
                            aria-controls="panel-<?php echo esc_attr($plot_id); ?>"
                        >
                            <?php echo esc_html($katha); ?>
                            <span aria-hidden="true">⌄</span>
                        </button>

                        <div
                            id="panel-<?php echo esc_attr($plot_id); ?>"
                            role="tabpanel"
                            aria-labelledby="tab-<?php echo esc_attr($plot_id); ?>"
                            class="rounded-b-xl rounded-tr-xl border border-line bg-white p-5 sm:p-8 <?php echo esc_attr($plot_index > 0 ? 'hidden' : ''); ?>"
                            data-panel="<?php echo esc_attr($plot_id); ?>"
                        >
                            <div class="grid items-center gap-6 md:grid-cols-[1fr_auto]">
                                <div class="rounded-lg bg-bg-soft p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-muted"><?php echo esc_html(south_city_translate('area', $language)); ?></p>
                                    <p class="mt-1 font-display text-xl font-bold text-navy"><?php echo esc_html($katha); ?></p>
                                    <?php if ($zone !== '') : ?>
                                        <span class="mt-2 inline-block rounded-full border border-gold/50 bg-gold/10 px-2.5 py-0.5 text-xs font-semibold text-navy"><?php echo esc_html($zone); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo esc_url(south_city_whatsapp_url($language)); ?>" target="_blank" rel="noopener" class="btn-gold w-full md:max-w-xs" data-track="whatsapp_click">
                                    <?php echo esc_html(south_city_translate('reserve_plot', $language)); ?>
                                </a>
                            </div>
                        </div>
                        <?php $plot_index++; ?>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php if (! empty($investment_points)) : ?>
        <section id="investment" class="section-pad bg-navy-deep sc-section sc-section--dark">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow !text-gold-light"><?php echo esc_html(south_city_translate('investment_eyebrow', $language)); ?></p>
                    <h2 class="sc-display !text-white"><?php echo esc_html(south_city_translate('investment_title', $language)); ?></h2>
                    <span class="sc-divider" aria-hidden="true"></span>
                    <?php if ($investment_intro !== '') : ?>
                        <p class="mt-4 max-w-2xl text-white/80"><?php echo esc_html($investment_intro); ?></p>
                    <?php endif; ?>
                </div>
                <ul class="sc-invest-grid" role="list">
                    <?php foreach ($investment_points as $point) : ?>
                        <?php
                        $point       = (array) $point;
                        $inv_title   = south_city_get_locale_row_value($point, 'title', $language);
                        $inv_body    = south_city_get_locale_row_value($point, 'body', $language);
                        ?>
                        <li class="reveal sc-invest-item">
                            <span class="sc-invest-item__icon" aria-hidden="true"><?php echo south_city_inline_icon((string) ($point['icon'] ?? 'check')); ?></span>
                            <h3><?php echo esc_html($inv_title); ?></h3>
                            <p><?php echo esc_html($inv_body); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>

    <?php if (! empty($ownership_steps)) : ?>
        <section id="ownership-process" class="section-pad bg-white sc-section">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('process_eyebrow', $language)); ?></p>
                    <h2 class="sc-display"><?php echo esc_html(south_city_translate('process_title', $language)); ?></h2>
                    <span class="sc-divider" aria-hidden="true"></span>
                </div>
                <ol class="sc-process" role="list">
                    <?php foreach (array_values($ownership_steps) as $index => $step) : ?>
                        <?php
                        $step       = (array) $step;
                        $step_title = south_city_get_locale_row_value($step, 'title', $language);
                        ?>
                        <li class="reveal sc-process__step">
                            <span class="sc-process__num"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                            <span class="sc-process__icon" aria-hidden="true"><?php echo south_city_inline_icon((string) ($step['icon'] ?? 'check')); ?></span>
                            <span class="sc-process__label"><?php echo esc_html($step_title); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </section>
    <?php endif; ?>

    <section id="location" class="section-pad bg-white">
        <div class="container-c">
            <div class="reveal mb-8 md:mb-12">
                <p class="eyebrow"><?php echo esc_html(south_city_translate('location_eyebrow', $language)); ?></p>
                <h2><?php echo esc_html(south_city_translate('location_title', $language)); ?></h2>
            </div>
            <div class="grid gap-8 lg:grid-cols-2">
                <div id="map-shell" class="reveal relative flex min-h-[320px] items-center justify-center overflow-hidden rounded-xl border border-line bg-bg-soft lg:min-h-[420px]" data-map-src="<?php echo esc_url($map_src); ?>">
                    <button type="button" id="map-load" class="btn-outline"><?php echo esc_html(south_city_translate('show_map', $language)); ?></button>
                    <p class="absolute bottom-4 px-4 text-center text-xs text-muted">Sayedpur, South Keraniganj, Dhaka</p>
                    <a href="<?php echo esc_url($map_directions_url); ?>" target="_blank" rel="noopener" class="absolute inset-0 z-10 block cursor-pointer" aria-label="<?php echo esc_attr(south_city_translate('get_directions', $language)); ?> - South City" data-track="map_directions_click">
                        <span class="absolute left-3 top-3 rounded-md bg-white px-3 py-2 text-sm font-semibold text-navy shadow-card"><?php echo esc_html(south_city_translate('get_directions', $language)); ?> ↗</span>
                    </a>
                </div>
                <div>
                    <?php if (! empty($distances)) : ?>
                        <h3 class="reveal font-display text-xl font-bold text-navy"><?php echo esc_html(south_city_translate('distances_title', $language)); ?></h3>
                        <ul class="mt-4 divide-y divide-line" role="list">
                            <?php foreach ($distances as $distance) : ?>
                                <?php $distance = (array) $distance; ?>
                                <li class="reveal flex items-center justify-between gap-4 py-3.5">
                                    <span class="font-medium text-ink"><?php echo esc_html(south_city_get_locale_row_value($distance, 'place', $language)); ?></span>
                                    <span class="font-display text-lg font-bold text-navy"><?php echo esc_html(south_city_get_locale_row_value($distance, 'value', $language)); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if (! empty($boundaries)) : ?>
                        <p class="reveal mt-6 rounded-lg bg-bg-soft p-4 text-sm leading-relaxed text-muted">
                            <span class="mb-1 block font-semibold text-navy"><?php echo esc_html(south_city_translate('boundaries', $language)); ?>:</span>
                            <?php foreach ($boundaries as $index => $boundary) : ?>
                                <?php $boundary = (array) $boundary; ?>
                                <span><?php echo esc_html(south_city_get_locale_row_value($boundary, 'side', $language)); ?> - <?php echo esc_html(south_city_get_locale_row_value($boundary, 'value', $language)); ?><?php echo esc_html($index < count($boundaries) - 1 ? ' · ' : ''); ?></span>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php $landmark_query = south_city_ordered_query('southcity_landmark'); ?>
    <?php if ($landmark_query->have_posts()) : ?>
        <?php south_city_dedupe_query_by_field($landmark_query, 'label', $language); ?>
        <section class="section-pad bg-bg-soft">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('landmarks_eyebrow', $language)); ?></p>
                    <h2><?php echo esc_html(south_city_translate('landmarks_title', $language)); ?></h2>
                </div>
                <div class="reveal" data-lm-tabs>
                    <div class="flex flex-wrap gap-2" role="tablist" aria-label="<?php echo esc_attr(south_city_translate('landmarks_title', $language)); ?>">
                        <?php $landmark_index = 0; ?>
                        <?php while ($landmark_query->have_posts()) : ?>
                            <?php $landmark_query->the_post(); ?>
                            <?php $landmark_id = 'landmark-' . get_the_ID(); ?>
                            <button type="button" role="tab" id="lmtab-<?php echo esc_attr($landmark_id); ?>" aria-controls="lmpanel-<?php echo esc_attr($landmark_id); ?>" aria-selected="<?php echo esc_attr($landmark_index === 0 ? 'true' : 'false'); ?>" tabindex="<?php echo esc_attr($landmark_index === 0 ? '0' : '-1'); ?>" class="min-h-[44px] rounded-full px-5 py-2 font-display text-sm font-semibold transition-colors sm:text-base <?php echo esc_attr($landmark_index === 0 ? 'bg-navy text-gold-light' : 'border border-line bg-white text-navy hover:border-gold'); ?>" data-lmtab="<?php echo esc_attr($landmark_id); ?>">
                                <?php echo esc_html(south_city_get_locale_field('label', get_the_ID(), $language)); ?>
                            </button>
                            <?php $landmark_index++; ?>
                        <?php endwhile; ?>
                    </div>

                    <?php $landmark_query->rewind_posts(); ?>
                    <?php $landmark_index = 0; ?>
                    <?php while ($landmark_query->have_posts()) : ?>
                        <?php $landmark_query->the_post(); ?>
                        <?php
                        $landmark_id = 'landmark-' . get_the_ID();
                        $items       = (array) south_city_get_field_or_meta('items', get_the_ID(), []);
                        $image_key   = (string) south_city_get_field_or_meta('image_key', get_the_ID(), '');
                        $image_url   = south_city_landmark_image_url($image_key);
                        ?>
                        <div id="lmpanel-<?php echo esc_attr($landmark_id); ?>" role="tabpanel" aria-labelledby="lmtab-<?php echo esc_attr($landmark_id); ?>" class="mt-6 grid items-start gap-6 md:grid-cols-2 <?php echo esc_attr($landmark_index > 0 ? 'hidden' : ''); ?>" data-lmpanel="<?php echo esc_attr($landmark_id); ?>">
                            <ol class="divide-y divide-line rounded-xl border border-line bg-white px-5">
                                <?php foreach ($items as $item_index => $item) : ?>
                                    <?php $item = (array) $item; ?>
                                    <li class="flex items-baseline gap-4 py-4">
                                        <span class="font-display text-sm font-bold text-gold" aria-hidden="true"><?php echo esc_html(str_pad((string) ($item_index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                                        <div>
                                            <p class="font-semibold text-navy"><?php echo esc_html(south_city_get_locale_row_value($item, 'name', $language)); ?></p>
                                            <p class="text-sm text-muted"><?php echo esc_html(south_city_get_locale_row_value($item, 'note', $language)); ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', ['class' => 'aspect-[4/3] w-full rounded-xl border border-line object-cover shadow-card']); ?>
                            <?php elseif ($image_url !== '') : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(south_city_get_locale_field('label', get_the_ID(), $language)); ?>" class="aspect-[4/3] w-full rounded-xl border border-line object-cover shadow-card" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <?php $landmark_index++; ?>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php
    $amenity_groups   = south_city_amenity_groups();
    $amenity_group_qs = [];
    $has_any_amenity  = false;
    foreach ($amenity_groups as $group_slug => $group_key) {
        $group_query = south_city_amenities_in_group($group_slug);
        if ($group_query->have_posts()) {
            south_city_dedupe_query_by_field($group_query, 'label', $language);
        }
        $amenity_group_qs[$group_slug] = $group_query;
        if ($group_query->have_posts()) {
            $has_any_amenity = true;
        }
    }
    ?>
    <?php if (! $has_any_amenity) : ?>
        <?php $amenity_query = south_city_ordered_query('southcity_amenity'); ?>
        <?php if ($amenity_query->have_posts()) : ?>
            <?php
            south_city_dedupe_query_by_field($amenity_query, 'label', $language);
            // Fallback: ungrouped amenities (before the grouped seed runs).
            $amenity_group_qs = ['core' => $amenity_query];
            $has_any_amenity  = true;
            $amenity_groups   = ['core' => 'amenities_title'];
            ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($has_any_amenity) : ?>
        <section id="amenities" class="section-pad relative isolate overflow-hidden bg-white sc-section">
            <div class="container-c relative">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('amenities_eyebrow', $language)); ?></p>
                    <h2 class="sc-display"><?php echo esc_html(south_city_translate('amenities_title', $language)); ?></h2>
                    <span class="sc-divider" aria-hidden="true"></span>
                </div>

                <?php foreach ($amenity_groups as $group_slug => $group_key) : ?>
                    <?php
                    $group_query = $amenity_group_qs[$group_slug] ?? null;
                    if (! $group_query || ! $group_query->have_posts()) {
                        continue;
                    }
                    $is_core = ($group_slug === 'core');
                    ?>
                    <div class="reveal sc-amenity-group">
                        <h3 class="sc-subtitle"><?php echo esc_html(south_city_translate($group_key, $language)); ?></h3>
                        <ul class="<?php echo $is_core ? 'sc-amenity-cards' : 'sc-amenity-chips'; ?>" role="list">
                            <?php while ($group_query->have_posts()) : ?>
                                <?php
                                $group_query->the_post();
                                $icon       = (string) south_city_get_field_or_meta('icon', get_the_ID(), 'check');
                                $amen_photo = south_city_asset_url(south_city_get_field_or_meta('image', get_the_ID()), 'medium');
                                if ($amen_photo === '') {
                                    $amen_photo = south_city_icon_image_url($icon);
                                }
                                $label      = south_city_get_locale_field('label', get_the_ID(), $language);
                                $amen_desc  = south_city_get_locale_field('desc', get_the_ID(), $language);
                                ?>
                                <?php if ($is_core) : ?>
                                    <li class="card sc-amenity-card">
                                        <?php if ($amen_photo !== '') : ?>
                                            <span class="sc-amenity-card__icon sc-amenity-card__icon--photo" aria-hidden="true">
                                                <img src="<?php echo esc_url($amen_photo); ?>" alt="" loading="lazy">
                                            </span>
                                        <?php else : ?>
                                            <span class="sc-amenity-card__icon" aria-hidden="true"><?php echo south_city_inline_icon($icon); ?></span>
                                        <?php endif; ?>
                                        <h4><?php echo esc_html($label); ?></h4>
                                        <?php if ($amen_desc !== '') : ?>
                                            <p><?php echo esc_html($amen_desc); ?></p>
                                        <?php endif; ?>
                                    </li>
                                <?php else : ?>
                                    <li class="sc-amenity-chip">
                                        <span class="sc-amenity-chip__icon" aria-hidden="true"><?php echo south_city_inline_icon($icon); ?></span>
                                        <?php echo esc_html($label); ?>
                                    </li>
                                <?php endif; ?>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php $gallery_query = south_city_ordered_query('southcity_gallery'); ?>
    <?php if ($gallery_query->have_posts()) : ?>
        <?php south_city_dedupe_query_by_field($gallery_query, 'caption', $language); ?>
        <section id="gallery" class="section-pad bg-bg-soft">
            <div class="container-c">
                <div class="reveal mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(south_city_translate('gallery_eyebrow', $language)); ?></p>
                    <h2><?php echo esc_html(south_city_translate('gallery_title', $language)); ?></h2>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">
                    <?php 
                    $seen_gallery_items = [];
                    while ($gallery_query->have_posts()) : 
                        $gallery_query->the_post(); 
                        $post_id       = get_the_ID();
                        $gallery_image = south_city_asset_url(south_city_get_field_or_meta('image', $post_id), 'large');
                        $caption       = south_city_get_locale_field('caption', $post_id, $language);
                        
                        $dedup_key = $gallery_image ?: $caption;
                        if (! $dedup_key || in_array($dedup_key, $seen_gallery_items, true)) {
                            continue;
                        }
                        $seen_gallery_items[] = $dedup_key;
                    ?>
                        <button type="button" class="reveal group relative overflow-hidden rounded-xl border border-line" data-lightbox="<?php echo esc_url($gallery_image); ?>" data-caption="<?php echo esc_attr($caption); ?>" aria-label="<?php echo esc_attr($caption); ?>">
                            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($caption); ?>" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                            <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-navy-deep/85 to-transparent px-3 pb-2.5 pt-8 text-left text-xs font-medium text-white sm:text-sm"><?php echo esc_html($caption); ?></span>
                        </button>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <dialog id="lightbox" class="m-auto w-[min(94vw,1000px)] rounded-xl bg-navy-deep p-0 backdrop:bg-navy-deep/90">
            <button type="button" id="lightbox-close" class="absolute right-3 top-3 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-navy/70 text-white hover:text-gold-light" aria-label="<?php esc_attr_e('Close', 'south-city'); ?>">×</button>
            <img id="lightbox-img" src="" alt="" class="h-auto w-full rounded-t-xl">
            <p id="lightbox-caption" class="px-4 py-3 text-center text-sm text-white/90"></p>
        </dialog>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <section id="contact" class="section-pad bg-navy-deep">
        <div class="container-c max-w-3xl">
            <div class="reveal mb-8 text-center md:mb-12">
                <p class="eyebrow !text-gold-light justify-center"><?php echo esc_html(south_city_translate('contact_eyebrow', $language)); ?></p>
                <h2 class="!text-white"><?php echo esc_html(south_city_translate('contact_title', $language)); ?></h2>
                <p class="mx-auto mt-3 max-w-xl text-white/80"><?php echo esc_html(south_city_translate('contact_subtitle', $language)); ?></p>
            </div>

            <form id="lead-form" class="reveal rounded-2xl bg-white p-5 shadow-card sm:p-8" method="post">
                <input type="checkbox" name="botcheck" class="hidden" style="display:none" tabindex="-1" autocomplete="off">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="lf-name" class="mb-1.5 block text-sm font-semibold text-navy"><?php echo esc_html(south_city_translate('form_name', $language)); ?> <span class="text-gold">*</span></label>
                        <input id="lf-name" name="name" type="text" required autocomplete="name" class="h-12 w-full rounded-md border border-line px-3.5 text-base text-ink focus:border-gold">
                    </div>
                    <div>
                        <label for="lf-phone" class="mb-1.5 block text-sm font-semibold text-navy"><?php echo esc_html(south_city_translate('form_phone', $language)); ?> <span class="text-gold">*</span></label>
                        <input id="lf-phone" name="phone" type="tel" required inputmode="numeric" autocomplete="tel" placeholder="<?php echo esc_attr(south_city_translate('form_phone_hint', $language)); ?>" class="h-12 w-full rounded-md border border-line px-3.5 text-base text-ink focus:border-gold">
                        <p id="lf-phone-err" class="mt-1 hidden text-sm text-red-600"><?php echo esc_html($language === 'bn' ? 'সঠিক বাংলাদেশি মোবাইল নম্বর দিন।' : 'Please enter a valid Bangladeshi mobile number.'); ?></p>
                    </div>
                    <div>
                        <label for="lf-plot" class="mb-1.5 block text-sm font-semibold text-navy"><?php echo esc_html(south_city_translate('form_plot_size', $language)); ?></label>
                        <select id="lf-plot" name="plot_size" class="h-12 w-full rounded-md border border-line bg-white px-3 text-base text-ink focus:border-gold">
                            <option value=""><?php echo esc_html(south_city_translate('form_plot_any', $language)); ?></option>
                            <?php $form_plots_query = south_city_ordered_query('southcity_plot'); ?>
                            <?php while ($form_plots_query->have_posts()) : ?>
                                <?php $form_plots_query->the_post(); ?>
                                <option value="<?php echo esc_attr(south_city_get_locale_field('katha', get_the_ID(), 'en')); ?>"><?php echo esc_html(south_city_get_locale_field('katha', get_the_ID(), $language)); ?></option>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="lf-msg" class="mb-1.5 block text-sm font-semibold text-navy"><?php echo esc_html(south_city_translate('form_message', $language)); ?></label>
                        <input id="lf-msg" name="message" type="text" class="h-12 w-full rounded-md border border-line px-3.5 text-base text-ink focus:border-gold">
                    </div>
                </div>
                <?php if (south_city_turnstile_site_key() !== '') : ?>
                    <div class="cf-turnstile mt-4" data-sitekey="<?php echo esc_attr(south_city_turnstile_site_key()); ?>" data-language="<?php echo esc_attr($language === 'bn' ? 'bn' : 'en'); ?>"></div>
                <?php endif; ?>
                <div class="mt-6">
                    <button type="submit" id="lf-submit" class="btn-gold w-full"><?php echo esc_html(south_city_translate('form_submit', $language)); ?></button>
                </div>
                <p id="lf-error" class="mt-3 hidden text-center text-sm font-medium text-red-600"><?php echo esc_html($language === 'bn' ? 'কিছু একটা সমস্যা হয়েছে। আবার চেষ্টা করুন।' : 'Something went wrong. Please try again.'); ?></p>
                <p id="lf-success" class="mt-3 hidden text-center text-sm font-medium text-emerald-600"><?php echo esc_html($language === 'bn' ? 'ধন্যবাদ! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।' : 'Thank you! We will get back to you shortly.'); ?></p>
            </form>
        </div>
    </section>
</main>

<?php
get_footer();
