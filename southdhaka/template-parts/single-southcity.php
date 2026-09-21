<?php
/**
 * Shared single template for South City custom post types.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

$language  = south_city_current_language();
$post_type = get_post_type();
$labels    = [
    'southcity_plot'     => __('Plot Details', 'south-city'),
    'southcity_badge'    => __('Trust Badge', 'south-city'),
    'southcity_fact'     => __('Project Fact', 'south-city'),
    'southcity_amenity'  => __('Amenity', 'south-city'),
    'southcity_landmark' => __('Neighborhood', 'south-city'),
    'southcity_gallery'  => __('Gallery', 'south-city'),
];

get_header();
?>

<main id="main" class="section-pad bg-white">
    <div class="container-c max-w-5xl">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            <article <?php post_class('reveal'); ?>>
                <header class="mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html($labels[$post_type] ?? get_post_type_object($post_type)->labels->singular_name); ?></p>
                    <h1><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-8 overflow-hidden rounded-xl border border-line shadow-card">
                        <?php the_post_thumbnail('large', ['class' => 'h-auto w-full']); ?>
                    </div>
                <?php endif; ?>

                <?php if ($post_type === 'southcity_plot') : ?>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <?php
                        $plot_fields = [
                            'katha'       => __('Size', 'south-city'),
                            'zone'        => __('Zone', 'south-city'),
                            'price'       => __('Price', 'south-city'),
                            'booking'     => __('Booking Money', 'south-city'),
                            'installment' => __('Installment Note', 'south-city'),
                        ];
                        ?>
                        <?php foreach ($plot_fields as $field_name => $field_label) : ?>
                            <?php $field_value = south_city_get_locale_field($field_name, get_the_ID(), $language); ?>
                            <?php if ($field_value === '') : ?>
                                <?php continue; ?>
                            <?php endif; ?>
                            <div class="rounded-lg bg-bg-soft p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted"><?php echo esc_html($field_label); ?></p>
                                <p class="mt-1 font-display text-xl font-bold text-navy"><?php echo esc_html($field_value); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($post_type === 'southcity_landmark') : ?>
                    <?php $items = (array) south_city_get_field_or_meta('items', get_the_ID(), []); ?>
                    <?php if (! empty($items)) : ?>
                        <ol class="divide-y divide-line rounded-xl border border-line bg-white px-5">
                            <?php foreach ($items as $index => $item) : ?>
                                <?php $item = (array) $item; ?>
                                <li class="flex items-baseline gap-4 py-4">
                                    <span class="font-display text-sm font-bold text-gold" aria-hidden="true"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                                    <div>
                                        <p class="font-semibold text-navy"><?php echo esc_html(south_city_get_locale_row_value($item, 'name', $language)); ?></p>
                                        <p class="text-sm text-muted"><?php echo esc_html(south_city_get_locale_row_value($item, 'note', $language)); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                <?php elseif ($post_type === 'southcity_gallery') : ?>
                    <?php
                    $gallery_image = south_city_asset_url(south_city_get_field_or_meta('image', get_the_ID()), 'large');
                    $caption       = south_city_get_locale_field('caption', get_the_ID(), $language);
                    ?>
                    <?php if ($gallery_image !== '') : ?>
                        <figure class="overflow-hidden rounded-xl border border-line shadow-card">
                            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($caption); ?>" class="h-auto w-full">
                            <?php if ($caption !== '') : ?>
                                <figcaption class="bg-bg-soft px-4 py-3 text-center text-sm text-muted"><?php echo esc_html($caption); ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>
                <?php else : ?>
                    <?php
                    $label = south_city_get_locale_field('label', get_the_ID(), $language);
                    $value = south_city_get_locale_field('value', get_the_ID(), $language);
                    $icon  = (string) south_city_get_field_or_meta('icon', get_the_ID(), '');
                    ?>
                    <div class="rounded-xl border border-line bg-bg-soft p-6">
                        <?php if ($icon !== '') : ?>
                            <p class="text-sm font-semibold uppercase tracking-wide text-gold"><?php echo esc_html($icon); ?></p>
                        <?php endif; ?>
                        <?php if ($label !== '') : ?>
                            <h2 class="mt-2 text-2xl font-bold text-navy"><?php echo esc_html($label); ?></h2>
                        <?php endif; ?>
                        <?php if ($value !== '') : ?>
                            <p class="mt-3 text-lg text-muted"><?php echo esc_html($value); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="prose prose-lg mt-8 max-w-none">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
