<?php if (!defined("ABSPATH")) exit; ?>
<article id='post-<?php the_ID(); ?>' <?php post_class('spectre-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a href='<?php echo esc_url(get_permalink()); ?>' class='spectre-card__media'>
            <?php the_post_thumbnail('large'); ?>
        </a>
    <?php endif; ?>

    <div class='spectre-card__body'>
        <div class='spectre-entry-meta'>
            <time datetime='<?php echo esc_attr(get_the_date('c')); ?>'><?php echo esc_html(get_the_date()); ?></time>
            <span class='spectre-entry-meta__separator'>&bull;</span>
            <span><?php echo esc_html(get_the_author()); ?></span>
        </div>

        <h2 class='spectre-card__title'>
            <a href='<?php echo esc_url(get_permalink()); ?>'>
                <?php the_title(); ?>
            </a>
        </h2>

        <div class='spectre-card__excerpt'>
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php echo esc_url(get_permalink()); ?>"
           class="spectre-card__readmore"
           aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'spectre-base'), get_the_title())); ?>">
            <?php esc_html_e('Read more', 'spectre-base'); ?>
        </a>
    </div>
</article>
