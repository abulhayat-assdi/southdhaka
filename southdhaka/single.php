<?php
/**
 * Default single post template.
 *
 * @package SouthCity
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="main" class="section-pad bg-white">
    <div class="container-c max-w-4xl">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            <article <?php post_class('reveal'); ?>>
                <header class="mb-8 md:mb-12">
                    <p class="eyebrow"><?php echo esc_html(get_the_date()); ?></p>
                    <h1><?php the_title(); ?></h1>
                    <div class="mt-3 text-sm text-muted">
                        <?php
                        printf(
                            esc_html__('By %s', 'south-city'),
                            esc_html(get_the_author())
                        );
                        ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="mb-8 overflow-hidden rounded-xl border border-line shadow-card">
                        <?php the_post_thumbnail('large', ['class' => 'h-auto w-full']); ?>
                    </div>
                <?php endif; ?>

                <div class="prose prose-lg max-w-none">
                    <?php the_content(); ?>
                </div>

                <?php
                wp_link_pages([
                    'before' => '<div class="mt-8 flex gap-2 font-display text-sm font-semibold text-navy">' . __('Pages:', 'south-city'),
                    'after'  => '</div>',
                ]);
                ?>
            </article>

            <nav class="mt-12 grid gap-4 border-t border-line pt-8 sm:grid-cols-2" aria-label="<?php esc_attr_e('Post navigation', 'south-city'); ?>">
                <div><?php previous_post_link('%link', __('Previous Post', 'south-city')); ?></div>
                <div class="text-right"><?php next_post_link('%link', __('Next Post', 'south-city')); ?></div>
            </nav>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
