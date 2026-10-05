<?php if (!defined("ABSPATH")) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="sp-h-full">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="spectre-skip-link sp-btn sp-btn--primary sp-btn--md" href="#spectre-main-content">
    <?php esc_html_e('Skip to main content', 'spectre-base'); ?>
</a>

<?php do_action('spectre_base_before_header'); ?>
<sp-nav
    full-width
    bordered
    sticky
    accent="<?php echo esc_attr(apply_filters('spectre_base_header_accent', 'top')); ?>"
    accent-color="<?php echo esc_attr(apply_filters('spectre_base_header_accent_color', 'brand')); ?>"
    inner-class="sp-flex-col sp-items-stretch"
    aria-label="<?php echo esc_attr__('Primary', 'spectre-base'); ?>"
>
    <?php $container_class = spectre_base_header_container_class(); ?>
    <sp-container<?php echo $container_class !== '' ? ' inner-class="' . esc_attr($container_class) . '"' : ''; ?>>
        <?php if ('edge-fluid-edge' === spectre_base_header_layout()) : ?>
            <sp-grid gap="md" inner-class="sp-grid-template--edge-fluid-edge sp-items-center">
                <?php spectre_base_site_branding(); ?>
                <?php spectre_base_primary_nav(); ?>
                <div class="header-actions">
                    <?php do_action('spectre_base_header_actions'); ?>
                </div>
            </sp-grid>
        <?php else : ?>
            <sp-stack direction="horizontal" align="stretch" gap="md" inner-class="sp-items-center sp-justify-between sp-flex-wrap">
                <?php spectre_base_site_branding(); ?>
                <?php spectre_base_primary_nav(); ?>
                <?php if (has_action('spectre_base_header_actions')) : ?>
                    <div class="header-actions">
                        <?php do_action('spectre_base_header_actions'); ?>
                    </div>
                <?php endif; ?>
            </sp-stack>
        <?php endif; ?>
    </sp-container>
</sp-nav>
<?php do_action('spectre_base_after_header'); ?>
