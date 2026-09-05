<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<main id="spectre-main-content" tabindex="-1">
<sp-container>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <sp-section>
                <header>
                    <sp-text variant="muted"><?php echo esc_html(get_bloginfo('name')); ?></sp-text>
                    <sp-text level="h1"><?php the_title(); ?></sp-text>
                    <?php if (has_excerpt()) : ?>
                        <sp-text variant="subtle"><?php echo esc_html(get_the_excerpt()); ?></sp-text>
                    <?php endif; ?>
                </header>

                <sp-card padded>
                    <div class="sp-prose sp-content-flow">
                        <?php the_content(); ?>
                    </div>
                </sp-card>
            </sp-section>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</sp-container>
</main>

<?php get_footer(); ?>
