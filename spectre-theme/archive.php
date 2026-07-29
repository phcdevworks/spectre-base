<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container>
    <sp-stack>
        <header>
            <sp-text variant="muted"><?php esc_html_e('Archive', 'spectre-base'); ?></sp-text>
            <sp-text level="h1"><?php the_archive_title(); ?></sp-text>
            <?php if (term_description()) : ?>
                <div><?php echo wp_kses_post(term_description()); ?></div>
            <?php endif; ?>
        </header>

        <?php if (have_posts()) : ?>
            <sp-grid columns="2">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'card'); ?>
                <?php endwhile; ?>
            </sp-grid>

            <?php the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Previous', 'spectre-base'),
                'next_text' => __('Next', 'spectre-base'),
            )); ?>
        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </sp-stack>
</sp-container>

<?php get_footer(); ?>
