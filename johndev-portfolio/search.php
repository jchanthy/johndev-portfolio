<?php get_header(); ?>

<main class="search-container container">
    <header class="search-header">
        <h1 class="search-title">
            <?php
            /* translators: %s: search query. */
            printf( esc_html__( 'Search Results for: %s', 'johndev-portfolio' ), '<span>' . get_search_query() . '</span>' );
            ?>
        </h1>
    </header>

    <?php if ( have_posts() ) : ?>
        <div class="blog-grid">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article class="blog-card">
                    <div class="blog-image">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>" class="placeholder-overlay"></a>
                        <?php endif; ?>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span class="blog-date"><?php echo get_the_date(); ?></span>
                            <span class="blog-type"><?php echo get_post_type(); ?></span>
                        </div>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="blog-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="btn-text">Read More &rarr;</a>
                    </div>
                </article>
                <?php
            endwhile;
            ?>
        </div>

        <div class="pagination">
            <?php
            the_posts_pagination( array(
                'prev_text' => '&larr; Previous',
                'next_text' => 'Next &rarr;',
            ) );
            ?>
        </div>

    <?php else : ?>
        <div class="no-results">
            <div class="terminal-window" style="max-width: 600px; margin: 0 auto;">
                <div class="terminal-header">
                    <div class="terminal-buttons">
                        <span class="btn-dot red"></span>
                        <span class="btn-dot yellow"></span>
                        <span class="btn-dot green"></span>
                    </div>
                    <div class="terminal-title">search_failed.exe</div>
                </div>
                <div class="terminal-body">
                    <p>> ERROR: No matches found for "<?php echo get_search_query(); ?>".</p>
                    <p>> Please try a different keyword.</p>
                    <div style="margin-top: 2rem;">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
