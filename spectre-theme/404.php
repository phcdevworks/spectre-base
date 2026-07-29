<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container>
    <sp-card padded>
        <sp-stack align="center">
            <sp-text variant="muted">404</sp-text>
            <sp-text level="h1"><?php esc_html_e('Page not found', 'spectre-base'); ?></sp-text>
            <sp-text variant="subtle"><?php esc_html_e('The page you requested could not be found. Try searching or head back to the homepage.', 'spectre-base'); ?></sp-text>

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
