<?php
/**
 * Shared archive template for South City custom post types.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

$language  = south_city_current_language();
$post_type = get_post_type() ?: get_query_var('post_type');
$labels    = [
    'southcity_plot'     => [
        'eyebrow' => __('Plot Sizes & Pricing', 'south-city'),
        'title'   => __('Choose Your Plot Size', 'south-city'),
    ],
    'southcity_badge'    => [
        'eyebrow' => __('Trust Badges', 'south-city'),
        'title'   => __('Buy With Confidence', 'south-city'),
    ],
    'southcity_fact'     => [
        'eyebrow' => __('Project Facts', 'south-city'),
        'title'   => __('Project at a Glance', 'south-city'),
    ],
    'southcity_amenity'  => [
        'eyebrow' => __('Amenities & Facilities', 'south-city'),
        'title'   => __('Designed for Family Living', 'south-city'),
    ],
    'southcity_landmark' => [
        'eyebrow' => __('Neighborhood', 'south-city'),
        'title'   => __('Everything You Need, Nearby', 'south-city'),
    ],
    'southcity_gallery'  => [
        'eyebrow' => __('Gallery', 'south-city'),
        'title'   => __('Master Plan & Project Renders', 'south-city'),
    ],
];

get_header();
?>

<main id="main" class="section-pad bg-bg-soft">
    <div class="container-c">
        <header class="reveal mb-8 md:mb-12">
            <p class="eyebrow"><?php echo esc_html($labels[$post_type]['eyebrow'] ?? __('Archive', 'south-city')); ?></p>
            <h1><?php echo esc_html($labels[$post_type]['title'] ?? post_type_archive_title('', false)); ?></h1>
        </header>

        <?php if (have_posts()) : ?>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <article <?php post_class('reveal card overflow-hidden bg-white'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="block aspect-[4/3] overflow-hidden">
                                <?php the_post_thumbnail('large', ['class' => 'h-full w-full object-cover transition-transform duration-300 hover:scale-105']); ?>
                            </a>
                        <?php endif; ?>

                        <div class="p-5">
                            <h2 class="text-xl font-bold text-navy">
                                <a href="<?php the_permalink(); ?>" class="hover:text-gold"><?php the_title(); ?></a>
                            </h2>

                            <?php if ($post_type === 'southcity_plot') : ?>
                                <dl class="mt-4 space-y-2 text-sm">
                                    <div class="flex justify-between gap-4">
                                        <dt class="font-semibold text-muted"><?php esc_html_e('Area', 'south-city'); ?></dt>
                                        <dd class="text-right font-medium text-navy"><?php echo esc_html(south_city_get_locale_field('sqft', get_the_ID(), $language)); ?></dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="font-semibold text-muted"><?php esc_html_e('Price', 'south-city'); ?></dt>
                                        <dd class="text-right font-medium text-gold"><?php echo esc_html(south_city_get_locale_field('price', get_the_ID(), $language) ?: south_city_translate('call_for_price', $language)); ?></dd>
                                    </div>
                                </dl>
                            <?php elseif ($post_type === 'southcity_gallery') : ?>
                                <p class="mt-3 text-sm text-muted"><?php echo esc_html(south_city_get_locale_field('caption', get_the_ID(), $language)); ?></p>
                            <?php elseif ($post_type === 'southcity_fact') : ?>
                                <p class="mt-3 text-sm text-muted"><?php echo esc_html(south_city_get_locale_field('label', get_the_ID(), $language)); ?></p>
                                <p class="mt-1 font-display text-lg font-bold text-navy"><?php echo esc_html(south_city_get_locale_field('value', get_the_ID(), $language)); ?></p>
                            <?php else : ?>
                                <div class="mt-3 text-sm leading-relaxed text-muted">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>

                            <a href="<?php the_permalink(); ?>" class="mt-4 inline-flex font-display text-sm font-semibold text-gold hover:text-navy">
                                <?php esc_html_e('View details', 'south-city'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="mt-10">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => __('Previous', 'south-city'),
                    'next_text' => __('Next', 'south-city'),
                ]);
                ?>
            </div>
        <?php else : ?>
            <div class="reveal rounded-xl border border-line bg-white p-8 text-center">
                <h2 class="text-2xl font-bold text-navy"><?php esc_html_e('Nothing found', 'south-city'); ?></h2>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
