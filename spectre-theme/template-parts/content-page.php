<?php if (!defined("ABSPATH")) exit; ?>
<article id='post-<?php the_ID(); ?>' <?php post_class(); ?>>
    <div class="sp-content-flow">
        <header>
            <sp-text level="h1" preset="heading"><?php the_title(); ?></sp-text>
        </header>

        <div class="sp-prose sp-content-flow">
            <?php the_content(); ?>
            <?php
            wp_link_pages(array(
                'before' => '<nav>' . esc_html__('Pages:', 'spectre-base') . ' ',
                'after' => '</nav>',
            ));
            ?>
        </div>
    </div>
</article>
