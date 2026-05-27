<article id='post-<?php the_ID(); ?>' <?php post_class('spectre-panel'); ?>>
    <header class='spectre-entry-header'>
        <h1 class='spectre-title-xl'><?php the_title(); ?></h1>
    </header>

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
