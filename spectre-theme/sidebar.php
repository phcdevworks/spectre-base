<?php
if (!defined("ABSPATH")) exit;

if (!is_active_sidebar('sidebar-main')) {
    return;
}
?>

<aside class='spectre-sidebar' role='complementary' aria-label='<?php esc_attr_e('Sidebar', 'spectre-base'); ?>'>
    <?php dynamic_sidebar('sidebar-main'); ?>
</aside>
