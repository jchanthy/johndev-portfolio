<?php get_header(); ?>

<main class="single-post-container container">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>
            
            <header class="entry-header">
                <div class="entry-meta">
                    <span class="posted-on"><?php echo get_the_date(); ?></span>
                    <span class="cat-links"><?php the_category( ', ' ); ?></span>
                </div>
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="entry-thumbnail">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'johndev-portfolio' ),
                        'after'  => '</div>',
                    )
                );
                ?>
            </div>

            <footer class="entry-footer">
                <?php
                $tags_list = get_the_tag_list( '', esc_html__( ', ', 'johndev-portfolio' ) );
                if ( $tags_list ) {
                    printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'johndev-portfolio' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
            </footer>

        </article>

        <nav class="navigation post-navigation" aria-label="Posts">
            <h2 class="screen-reader-text">Post navigation</h2>
            <div class="nav-links">
                <div class="nav-previous"><?php previous_post_link( '%link', '&larr; %title' ); ?></div>
                <div class="nav-next"><?php next_post_link( '%link', '%title &rarr;' ); ?></div>
            </div>
        </nav>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

    endwhile; // End of the loop.
    ?>
</main>

<?php
get_footer();
