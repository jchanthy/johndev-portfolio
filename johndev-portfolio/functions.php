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

// Register Skill Custom Post Type
function john_portfolio_register_skill_cpt() {
    $labels = array(
        'name'                  => _x( 'Skills', 'Post Type General Name', 'johndev-portfolio' ),
        'singular_name'         => _x( 'Skill', 'Post Type Singular Name', 'johndev-portfolio' ),
        'menu_name'             => __( 'Skills', 'johndev-portfolio' ),
        'all_items'             => __( 'All Skills', 'johndev-portfolio' ),
        'add_new_item'          => __( 'Add New Skill', 'johndev-portfolio' ),
        'add_new'               => __( 'Add New', 'johndev-portfolio' ),
        'new_item'              => __( 'New Skill', 'johndev-portfolio' ),
        'edit_item'             => __( 'Edit Skill', 'johndev-portfolio' ),
        'update_item'           => __( 'Update Skill', 'johndev-portfolio' ),
        'view_item'             => __( 'View Skill', 'johndev-portfolio' ),
        'search_items'          => __( 'Search Skill', 'johndev-portfolio' ),
        'not_found'             => __( 'Not found', 'johndev-portfolio' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'johndev-portfolio' ),
        'featured_image'        => __( 'Skill Icon/Logo', 'johndev-portfolio' ),
        'set_featured_image'    => __( 'Set skill icon', 'johndev-portfolio' ),
        'remove_featured_image' => __( 'Remove skill icon', 'johndev-portfolio' ),
        'use_featured_image'    => __( 'Use as skill icon', 'johndev-portfolio' ),
    );
    $args = array(
        'label'                 => __( 'Skill', 'johndev-portfolio' ),
        'description'           => __( 'Technical Skills', 'johndev-portfolio' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'thumbnail', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 7,
        'menu_icon'             => 'dashicons-hammer',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'skill', $args );
}
add_action( 'init', 'john_portfolio_register_skill_cpt', 0 );

// Add Meta Box for Skill Level
function john_portfolio_add_skill_meta_box() {
    add_meta_box(
        'john_portfolio_skill_level',
        __( 'Skill Level (0-100)', 'johndev-portfolio' ),
        'john_portfolio_skill_level_callback',
        'skill',
        'side'
    );
}
add_action( 'add_meta_boxes', 'john_portfolio_add_skill_meta_box' );

function john_portfolio_skill_level_callback( $post ) {
    wp_nonce_field( 'john_portfolio_save_skill_level', 'john_portfolio_skill_level_nonce' );
    $value = get_post_meta( $post->ID, '_skill_level', true );
    echo '<input type="number" id="john_portfolio_skill_level_field" name="john_portfolio_skill_level_field" value="' . esc_attr( $value ) . '" min="0" max="100" style="width:100%;" />';
    echo '<p class="description">' . __( 'Enter a percentage (e.g., 90). Leave blank to hide progress bar.', 'johndev-portfolio' ) . '</p>';
}

function john_portfolio_save_skill_level( $post_id ) {
    if ( ! isset( $_POST['john_portfolio_skill_level_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['john_portfolio_skill_level_nonce'], 'john_portfolio_save_skill_level' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( ! isset( $_POST['john_portfolio_skill_level_field'] ) ) {
        return;
    }
    $my_data = sanitize_text_field( $_POST['john_portfolio_skill_level_field'] );
    update_post_meta( $post_id, '_skill_level', $my_data );
}
add_action( 'save_post', 'john_portfolio_save_skill_level' );

// Register Experience Custom Post Type
function john_portfolio_register_experience_cpt() {
    $labels = array(
        'name'                  => _x( 'Experience', 'Post Type General Name', 'johndev-portfolio' ),
        'singular_name'         => _x( 'Experience', 'Post Type Singular Name', 'johndev-portfolio' ),
        'menu_name'             => __( 'Experience', 'johndev-portfolio' ),
        'all_items'             => __( 'All Experience', 'johndev-portfolio' ),
        'add_new_item'          => __( 'Add New Experience', 'johndev-portfolio' ),
        'add_new'               => __( 'Add New', 'johndev-portfolio' ),
        'new_item'              => __( 'New Experience', 'johndev-portfolio' ),
        'edit_item'             => __( 'Edit Experience', 'johndev-portfolio' ),
        'update_item'           => __( 'Update Experience', 'johndev-portfolio' ),
        'view_item'             => __( 'View Experience', 'johndev-portfolio' ),
        'search_items'          => __( 'Search Experience', 'johndev-portfolio' ),
        'not_found'             => __( 'Not found', 'johndev-portfolio' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'johndev-portfolio' ),
        'featured_image'        => __( 'Company Logo', 'johndev-portfolio' ),
        'set_featured_image'    => __( 'Set company logo', 'johndev-portfolio' ),
        'remove_featured_image' => __( 'Remove company logo', 'johndev-portfolio' ),
        'use_featured_image'    => __( 'Use as company logo', 'johndev-portfolio' ),
    );
    $args = array(
        'label'                 => __( 'Experience', 'johndev-portfolio' ),
        'description'           => __( 'Work Experience', 'johndev-portfolio' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-businessperson',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'experience', $args );
}
add_action( 'init', 'john_portfolio_register_experience_cpt', 0 );

// Add Meta Boxes for Experience
function john_portfolio_add_experience_meta_boxes() {
    add_meta_box( 'john_portfolio_experience_details', __( 'Job Details', 'johndev-portfolio' ), 'john_portfolio_experience_details_callback', 'experience', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'john_portfolio_add_experience_meta_boxes' );

function john_portfolio_experience_details_callback( $post ) {
    wp_nonce_field( 'john_portfolio_save_experience_details', 'john_portfolio_experience_details_nonce' );
    $company = get_post_meta( $post->ID, '_experience_company', true );
    $job_title = get_post_meta( $post->ID, '_experience_job_title', true );
    $duration = get_post_meta( $post->ID, '_experience_duration', true );
    $location = get_post_meta( $post->ID, '_experience_location', true );
    ?>
    <p>
        <label for="john_portfolio_experience_company"><?php _e( 'Company Name', 'johndev-portfolio' ); ?></label><br>
        <input type="text" name="john_portfolio_experience_company" id="john_portfolio_experience_company" value="<?php echo esc_attr( $company ); ?>" class="widefat">
    </p>
    <p>
        <label for="john_portfolio_experience_job_title"><?php _e( 'Job Title', 'johndev-portfolio' ); ?></label><br>
        <input type="text" name="john_portfolio_experience_job_title" id="john_portfolio_experience_job_title" value="<?php echo esc_attr( $job_title ); ?>" class="widefat">
    </p>
    <p>
        <label for="john_portfolio_experience_duration"><?php _e( 'Duration (e.g., Jan 2023 - Present)', 'johndev-portfolio' ); ?></label><br>
        <input type="text" name="john_portfolio_experience_duration" id="john_portfolio_experience_duration" value="<?php echo esc_attr( $duration ); ?>" class="widefat">
    </p>
    <p>
        <label for="john_portfolio_experience_location"><?php _e( 'Location (Optional)', 'johndev-portfolio' ); ?></label><br>
        <input type="text" name="john_portfolio_experience_location" id="john_portfolio_experience_location" value="<?php echo esc_attr( $location ); ?>" class="widefat">
    </p>
    <?php
}

function john_portfolio_save_experience_details( $post_id ) {
    if ( ! isset( $_POST['john_portfolio_experience_details_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['john_portfolio_experience_details_nonce'], 'john_portfolio_save_experience_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['john_portfolio_experience_company'] ) ) {
        update_post_meta( $post_id, '_experience_company', sanitize_text_field( $_POST['john_portfolio_experience_company'] ) );
    }
    if ( isset( $_POST['john_portfolio_experience_job_title'] ) ) {
        update_post_meta( $post_id, '_experience_job_title', sanitize_text_field( $_POST['john_portfolio_experience_job_title'] ) );
    }
    if ( isset( $_POST['john_portfolio_experience_duration'] ) ) {
        update_post_meta( $post_id, '_experience_duration', sanitize_text_field( $_POST['john_portfolio_experience_duration'] ) );
    }
    if ( isset( $_POST['john_portfolio_experience_location'] ) ) {
        update_post_meta( $post_id, '_experience_location', sanitize_text_field( $_POST['john_portfolio_experience_location'] ) );
    }
}
add_action( 'save_post', 'john_portfolio_save_experience_details' );

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

    // Hero Section
    $wp_customize->add_section( 'john_portfolio_hero', array(
        'title'    => __( 'Hero Section', 'johndev-portfolio' ),
        'priority' => 20,
    ) );

    // Hero Title
    $wp_customize->add_setting( 'john_portfolio_hero_title', array(
        'default'           => 'Building Secure',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_hero_title', array(
        'label'    => __( 'Hero Title (Line 1)', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'text',
    ) );

    // Hero Highlight
    $wp_customize->add_setting( 'john_portfolio_hero_highlight', array(
        'default'           => 'Digital Experiences',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_hero_highlight', array(
        'label'    => __( 'Highlighted Text (Line 2)', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'text',
    ) );

    // Hero Subtitle
    $wp_customize->add_setting( 'john_portfolio_hero_subtitle', array(
        'default'           => 'Full Stack Developer & Cybersecurity Specialist. I craft robust applications with a security-first mindset.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_hero_subtitle', array(
        'label'    => __( 'Subtitle', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'textarea',
    ) );

    // Hero CTA Text
    $wp_customize->add_setting( 'john_portfolio_hero_cta_text', array(
        'default'           => 'View Work',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_hero_cta_text', array(
        'label'    => __( 'CTA Button Text', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'text',
    ) );

    // Hero CTA URL
    $wp_customize->add_setting( 'john_portfolio_hero_cta_url', array(
        'default'           => '#projects',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'john_portfolio_hero_cta_url', array(
        'label'    => __( 'CTA Button URL', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'text',
    ) );

    // Code Snippet Settings
    $wp_customize->add_setting( 'john_portfolio_code_comment', array(
        'default'           => '// Welcome to my digital fortress',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_code_comment', array(
        'label'    => __( 'Code Comment', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'text',
    ) );

    $wp_customize->add_setting( 'john_portfolio_code_name', array(
        'default'           => 'John',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_code_name', array(
        'label'    => __( 'Code Name', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'text',
    ) );

    $wp_customize->add_setting( 'john_portfolio_code_role', array(
        'default'           => 'Security Engineer',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_code_role', array(
        'label'    => __( 'Code Role', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'text',
    ) );

    $wp_customize->add_setting( 'john_portfolio_code_skills', array(
        'default'           => 'Penetration Testing, Secure Coding, React/Next.js',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_code_skills', array(
        'label'    => __( 'Code Skills (Comma Separated)', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_hero',
        'type'     => 'textarea',
    ) );

    // Contact Section
    $wp_customize->add_section( 'john_portfolio_contact', array(
        'title'    => __( 'Contact Section', 'johndev-portfolio' ),
        'priority' => 80,
    ) );

    $wp_customize->add_setting( 'john_portfolio_contact_text', array(
        'default'           => "I'm currently open to new opportunities and collaborations. Whether you have a question or just want to say hi, I'll try my best to get back to you!",
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_contact_text', array(
        'label'    => __( 'Contact Text', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_contact',
        'type'     => 'textarea',
    ) );

    // Blog Section
    $wp_customize->add_section( 'john_portfolio_blog', array(
        'title'    => __( 'Blog Section', 'johndev-portfolio' ),
        'priority' => 70,
    ) );

    $wp_customize->add_setting( 'john_portfolio_show_blog', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'john_portfolio_show_blog', array(
        'label'    => __( 'Show Blog Section', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_blog',
        'type'     => 'checkbox',
    ) );

    $wp_customize->add_setting( 'john_portfolio_blog_title', array(
        'default'           => 'Latest Articles',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'john_portfolio_blog_title', array(
        'label'    => __( 'Section Title', 'johndev-portfolio' ),
        'section'  => 'john_portfolio_blog',
        'type'     => 'text',
    ) );
}
add_action( 'customize_register', 'john_portfolio_customize_register' );
