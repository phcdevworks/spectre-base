<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container>
    <sp-card padded>
        <sp-stack align="center">
            <p>404</p>
            <h1><?php esc_html_e('Page not found', 'spectre-base'); ?></h1>
            <p><?php esc_html_e('The page you requested could not be found. Try searching or head back to the homepage.', 'spectre-base'); ?></p>

            <div>
                <?php get_search_form(); ?>
            </div>

            <p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="sp-btn sp-btn--primary sp-btn--md">
                    <?php esc_html_e('Back to home', 'spectre-base'); ?>
                </a>
            </p>
        </sp-stack>
    </sp-card>
</sp-container>

<?php get_footer(); ?>
