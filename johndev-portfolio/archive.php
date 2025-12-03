<?php get_header(); ?>

<main class="archive-container container">
    <header class="archive-header">
        <h1 class="archive-title"><?php the_archive_title(); ?></h1>
        <div class="archive-description"><?php the_archive_description(); ?></div>
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
                        </div>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="blog-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="btn-text">Read Article &rarr;</a>
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
            <p>No posts found.</p>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
