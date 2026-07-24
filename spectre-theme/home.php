<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container>
    <sp-stack>
        <header>
            <p><?php esc_html_e('Journal', 'spectre-base'); ?></p>
            <h1><?php echo esc_html(single_post_title('', false)); ?></h1>
            <p><?php esc_html_e('The latest posts from this site.', 'spectre-base'); ?></p>
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
