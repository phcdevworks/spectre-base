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
    <sp-container>
        <?php // align="stretch" avoids sp-stack's reflected align="center", which browsers read as legacy text-align: center. ?>
        <sp-stack direction="horizontal" align="stretch" gap="md" inner-class="sp-items-center sp-justify-between sp-flex-wrap">
            <div class="site-branding">
                <?php do_action('spectre_base_before_site_branding'); ?>
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a class="sp-nav__link sp-heading--h6" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php echo esc_html(get_bloginfo('name')); ?>
                    </a>
                <?php endif; ?>
                <?php do_action('spectre_base_after_site_branding'); ?>
            </div>

            <div class="main-navigation">
                <?php
                wp_nav_menu(apply_filters('spectre_base_primary_nav_args', array(
                    'theme_location'     => 'primary',
                    'container'          => false,
                    'depth'              => 1,
                    'items_wrap'         => '<ul class="sp-nav__links sp-mx-0 sp-flex-wrap">%3$s</ul>',
                    'spectre_link_class' => 'sp-nav__link',
                    'fallback_cb'        => 'spectre_base_primary_menu_fallback',
                )));
                ?>
            </div>
        </sp-stack>
    </sp-container>
</sp-nav>
<?php do_action('spectre_base_after_header'); ?>
