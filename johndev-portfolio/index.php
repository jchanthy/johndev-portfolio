<?php
get_header();
?>

<main class="container section">
    <div class="content-area">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php
            endwhile;
        else :
            ?>
            <p><?php _e( 'Nothing found.', 'johndev-portfolio' ); ?></p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
