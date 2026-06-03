<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<main class='spectre-site-container spectre-main'>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <section class='spectre-section'>
                <header class='spectre-archive-header'>
                    <p class='spectre-eyebrow'><?php echo esc_html(get_bloginfo('name')); ?></p>
                    <h1 class='spectre-title-2xl'><?php the_title(); ?></h1>
                    <?php if (has_excerpt()) : ?>
                        <p class='spectre-muted'><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>
                </header>

                <div class='spectre-panel spectre-content'>
                    <?php the_content(); ?>
                </div>
            </section>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
