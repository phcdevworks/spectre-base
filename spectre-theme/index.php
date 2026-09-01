<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container inner-class="sp-container--max-width-wide">
    <sp-stack>
        <?php if (have_posts()) : ?>
            <sp-grid columns="2">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/content', 'card'); ?>
                <?php endwhile; ?>
            </sp-grid>

            <?php the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'spectre-base'),
                'next_text' => __('Next', 'spectre-base'),
            )); ?>
        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </sp-stack>
</sp-container>

<?php get_footer(); ?>
