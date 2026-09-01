<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container inner-class="sp-container--max-width-wide">
    <sp-stack>
        <header>
            <sp-text variant="muted"><?php esc_html_e('Search', 'spectre-base'); ?></sp-text>
            <sp-text level="h1">
                <?php printf(esc_html__('Results for: %s', 'spectre-base'), esc_html(get_search_query())); ?>
            </sp-text>
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
