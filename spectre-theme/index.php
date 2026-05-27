<?php get_header(); ?>

<main class='spectre-site-container spectre-main'>
    <?php if (have_posts()) : ?>
        <div class='spectre-post-grid'>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/content', 'card'); ?>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('Previous', 'spectre-base'),
            'next_text' => __('Next', 'spectre-base'),
        )); ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
