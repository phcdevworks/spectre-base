<?php if (!defined("ABSPATH")) exit; ?>
<?php get_header(); ?>

<sp-container>
    <sp-stack>
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/content', 'single'); ?>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>

                <sp-card padded>
                    <nav>
                        <sp-stack direction="horizontal">
                            <div><?php previous_post_link('%link', '&larr; %title'); ?></div>
                            <div><?php next_post_link('%link', '%title &rarr;'); ?></div>
                        </sp-stack>
                    </nav>
                </sp-card>
            <?php endwhile; ?>
        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </sp-stack>
</sp-container>

<?php get_footer(); ?>
