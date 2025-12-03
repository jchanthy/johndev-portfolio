<?php
function john_portfolio_scripts() {
    wp_enqueue_style( 'john-portfolio-style', get_stylesheet_uri() );
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap', array(), null );
    wp_enqueue_script( 'john-portfolio-script', get_template_directory_uri() . '/js/script.js', array(), '1.0', true );
    
    // Pass AJAX URL to script.js
    wp_localize_script( 'john-portfolio-script', 'john_portfolio_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'john_portfolio_contact_nonce' )
    ));
}
add_action( 'wp_enqueue_scripts', 'john_portfolio_scripts' );

// AJAX Contact Form Handler
function john_portfolio_send_message() {
    check_ajax_referer( 'john_portfolio_contact_nonce', 'nonce' );

    // Honeypot Check
    if ( ! empty( $_POST['portfolio_honey'] ) ) {
        wp_send_json_error( 'Spam detected.' );
        return;
    }

    $name    = sanitize_text_field( $_POST['name'] );
    $email   = sanitize_email( $_POST['email'] );
    $message = sanitize_textarea_field( $_POST['message'] );

    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( 'Please fill in all fields.' );
    }

    $to      = get_option( 'admin_email' );
    $subject = 'New Contact Message from ' . $name;
    $body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' );

    if ( wp_mail( $to, $subject, $body, $headers ) ) {
        wp_send_json_success( 'Message sent successfully!' );
    } else {
        wp_send_json_error( 'Failed to send message. Please try again later.' );
    }
}
add_action( 'wp_ajax_john_portfolio_send_message', 'john_portfolio_send_message' );
add_action( 'wp_ajax_nopriv_john_portfolio_send_message', 'john_portfolio_send_message' );

function john_portfolio_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    // Register Navigation Menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'johndev-portfolio' ),
    ) );
}
add_action( 'after_setup_theme', 'john_portfolio_setup' );

