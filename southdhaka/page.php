<?php
/**
 * Default page template.
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
                    <p class="eyebrow"><?php echo esc_html(get_bloginfo('name')); ?></p>
                    <h1><?php the_title(); ?></h1>
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
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
