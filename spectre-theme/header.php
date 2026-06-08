<?php if (!defined("ABSPATH")) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php do_action('spectre_base_before_header'); ?>
<header class="site-header spectre-site-header">
    <div class="spectre-site-container spectre-site-header__inner">
        <div class="site-branding spectre-site-branding">
            <?php do_action('spectre_base_before_site_branding'); ?>
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <h1 class="spectre-site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="spectre-inverse-link">
                        <?php echo esc_html(get_bloginfo('name')); ?>
                    </a>
                </h1>
            <?php endif; ?>
            <?php do_action('spectre_base_after_site_branding'); ?>
        </div>

        <nav class="main-navigation">
            <?php
            wp_nav_menu(apply_filters('spectre_base_primary_nav_args', array(
                'theme_location' => 'primary',
                'menu_class' => 'spectre-navigation-menu',
                'container' => false,
                'fallback_cb' => 'spectre_base_primary_menu_fallback',
            )));
            ?>
        </nav>
    </div>
</header>
<?php do_action('spectre_base_after_header'); ?>
