<?php if (!defined("ABSPATH")) exit; ?>
<sp-section>
    <sp-stack align="center">
        <?php if (is_search()) : ?>
            <h2><?php esc_html_e('No search results', 'spectre-base'); ?></h2>
            <p><?php esc_html_e('Try a different search term or browse the site navigation.', 'spectre-base'); ?></p>
        <?php elseif (is_home()) : ?>
            <h2><?php esc_html_e('No posts published yet', 'spectre-base'); ?></h2>
            <p><?php esc_html_e('Publish your first post to populate the journal feed.', 'spectre-base'); ?></p>
        <?php else : ?>
            <h2><?php esc_html_e('Nothing found', 'spectre-base'); ?></h2>
            <p><?php esc_html_e('There is no content matching this request yet.', 'spectre-base'); ?></p>
        <?php endif; ?>

        <div>
            <?php get_search_form(); ?>
        </div>
    </sp-stack>
</sp-section>
