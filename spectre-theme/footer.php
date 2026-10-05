<?php if (!defined("ABSPATH")) exit; ?>
<?php do_action('spectre_base_before_footer'); ?>
<?php
$footer_menus = array_filter(spectre_base_footer_menu_locations(), 'has_nav_menu', ARRAY_FILTER_USE_KEY);
?>
<sp-footer
    class="sp-mt-auto"
    full-width
    bordered
    <?php echo spectre_base_footer_surface_attributes(); ?>
    accent="<?php echo esc_attr(apply_filters('spectre_base_footer_accent', 'top')); ?>"
    accent-color="<?php echo esc_attr(apply_filters('spectre_base_footer_accent_color', 'brand')); ?>"
    inner-class="sp-py-64 sp-flex-col sp-items-stretch"
    aria-label="<?php echo esc_attr__('Site footer', 'spectre-base'); ?>"
>
    <sp-container>
        <sp-stack gap="lg" align="stretch">
            <sp-grid columns="<?php echo $footer_menus ? '3' : '1'; ?>" gap="lg">
                <sp-stack class="spectre-footer-column" gap="sm" align="stretch">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <a class="sp-footer__heading" href="<?php echo esc_url(home_url('/')); ?>">
                            <?php echo esc_html(get_bloginfo('name')); ?>
                        </a>
                    <?php endif; ?>

                    <?php $tagline = get_bloginfo('description'); ?>
                    <?php if ($tagline) : ?>
                        <p class="sp-footer__text"><?php echo esc_html($tagline); ?></p>
                    <?php endif; ?>

                    <?php spectre_base_footer_social_icons(); ?>
                    <?php spectre_base_footer_contact_info(); ?>
                </sp-stack>

                <?php if ($footer_menus) : ?>
                    <?php // The span class sits on the host: it is the outer grid's item, and sp-grid's own `span` lands on its inner element. ?>
                    <sp-grid class="sp-lg-col-span-2" columns="<?php echo esc_attr(count($footer_menus)); ?>" gap="lg">
                        <?php foreach ($footer_menus as $location => $footer_menu) : ?>
                            <?php spectre_base_footer_nav_column($location, apply_filters('spectre_base_footer_nav_heading', spectre_base_footer_menu_name($location), $location), $footer_menu['args_filter']); ?>
                        <?php endforeach; ?>
                    </sp-grid>
                <?php endif; ?>
            </sp-grid>

            <hr class="sp-footer__divider">

            <sp-stack direction="horizontal" align="stretch" gap="sm" inner-class="sp-justify-between sp-flex-wrap">
                <span class="sp-footer__muted">&copy; <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'spectre-base'); ?></span>
                <?php spectre_base_footer_legal_links(); ?>
            </sp-stack>
        </sp-stack>
    </sp-container>
</sp-footer>
<?php do_action('spectre_base_after_footer'); ?>

<?php wp_footer(); ?>
</body>
</html>
