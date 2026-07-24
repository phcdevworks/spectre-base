<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <sp-section>
                <header>
                    <p><?php echo esc_html(get_bloginfo('name')); ?></p>
                    <h1><?php the_title(); ?></h1>
                    <?php if (has_excerpt()) : ?>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>
                </header>

                <sp-card padded>
                    <?php the_content(); ?>
                </sp-card>
            </sp-section>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</sp-container>

<?php get_footer(); ?>
