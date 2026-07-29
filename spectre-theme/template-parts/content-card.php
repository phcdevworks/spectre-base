<?php if (!defined("ABSPATH")) exit; ?>
<sp-card id='post-<?php the_ID(); ?>' <?php post_class(); ?> padded>
    <sp-stack>
        <?php if (has_post_thumbnail()) : ?>
            <a href='<?php echo esc_url(get_permalink()); ?>'>
                <?php the_post_thumbnail('large'); ?>
            </a>
        <?php endif; ?>

        <sp-text variant="meta" size="sm">
            <time datetime='<?php echo esc_attr(get_the_date('c')); ?>'><?php echo esc_html(get_the_date()); ?></time>
            <span>&bull;</span>
            <span><?php echo esc_html(get_the_author()); ?></span>
        </sp-text>

        <sp-text level="h2">
            <a href='<?php echo esc_url(get_permalink()); ?>'>
                <?php the_title(); ?>
            </a>
        </sp-text>

        <div>
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php echo esc_url(get_permalink()); ?>"
           class="sp-btn sp-btn--ghost sp-btn--sm"
           aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'spectre-base'), get_the_title())); ?>">
            <?php esc_html_e('Read more', 'spectre-base'); ?>
        </a>
    </sp-stack>
</sp-card>
