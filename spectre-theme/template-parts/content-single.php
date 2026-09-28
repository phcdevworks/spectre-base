<?php if (!defined("ABSPATH")) exit; ?>
<article id='post-<?php the_ID(); ?>' <?php post_class(); ?>>
    <div class="sp-content-flow">
        <header>
            <sp-text variant="meta" size="sm">
                <time datetime='<?php echo esc_attr(get_the_date('c')); ?>'><?php echo esc_html(get_the_date()); ?></time>
                <span>&bull;</span>
                <span><?php echo esc_html(get_the_author()); ?></span>
            </sp-text>

            <sp-text level="h1" preset="heading"><?php the_title(); ?></sp-text>
        </header>

        <?php if (has_post_thumbnail()) : ?>
            <div>
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>

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
