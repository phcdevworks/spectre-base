<footer class="site-footer spectre-site-footer">
    <div class="spectre-site-container spectre-site-footer__inner">
        <?php if (has_nav_menu('footer')) : ?>
            <nav aria-label="<?php esc_attr_e('Footer Navigation', 'spectre-base'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'spectre-footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                ));
                ?>
            </nav>
        <?php endif; ?>

        <?php
        // Filter: spectre_base_footer_social_icons — each entry: ['name', 'size' (default '20'), 'url' (optional)]
        $social_icons = apply_filters('spectre_base_footer_social_icons', array());
        ?>
        <?php if (spectre_base_has_icons() && !empty($social_icons)) : ?>
            <div class="spectre-site-footer__social" aria-label="<?php esc_attr_e('Social links', 'spectre-base'); ?>">
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
            </div>
        <?php endif; ?>

        <p class="spectre-site-footer__meta">&copy; <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'spectre-base'); ?></p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
