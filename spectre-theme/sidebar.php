<?php
if (!defined("ABSPATH")) exit;

$spectre_base_sidebar_id = apply_filters('spectre_base_sidebar_id', 'sidebar-main');

if (!is_active_sidebar($spectre_base_sidebar_id)) {
    return;
}
?>

<?php do_action('spectre_base_before_sidebar', $spectre_base_sidebar_id); ?>
<aside class='spectre-sidebar' role='complementary' aria-label='<?php esc_attr_e('Sidebar', 'spectre-base'); ?>'>
    <?php dynamic_sidebar($spectre_base_sidebar_id); ?>
</aside>
<?php do_action('spectre_base_after_sidebar', $spectre_base_sidebar_id); ?>
