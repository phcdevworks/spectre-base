<?php if (!defined("ABSPATH")) exit; ?>
<div id='post-<?php the_ID(); ?>' <?php post_class(); ?>>
    <sp-stack>
        <header>
            <sp-text level="h1"><?php the_title(); ?></sp-text>
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
    </sp-stack>
</div>
