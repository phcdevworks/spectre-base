<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<main id="spectre-main-content" tabindex="-1">
<?php do_action('spectre_base_main_start'); ?>
<sp-container inner-class="sp-py-32">
    <sp-stack align="stretch">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/content', 'single'); ?>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>

                <?php if (get_previous_post() || get_next_post()) : ?>
                <nav aria-label="<?php esc_attr_e('Post navigation', 'spectre-base'); ?>">
                    <sp-stack direction="horizontal" align="stretch" inner-class="sp-justify-between sp-flex-wrap">
                        <div><?php previous_post_link('%link', '&larr; %title'); ?></div>
                        <div><?php next_post_link('%link', '%title &rarr;'); ?></div>
                    </sp-stack>
                </nav>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </sp-stack>
</sp-container>
</main>

<?php get_footer(); ?>
