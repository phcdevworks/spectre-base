<article id='post-<?php the_ID(); ?>' <?php post_class('spectre-panel'); ?>>
    <header class='spectre-entry-header'>
        <div class='spectre-entry-meta'>
            <time datetime='<?php echo esc_attr(get_the_date('c')); ?>'><?php echo esc_html(get_the_date()); ?></time>
            <span class='spectre-entry-meta__separator'>&bull;</span>
            <span><?php echo esc_html(get_the_author()); ?></span>
        </div>

        <h1 class='spectre-title-xl'><?php the_title(); ?></h1>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <div class='spectre-featured-media'>
            <?php the_post_thumbnail('full'); ?>
        </div>
    <?php endif; ?>

    <div class='spectre-content'>
        <?php the_content(); ?>
        <?php
        wp_link_pages(array(
            'before' => '<nav class="spectre-pagination">' . esc_html__('Pages:', 'spectre-base') . ' ',
            'after' => '</nav>',
        ));
        ?>
    </div>
</article>
