<?php if (!defined("ABSPATH")) exit; ?>
<section class='spectre-panel spectre-panel--roomy spectre-panel--centered spectre-panel--dashed'>
    <?php if (is_search()) : ?>
        <h2 class='spectre-title-lg'><?php esc_html_e('No search results', 'spectre-base'); ?></h2>
        <p class='spectre-muted'><?php esc_html_e('Try a different search term or browse the site navigation.', 'spectre-base'); ?></p>
    <?php elseif (is_home()) : ?>
        <h2 class='spectre-title-lg'><?php esc_html_e('No posts published yet', 'spectre-base'); ?></h2>
        <p class='spectre-muted'><?php esc_html_e('Publish your first post to populate the journal feed.', 'spectre-base'); ?></p>
    <?php else : ?>
        <h2 class='spectre-title-lg'><?php esc_html_e('Nothing found', 'spectre-base'); ?></h2>
        <p class='spectre-muted'><?php esc_html_e('There is no content matching this request yet.', 'spectre-base'); ?></p>
    <?php endif; ?>

    <div class='spectre-search-region'>
        <?php get_search_form(); ?>
    </div>
</section>
