<?php if (!defined("ABSPATH")) exit; ?>
<form role="search" method="get" class="spectre-search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="sr-only" for="spectre-search-field"><?php esc_html_e('Search for:', 'spectre-base'); ?></label>
    <sp-input
        id="spectre-search-field"
        type="search"
        name="s"
        placeholder="<?php echo esc_attr_x('Search the site', 'placeholder', 'spectre-base'); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
    ></sp-input>
    <sp-button type="submit" variant="primary"><?php esc_html_e('Search', 'spectre-base'); ?></sp-button>
</form>
