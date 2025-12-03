<?php get_header(); ?>

<main class="error-404-container container">
    <div class="terminal-window">
        <div class="terminal-header">
            <div class="terminal-buttons">
                <span class="btn-dot red"></span>
                <span class="btn-dot yellow"></span>
                <span class="btn-dot green"></span>
            </div>
            <div class="terminal-title">system_error.exe</div>
        </div>
        <div class="terminal-body">
            <div class="error-code">404</div>
            <div class="error-message">
                <p>> ERROR: Resource not found.</p>
                <p>> The page you are looking for has been moved, deleted, or never existed.</p>
                <p>> Initiating recovery protocol...</p>
            </div>
            <div class="error-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Return to Base</a>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
