    <footer class="main-footer">
        <div class="container footer-content">
            <div class="footer-left">
                <p>&copy; <?php echo date('Y'); ?> JohnDev. Built with security in mind.</p>
            </div>
            <div class="footer-right">
                <?php if ( get_theme_mod( 'john_portfolio_github_url' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'john_portfolio_github_url' ) ); ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
                <?php endif; ?>
                
                <?php if ( get_theme_mod( 'john_portfolio_linkedin_url' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'john_portfolio_linkedin_url' ) ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                <?php endif; ?>
                
                <?php if ( get_theme_mod( 'john_portfolio_twitter_url' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'john_portfolio_twitter_url' ) ); ?>" target="_blank" rel="noopener noreferrer">Twitter</a>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'john_portfolio_facebook_url' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'john_portfolio_facebook_url' ) ); ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'john_portfolio_instagram_url' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'john_portfolio_instagram_url' ) ); ?>" target="_blank" rel="noopener noreferrer">Instagram</a>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'john_portfolio_youtube_url' ) ) : ?>
                    <a href="<?php echo esc_url( get_theme_mod( 'john_portfolio_youtube_url' ) ); ?>" target="_blank" rel="noopener noreferrer">YouTube</a>
                <?php endif; ?>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
