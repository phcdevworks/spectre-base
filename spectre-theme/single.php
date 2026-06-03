<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<main class='spectre-site-container spectre-main'>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content', 'single'); ?>

            <?php if (comments_open() || get_comments_number()) : ?>
                <?php comments_template(); ?>
            <?php endif; ?>

            <nav class='spectre-panel spectre-post-navigation'>
                <div><?php previous_post_link('%link', '&larr; %title'); ?></div>
                <div><?php next_post_link('%link', '%title &rarr;'); ?></div>
            </nav>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
