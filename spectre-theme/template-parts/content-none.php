<?php if (!defined("ABSPATH")) exit; ?>
<sp-section>
    <sp-stack align="center">
        <?php if (is_search()) : ?>
            <sp-text level="h2"><?php esc_html_e('No search results', 'spectre-base'); ?></sp-text>
            <sp-text variant="subtle"><?php esc_html_e('Try a different search term or browse the site navigation.', 'spectre-base'); ?></sp-text>
        <?php elseif (is_home()) : ?>
            <sp-text level="h2"><?php esc_html_e('No posts published yet', 'spectre-base'); ?></sp-text>
            <sp-text variant="subtle"><?php esc_html_e('Publish your first post to populate the journal feed.', 'spectre-base'); ?></sp-text>
        <?php else : ?>
            <sp-text level="h2"><?php esc_html_e('Nothing found', 'spectre-base'); ?></sp-text>
            <sp-text variant="subtle"><?php esc_html_e('There is no content matching this request yet.', 'spectre-base'); ?></sp-text>
        <?php endif; ?>

        <div>
            <?php get_search_form(); ?>
        </div>
    </sp-stack>
</sp-section>
