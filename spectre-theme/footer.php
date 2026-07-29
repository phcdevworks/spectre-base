<?php if (!defined("ABSPATH")) exit; ?>
<?php do_action('spectre_base_before_footer'); ?>
<sp-footer full-width aria-label="<?php echo esc_attr__('Site footer', 'spectre-base'); ?>">
    <sp-container>
        <sp-stack>
            <?php if (has_nav_menu('footer')) : ?>
                <nav aria-label="<?php esc_attr_e('Footer Navigation', 'spectre-base'); ?>">
                    <sp-stack direction="horizontal">
                        <?php
                        wp_nav_menu(apply_filters('spectre_base_footer_nav_args', array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'depth'          => 1,
                        )));
                        ?>
                    </sp-stack>
                </nav>
            <?php endif; ?>

            <?php
            // Filter: spectre_base_footer_social_icons — each entry: ['name', 'size' (default '20'), 'url' (optional)]
            $social_icons = apply_filters('spectre_base_footer_social_icons', array());
            ?>
            <?php if (spectre_base_has_icons() && !empty($social_icons)) : ?>
                <sp-stack direction="horizontal" aria-label="<?php esc_attr_e('Social links', 'spectre-base'); ?>">
                    <?php foreach ($social_icons as $icon) :
                        $icon_name = isset($icon['name']) ? $icon['name'] : '';
                        $icon_size = isset($icon['size']) ? $icon['size'] : '20';
                        $icon_url  = isset($icon['url'])  ? $icon['url']  : '';
                        if (empty($icon_name)) continue;
                        $shortcode = do_shortcode('[spectre-icon name="' . esc_attr($icon_name) . '" size="' . esc_attr($icon_size) . '"]');
                        if ($icon_url) :
                    ?>
                        <a href="<?php echo esc_url($icon_url); ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo $shortcode; ?>
                        </a>
                    <?php else :
                        echo $shortcode;
                    endif;
                    endforeach; ?>
                </sp-stack>
            <?php endif; ?>

            <sp-text variant="muted" size="sm">&copy; <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'spectre-base'); ?></sp-text>
        </sp-stack>
    </sp-container>
</sp-footer>
<?php do_action('spectre_base_after_footer'); ?>

<?php wp_footer(); ?>
</body>
</html>
