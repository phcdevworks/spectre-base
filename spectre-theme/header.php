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
<sp-nav bordered full-width sticky aria-label="<?php echo esc_attr__('Site header', 'spectre-base'); ?>">
    <sp-container>
        <sp-stack direction="horizontal" align="center">
            <div class="site-branding">
                <?php do_action('spectre_base_before_site_branding'); ?>
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <h1>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <?php echo esc_html(get_bloginfo('name')); ?>
                        </a>
                    </h1>
                <?php endif; ?>
                <?php do_action('spectre_base_after_site_branding'); ?>
            </div>

            <nav class="main-navigation" aria-label="<?php echo esc_attr__('Primary', 'spectre-base'); ?>">
                <sp-stack direction="horizontal">
                    <?php
                    wp_nav_menu(apply_filters('spectre_base_primary_nav_args', array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'fallback_cb' => 'spectre_base_primary_menu_fallback',
                    )));
                    ?>
                </sp-stack>
            </nav>
        </sp-stack>
    </sp-container>
</sp-nav>
<?php do_action('spectre_base_after_header'); ?>
