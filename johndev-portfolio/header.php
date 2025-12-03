<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio of a Cybersecurity Expert and Full Stack Developer. Projects, Courses, and Technical Insights.">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="grid-background"></div>
    
    <header class="main-header">
        <nav class="container">
            <a href="<?php echo home_url(); ?>" class="logo">&lt;JohnDev /&gt;</a>
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_class'     => 'nav-links',
                'container'      => false,
            ) );
            ?>
            <button class="mobile-menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>
