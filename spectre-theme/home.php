<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<main id="spectre-main-content" tabindex="-1">
<sp-container inner-class="sp-container--max-width-wide">
    <sp-stack>
        <header>
            <sp-text variant="muted"><?php esc_html_e('Journal', 'spectre-base'); ?></sp-text>
            <sp-text level="h1"><?php echo esc_html(single_post_title('', false)); ?></sp-text>
            <sp-text variant="subtle"><?php esc_html_e('The latest posts from this site.', 'spectre-base'); ?></sp-text>
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
</main>

<?php get_footer(); ?>