// Register Project Custom Post Type
function john_portfolio_register_project_cpt() {
    $labels = array(
        'name'                  => _x( 'Projects', 'Post Type General Name', 'johndev-portfolio' ),
        'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'johndev-portfolio' ),
        'menu_name'             => __( 'Projects', 'johndev-portfolio' ),
        'name_admin_bar'        => __( 'Project', 'johndev-portfolio' ),
        'archives'              => __( 'Project Archives', 'johndev-portfolio' ),
        'attributes'            => __( 'Project Attributes', 'johndev-portfolio' ),
        'parent_item_colon'     => __( 'Parent Project:', 'johndev-portfolio' ),
        'all_items'             => __( 'All Projects', 'johndev-portfolio' ),
        'add_new_item'          => __( 'Add New Project', 'johndev-portfolio' ),
        'add_new'               => __( 'Add New', 'johndev-portfolio' ),
        'new_item'              => __( 'New Project', 'johndev-portfolio' ),
        'edit_item'             => __( 'Edit Project', 'johndev-portfolio' ),
        'update_item'           => __( 'Update Project', 'johndev-portfolio' ),
        'view_item'             => __( 'View Project', 'johndev-portfolio' ),
        'view_items'            => __( 'View Projects', 'johndev-portfolio' ),
        'search_items'          => __( 'Search Project', 'johndev-portfolio' ),
        'not_found'             => __( 'Not found', 'johndev-portfolio' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'johndev-portfolio' ),
        'featured_image'        => __( 'Project Image', 'johndev-portfolio' ),
        'set_featured_image'    => __( 'Set project image', 'johndev-portfolio' ),
        'remove_featured_image' => __( 'Remove project image', 'johndev-portfolio' ),
        'use_featured_image'    => __( 'Use as project image', 'johndev-portfolio' ),
        'insert_into_item'      => __( 'Insert into project', 'johndev-portfolio' ),
        'uploaded_to_this_item' => __( 'Uploaded to this project', 'johndev-portfolio' ),
        'items_list'            => __( 'Projects list', 'johndev-portfolio' ),
        'items_list_navigation' => __( 'Projects list navigation', 'johndev-portfolio' ),
        'filter_items_list'     => __( 'Filter projects list', 'johndev-portfolio' ),
    );
    $args = array(
        'label'                 => __( 'Project', 'johndev-portfolio' ),
        'description'           => __( 'Portfolio Projects', 'johndev-portfolio' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'technology' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'project', $args );
}
add_action( 'init', 'john_portfolio_register_project_cpt', 0 );

// Register Technology Taxonomy
function john_portfolio_register_technology_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Technologies', 'Taxonomy General Name', 'johndev-portfolio' ),
        'singular_name'              => _x( 'Technology', 'Taxonomy Singular Name', 'johndev-portfolio' ),
        'menu_name'                  => __( 'Technologies', 'johndev-portfolio' ),
        'all_items'                  => __( 'All Technologies', 'johndev-portfolio' ),
        'parent_item'                => __( 'Parent Technology', 'johndev-portfolio' ),
        'parent_item_colon'          => __( 'Parent Technology:', 'johndev-portfolio' ),
        'new_item_name'              => __( 'New Technology Name', 'johndev-portfolio' ),
        'add_new_item'               => __( 'Add New Technology', 'johndev-portfolio' ),
        'edit_item'                  => __( 'Edit Technology', 'johndev-portfolio' ),
        'update_item'                => __( 'Update Technology', 'johndev-portfolio' ),
        'view_item'                  => __( 'View Technology', 'johndev-portfolio' ),
        'separate_items_with_commas' => __( 'Separate technologies with commas', 'johndev-portfolio' ),
        'add_or_remove_items'        => __( 'Add or remove technologies', 'johndev-portfolio' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'johndev-portfolio' ),
        'popular_items'              => __( 'Popular Technologies', 'johndev-portfolio' ),
        'search_items'               => __( 'Search Technologies', 'johndev-portfolio' ),
        'not_found'                  => __( 'Not Found', 'johndev-portfolio' ),
        'no_terms'                   => __( 'No technologies', 'johndev-portfolio' ),
        'items_list'                 => __( 'Technologies list', 'johndev-portfolio' ),
        'items_list_navigation'      => __( 'Technologies list navigation', 'johndev-portfolio' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy( 'technology', array( 'project' ), $args );
}
add_action( 'init', 'john_portfolio_register_technology_taxonomy', 0 );

// Register Course Custom Post Type
function john_portfolio_register_course_cpt() {
    $labels = array(
        'name'                  => _x( 'Courses', 'Post Type General Name', 'johndev-portfolio' ),
        'singular_name'         => _x( 'Course', 'Post Type Singular Name', 'johndev-portfolio' ),
        'menu_name'             => __( 'Courses', 'johndev-portfolio' ),
        'name_admin_bar'        => __( 'Course', 'johndev-portfolio' ),
        'archives'              => __( 'Course Archives', 'johndev-portfolio' ),
        'attributes'            => __( 'Course Attributes', 'johndev-portfolio' ),
        'parent_item_colon'     => __( 'Parent Course:', 'johndev-portfolio' ),
        'all_items'             => __( 'All Courses', 'johndev-portfolio' ),
        'add_new_item'          => __( 'Add New Course', 'johndev-portfolio' ),
        'add_new'               => __( 'Add New', 'johndev-portfolio' ),
        'new_item'              => __( 'New Course', 'johndev-portfolio' ),
        'edit_item'             => __( 'Edit Course', 'johndev-portfolio' ),
        'update_item'           => __( 'Update Course', 'johndev-portfolio' ),
        'view_item'             => __( 'View Course', 'johndev-portfolio' ),
        'view_items'            => __( 'View Courses', 'johndev-portfolio' ),
        'search_items'          => __( 'Search Course', 'johndev-portfolio' ),
        'not_found'             => __( 'Not found', 'johndev-portfolio' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'johndev-portfolio' ),
        'featured_image'        => __( 'Course Image', 'johndev-portfolio' ),
        'set_featured_image'    => __( 'Set course image', 'johndev-portfolio' ),
        'remove_featured_image' => __( 'Remove course image', 'johndev-portfolio' ),
        'use_featured_image'    => __( 'Use as course image', 'johndev-portfolio' ),
        'insert_into_item'      => __( 'Insert into course', 'johndev-portfolio' ),
        'uploaded_to_this_item' => __( 'Uploaded to this course', 'johndev-portfolio' ),
        'items_list'            => __( 'Courses list', 'johndev-portfolio' ),
        'items_list_navigation' => __( 'Courses list navigation', 'johndev-portfolio' ),
        'filter_items_list'     => __( 'Filter courses list', 'johndev-portfolio' ),
    );
    $args = array(
        'label'                 => __( 'Course', 'johndev-portfolio' ),
        'description'           => __( 'Certifications and Courses', 'johndev-portfolio' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-welcome-learn-more',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'course', $args );
}
add_action( 'init', 'john_portfolio_register_course_cpt', 0 );

// Auto-add button class to Contact menu item
function john_portfolio_menu_classes( $atts, $item, $args ) {
    if ( 'primary' === $args->theme_location ) {
        if ( 'Contact' === $item->title || 'Get in Touch' === $item->title ) {
            $class = isset( $atts['class'] ) ? $atts['class'] : '';
            $atts['class'] = $class . ' btn btn-primary';
        }
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'john_portfolio_menu_classes', 10, 3 );

// Theme Update Checker
// require 'inc/plugin-update-checker/plugin-update-checker.php';
// use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// $myUpdateChecker = PucFactory::buildUpdateChecker(
//     'https://github.com/jchanthy/johndev-portfolio',
//     __FILE__,
//     'johndev-portfolio'
// );

// Set the branch that contains the stable release.
// $myUpdateChecker->setBranch('main');


// Customizer Settings
function john_portfolio_customize_register( $wp_customize ) {
    // Social Links Section
    $wp_customize->add_section( 'john_portfolio_social_links', array(
        'title'    => __( 'Social Links', 'johndev-portfolio' ),
        'priority' => 30,
    ) );

    // GitHub URL
    $wp_customize->add_setting( 'john_portfolio_github_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'john_portfolio_github_url', array(
        'label'    => __( 'GitHub URL', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_social_links',
        'type'     => 'url',
    ) );

    // LinkedIn URL
    $wp_customize->add_setting( 'john_portfolio_linkedin_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'john_portfolio_linkedin_url', array(
        'label'    => __( 'LinkedIn URL', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_social_links',
        'type'     => 'url',
    ) );

    // Twitter URL
    $wp_customize->add_setting( 'john_portfolio_twitter_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'john_portfolio_twitter_url', array(
        'label'    => __( 'Twitter URL', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_social_links',
        'type'     => 'url',
    ) );

    // Facebook URL
    $wp_customize->add_setting( 'john_portfolio_facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'john_portfolio_facebook_url', array(
        'label'    => __( 'Facebook URL', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_social_links',
        'type'     => 'url',
    ) );

    // Instagram URL
    $wp_customize->add_setting( 'john_portfolio_instagram_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'john_portfolio_instagram_url', array(
        'label'    => __( 'Instagram URL', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_social_links',
        'type'     => 'url',
    ) );

    // YouTube URL
    $wp_customize->add_setting( 'john_portfolio_youtube_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'john_portfolio_youtube_url', array(
        'label'    => __( 'YouTube URL', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_social_links',
        'type'     => 'url',
    ) );
}
add_action( 'customize_register', 'john_portfolio_customize_register' );
