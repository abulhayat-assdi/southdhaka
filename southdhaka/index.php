<?php
/**
 * Fallback template and blog archive.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="main" class="section-pad bg-bg-soft">
    <div class="container-c">
        <?php if (is_home() && ! is_front_page()) : ?>
            <div class="reveal mb-8 md:mb-12">
                <p class="eyebrow"><?php esc_html_e('Updates', 'south-city'); ?></p>
                <h1><?php single_post_title(); ?></h1>
            </div>
        <?php elseif (is_archive()) : ?>
            <div class="reveal mb-8 md:mb-12">
                <p class="eyebrow"><?php esc_html_e('Archive', 'south-city'); ?></p>
                <h1><?php the_archive_title(); ?></h1>
                <?php the_archive_description('<div class="mt-3 max-w-2xl text-muted">', '</div>'); ?>
            </div>
        <?php else : ?>
            <div class="reveal mb-8 md:mb-12">
                <p class="eyebrow"><?php esc_html_e('Latest', 'south-city'); ?></p>
                <h1><?php esc_html_e('Latest Updates', 'south-city'); ?></h1>
            </div>
        <?php endif; ?>

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
                            <div class="mt-3 text-sm leading-relaxed text-muted">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="mt-4 inline-flex font-display text-sm font-semibold text-gold hover:text-navy">
                                <?php esc_html_e('Read more', 'south-city'); ?>
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
                <p class="mt-2 text-muted"><?php esc_html_e('Please check back soon.', 'south-city'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
