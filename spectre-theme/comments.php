<?php if (post_password_required()) : ?>
    <?php return; ?>
<?php endif; ?>

<section id="comments" class="spectre-panel spectre-comments">
    <?php if (have_comments()) : ?>
        <header>
            <h2 class="spectre-title-lg">
                <?php
                printf(
                    esc_html(
                        _n('%s comment', '%s comments', get_comments_number(), 'spectre-base')
                    ),
                    esc_html(number_format_i18n(get_comments_number()))
                );
                ?>
            </h2>
        </header>

        <ol class="spectre-comments__list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 48,
            ));
            ?>
        </ol>

        <?php the_comments_pagination(array(
            'prev_text' => esc_html__('Previous comments', 'spectre-base'),
            'next_text' => esc_html__('Next comments', 'spectre-base'),
        )); ?>
    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number()) : ?>
        <p class="spectre-muted"><?php esc_html_e('Comments are closed.', 'spectre-base'); ?></p>
    <?php endif; ?>

    <div class="spectre-comments__form">
        <?php comment_form(); ?>
    </div>
</section>
