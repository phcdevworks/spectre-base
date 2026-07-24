<?php if (!defined("ABSPATH")) exit; ?>
<div id='post-<?php the_ID(); ?>' <?php post_class(); ?>>
    <sp-stack>
        <header>
            <h1><?php the_title(); ?></h1>
        </header>

        <div>
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
