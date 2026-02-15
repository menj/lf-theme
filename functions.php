<?php
/**
 * Langgam Fikir Child Theme Functions
 * Child theme of Twenty Twenty-Five
 *
 * @package Langgam_Fikir
 * @since 1.0.0
 * Version: 1.9.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue Parent and Child Theme Styles
 */
function langgam_fikir_enqueue_styles() {
    // Enqueue parent theme stylesheet
    wp_enqueue_style( 
        'twentytwentyfive-style', 
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->parent()->get('Version')
    );
    
    // Enqueue child theme stylesheet
    wp_enqueue_style( 
        'langgam-fikir-style', 
        get_stylesheet_uri(),
        array( 'twentytwentyfive-style' ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'langgam_fikir_enqueue_styles', 15 );

/**
 * Theme Setup
 */
function langgam_fikir_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );
    
    // Set custom image sizes
    add_image_size( 'book-cover', 400, 533, true ); // 3:4 ratio
    add_image_size( 'book-thumbnail', 280, 373, true );
    
    // Register navigation menus
    register_nav_menus( array(
        'primary'      => __( 'Primary Menu', 'langgam-fikir' ),
        'footer'       => __( 'Footer Menu', 'langgam-fikir' ),
        'footer-legal' => __( 'Footer Legal Menu', 'langgam-fikir' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}
add_action( 'after_setup_theme', 'langgam_fikir_setup' );

/**
 * Set content width
 */
function langgam_fikir_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'langgam_fikir_content_width', 1200 );
}
add_action( 'after_setup_theme', 'langgam_fikir_content_width', 0 );

/**
 * Modify posts per page for blog/resources archive
 */
function langgam_fikir_modify_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_home() ) {
        $query->set( 'posts_per_page', 6 );
    }
}
add_action( 'pre_get_posts', 'langgam_fikir_modify_posts_per_page' );

/**
 * Enqueue Child Theme Assets (CSS and JavaScript)
 */
function langgam_fikir_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style( 
        'langgam-fikir-fonts', 
        'https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600&display=swap',
        array(),
        null
    );
    
    // Main CSS file
    wp_enqueue_style( 
        'langgam-fikir-main', 
        get_stylesheet_directory_uri() . '/css/main.css',
        array( 'langgam-fikir-style' ),
        filemtime( get_stylesheet_directory() . '/css/main.css' )
    );
    
    // Additional CSS for forms and extra pages
    wp_enqueue_style( 
        'langgam-fikir-additional', 
        get_stylesheet_directory_uri() . '/css/additional.css',
        array( 'langgam-fikir-main' ),
        filemtime( get_stylesheet_directory() . '/css/additional.css' )
    );
    
    // Main JavaScript
    wp_enqueue_script(
        'langgam-fikir-main',
        get_stylesheet_directory_uri() . '/js/main.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/main.js' ),
        true
    );
    
    // Add localized data for JavaScript
    wp_localize_script( 'langgam-fikir-main', 'langgamFikir', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'langgam_fikir_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'langgam_fikir_enqueue_assets', 20 );

/**
 * Enqueue Admin Assets with proper dependencies
 */
function langgam_fikir_enqueue_admin_assets( $hook ) {
    // Only load on post edit pages
    if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
        return;
    }
    
    // Get current screen to check post type
    $screen = get_current_screen();
    
    // Only load for pages (where About meta box appears)
    if ( $screen && 'page' === $screen->post_type ) {
        // Enqueue admin CSS
        wp_enqueue_style(
            'langgam-fikir-admin',
            get_stylesheet_directory_uri() . '/css/admin.css',
            array(),
            filemtime( get_stylesheet_directory() . '/css/admin.css' )
        );
        
        // Enqueue WordPress media uploader and dependencies
        wp_enqueue_media();
        
        // Enqueue admin JavaScript with full dependencies
        wp_enqueue_script(
            'langgam-fikir-admin',
            get_stylesheet_directory_uri() . '/js/admin.js',
            array( 'jquery', 'media-upload', 'media-editor', 'wp-media-utils' ),
            filemtime( get_stylesheet_directory() . '/js/admin.js' ),
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'langgam_fikir_enqueue_admin_assets' );

/**
 * Safer Heading Style Injection
 * Targets headings without custom colors to avoid breaking block settings.
 */
function langgam_fikir_custom_block_styles() {
    echo '<style>
        :where(.wp-block-heading):not([style*="color"]) {
            font-family: var(--font-primary) !important;
            color: var(--color-primary) !important;
            font-weight: 600 !important;
        }
    </style>';
}
add_action( 'wp_head', 'langgam_fikir_custom_block_styles' );

/**
 * Register Custom Post Type: Books
 */
function langgam_fikir_register_books_post_type() {
    $labels = array(
        'name'                  => _x( 'Books', 'Post Type General Name', 'langgam-fikir' ),
        'singular_name'         => _x( 'Book', 'Post Type Singular Name', 'langgam-fikir' ),
        'menu_name'             => __( 'Books', 'langgam-fikir' ),
        'name_admin_bar'        => __( 'Book', 'langgam-fikir' ),
        'archives'              => __( 'Book Archives', 'langgam-fikir' ),
        'attributes'            => __( 'Book Attributes', 'langgam-fikir' ),
        'parent_item_colon'     => __( 'Parent Book:', 'langgam-fikir' ),
        'all_items'             => __( 'All Books', 'langgam-fikir' ),
        'add_new_item'          => __( 'Add New Book', 'langgam-fikir' ),
        'add_new'               => __( 'Add New', 'langgam-fikir' ),
        'new_item'              => __( 'New Book', 'langgam-fikir' ),
        'edit_item'             => __( 'Edit Book', 'langgam-fikir' ),
        'update_item'           => __( 'Update Book', 'langgam-fikir' ),
        'view_item'             => __( 'View Book', 'langgam-fikir' ),
        'view_items'            => __( 'View Books', 'langgam-fikir' ),
        'search_items'          => __( 'Search Book', 'langgam-fikir' ),
        'not_found'             => __( 'Not found', 'langgam-fikir' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'langgam-fikir' ),
        'featured_image'        => __( 'Book Cover', 'langgam-fikir' ),
        'set_featured_image'    => __( 'Set book cover', 'langgam-fikir' ),
        'remove_featured_image' => __( 'Remove book cover', 'langgam-fikir' ),
        'use_featured_image'    => __( 'Use as book cover', 'langgam-fikir' ),
        'insert_into_item'      => __( 'Insert into book', 'langgam-fikir' ),
        'uploaded_to_this_item' => __( 'Uploaded to this book', 'langgam-fikir' ),
        'items_list'            => __( 'Books list', 'langgam-fikir' ),
        'items_list_navigation' => __( 'Books list navigation', 'langgam-fikir' ),
        'filter_items_list'     => __( 'Filter books list', 'langgam-fikir' ),
    );

    $args = array(
        'label'                 => __( 'Book', 'langgam-fikir' ),
        'description'           => __( 'Books published by Langgam Fikir', 'langgam-fikir' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'taxonomies'            => array( 'book-category', 'book-tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'publications', 'with_front' => false ),
    );

    register_post_type( 'book', $args );
}
add_action( 'init', 'langgam_fikir_register_books_post_type', 0 );

/**
 * Flush rewrite rules on theme activation
 * This fixes URL/permalink issues when switching themes
 */
function langgam_fikir_activation() {
    langgam_fikir_register_books_post_type();
    langgam_fikir_register_book_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'langgam_fikir_activation' );

/**
 * Flush rewrite rules on theme deactivation
 */
function langgam_fikir_deactivation() {
    flush_rewrite_rules();
}
add_action( 'switch_theme', 'langgam_fikir_deactivation' );

/**
 * Register Custom Taxonomies for Books
 */
function langgam_fikir_register_book_taxonomies() {
    // Book Category
    $category_labels = array(
        'name'              => _x( 'Book Categories', 'taxonomy general name', 'langgam-fikir' ),
        'singular_name'     => _x( 'Book Category', 'taxonomy singular name', 'langgam-fikir' ),
        'search_items'      => __( 'Search Categories', 'langgam-fikir' ),
        'all_items'         => __( 'All Categories', 'langgam-fikir' ),
        'parent_item'       => __( 'Parent Category', 'langgam-fikir' ),
        'parent_item_colon' => __( 'Parent Category:', 'langgam-fikir' ),
        'edit_item'         => __( 'Edit Category', 'langgam-fikir' ),
        'update_item'       => __( 'Update Category', 'langgam-fikir' ),
        'add_new_item'      => __( 'Add New Category', 'langgam-fikir' ),
        'new_item_name'     => __( 'New Category Name', 'langgam-fikir' ),
        'menu_name'         => __( 'Categories', 'langgam-fikir' ),
    );

    register_taxonomy( 'book-category', array( 'book' ), array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'book-category' ),
        'show_in_rest'      => true,
    ) );

    // Book Tag
    $tag_labels = array(
        'name'              => _x( 'Book Tags', 'taxonomy general name', 'langgam-fikir' ),
        'singular_name'     => _x( 'Book Tag', 'taxonomy singular name', 'langgam-fikir' ),
        'search_items'      => __( 'Search Tags', 'langgam-fikir' ),
        'all_items'         => __( 'All Tags', 'langgam-fikir' ),
        'edit_item'         => __( 'Edit Tag', 'langgam-fikir' ),
        'update_item'       => __( 'Update Tag', 'langgam-fikir' ),
        'add_new_item'      => __( 'Add New Tag', 'langgam-fikir' ),
        'new_item_name'     => __( 'New Tag Name', 'langgam-fikir' ),
        'menu_name'         => __( 'Tags', 'langgam-fikir' ),
    );

    register_taxonomy( 'book-tag', array( 'book' ), array(
        'hierarchical'      => false,
        'labels'            => $tag_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'book-tag' ),
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'langgam_fikir_register_book_taxonomies', 0 );

/**
 * Add Book Meta Boxes
 */
function langgam_fikir_add_book_meta_boxes() {
    add_meta_box(
        'book_details',
        __( 'Book Details', 'langgam-fikir' ),
        'langgam_fikir_book_details_callback',
        'book',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'langgam_fikir_add_book_meta_boxes' );

/**
 * Book Details Meta Box Callback
 */
function langgam_fikir_book_details_callback( $post ) {
    wp_nonce_field( 'langgam_fikir_book_meta', 'langgam_fikir_book_meta_nonce' );
    
    $subtitle   = get_post_meta( $post->ID, '_book_subtitle', true );
    $author     = get_post_meta( $post->ID, '_book_author', true );
    $editor     = get_post_meta( $post->ID, '_book_editor', true );
    $translator = get_post_meta( $post->ID, '_book_translator', true );
    $publisher  = get_post_meta( $post->ID, '_book_publisher', true );
    $year       = get_post_meta( $post->ID, '_book_year', true );
    $isbn       = get_post_meta( $post->ID, '_book_isbn', true );
    $status     = get_post_meta( $post->ID, '_book_status', true );
    if ( empty( $status ) ) {
        $status = 'published';
    }
    $price      = get_post_meta( $post->ID, '_book_price', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="book_subtitle"><?php _e( 'Subtitle', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="text" id="book_subtitle" name="book_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="large-text">
            </td>
        </tr>
        <tr>
            <th><label for="book_author"><?php _e( 'Author', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="text" id="book_author" name="book_author" value="<?php echo esc_attr( $author ); ?>" class="large-text">
            </td>
        </tr>
        <tr>
            <th><label for="book_editor"><?php _e( 'Editor', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="text" id="book_editor" name="book_editor" value="<?php echo esc_attr( $editor ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'Leave blank if not applicable', 'langgam-fikir' ); ?>">
            </td>
        </tr>
        <tr>
            <th><label for="book_translator"><?php _e( 'Penterjemah', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="text" id="book_translator" name="book_translator" value="<?php echo esc_attr( $translator ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'Leave blank if not applicable', 'langgam-fikir' ); ?>">
            </td>
        </tr>
        <tr>
            <th><label for="book_publisher"><?php _e( 'Organizer', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="text" id="book_publisher" name="book_publisher" value="<?php echo esc_attr( $publisher ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'Leave blank if not applicable', 'langgam-fikir' ); ?>">
            </td>
        </tr>
        <tr>
            <th><label for="book_year"><?php _e( 'Publication Year', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="number" id="book_year" name="book_year" value="<?php echo esc_attr( $year ); ?>" min="1900" max="2100">
            </td>
        </tr>
        <tr>
            <th><label for="book_isbn"><?php _e( 'ISBN', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="text" id="book_isbn" name="book_isbn" value="<?php echo esc_attr( $isbn ); ?>" class="large-text" placeholder="978-629-96135-0-3">
            </td>
        </tr>
        <tr>
            <th><label><?php _e( 'Status', 'langgam-fikir' ); ?></label></th>
            <td>
                <label style="margin-right: 20px;">
                    <input type="radio" name="book_status" value="published" <?php checked( $status, 'published' ); ?>>
                    <?php _e( 'Published', 'langgam-fikir' ); ?>
                </label>
                <label style="margin-right: 20px;">
                    <input type="radio" name="book_status" value="pre-order" <?php checked( $status, 'pre-order' ); ?>>
                    <?php _e( 'Pre-Order', 'langgam-fikir' ); ?>
                </label>
                <label style="margin-right: 20px;">
                    <input type="radio" name="book_status" value="coming-soon" <?php checked( $status, 'coming-soon' ); ?>>
                    <?php _e( 'Coming Soon', 'langgam-fikir' ); ?>
                </label>
                <label style="margin-right: 20px;">
                    <input type="radio" name="book_status" value="reprinting" <?php checked( $status, 'reprinting' ); ?>>
                    <?php _e( 'Reprinting', 'langgam-fikir' ); ?>
                </label>
                <label>
                    <input type="radio" name="book_status" value="out-of-print" <?php checked( $status, 'out-of-print' ); ?>>
                    <?php _e( 'Out of Print', 'langgam-fikir' ); ?>
                </label>
            </td>
        </tr>
        <tr>
            <th><label for="book_price"><?php _e( 'Price (RM)', 'langgam-fikir' ); ?></label></th>
            <td>
                <input type="number" id="book_price" name="book_price" value="<?php echo esc_attr( $price ); ?>" step="0.01" min="0">
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save Book Meta Data
 */
function langgam_fikir_save_book_meta( $post_id ) {
    if ( ! isset( $_POST['langgam_fikir_book_meta_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['langgam_fikir_book_meta_nonce'], 'langgam_fikir_book_meta' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array( 'book_subtitle', 'book_author', 'book_editor', 'book_translator', 'book_publisher', 'book_year', 'book_isbn', 'book_status', 'book_price' );

    foreach ( $fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
}
add_action( 'save_post_book', 'langgam_fikir_save_book_meta' );

/**
 * Get Book Meta Data Helper Function
 */
function langgam_fikir_get_book_meta( $post_id, $key ) {
    return get_post_meta( $post_id, '_book_' . $key, true );
}

/**
 * Customize excerpt length
 */
function langgam_fikir_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'langgam_fikir_excerpt_length', 999 );

/**
 * Customize excerpt more text
 */
function langgam_fikir_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'langgam_fikir_excerpt_more' );

/**
 * Handle Contact Form Submission
 */
function langgam_fikir_handle_contact_form() {
    // Verify nonce
    if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( $_POST['contact_nonce'], 'langgam_fikir_contact' ) ) {
        wp_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
        exit;
    }

    // Sanitize form data
    $name    = sanitize_text_field( $_POST['contact_name'] );
    $email   = sanitize_email( $_POST['contact_email'] );
    $subject = sanitize_text_field( $_POST['contact_subject'] );
    $message = sanitize_textarea_field( $_POST['contact_message'] );

    // Validate
    if ( empty( $name ) || empty( $email ) || empty( $subject ) || empty( $message ) ) {
        wp_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
        exit;
    }

    if ( ! is_email( $email ) ) {
        wp_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
        exit;
    }

    // Prepare email
    $to      = get_option( 'admin_email' );
    $headers = array( 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email );
    
    $email_subject = sprintf( __( '[%s] Contact Form: %s', 'langgam-fikir' ), get_bloginfo( 'name' ), $subject );
    
    $email_message = sprintf(
        __( '<p><strong>Name:</strong> %s</p><p><strong>Email:</strong> %s</p><p><strong>Subject:</strong> %s</p><p><strong>Message:</strong><br>%s</p>', 'langgam-fikir' ),
        $name,
        $email,
        $subject,
        nl2br( $message )
    );

    // Send email
    $sent = wp_mail( $to, $email_subject, $email_message, $headers );

    if ( $sent ) {
        wp_redirect( add_query_arg( 'contact', 'success', wp_get_referer() ) );
    } else {
        wp_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
    }
    
    exit;
}
add_action( 'admin_post_nopriv_langgam_fikir_contact_form', 'langgam_fikir_handle_contact_form' );
add_action( 'admin_post_langgam_fikir_contact_form', 'langgam_fikir_handle_contact_form' );

/**
 * Automatically use About template for page with slug 'about'
 */
function langgam_fikir_about_template( $template ) {
    if ( is_page() ) {
        global $post;
        $page_slug = $post->post_name;
        
        if ( $page_slug === 'about' || $page_slug === 'about-us' || $page_slug === 'about-langgam-fikir' ) {
            $about_template = locate_template( 'page-about.php' );
            if ( $about_template ) {
                return $about_template;
            }
        }
    }
    return $template;
}
add_filter( 'template_include', 'langgam_fikir_about_template' );

/**
 * Automatically use Contact template for page with slug 'contact'
 */
function langgam_fikir_contact_template( $template ) {
    if ( is_page() ) {
        global $post;
        $page_slug = $post->post_name;
        
        if ( $page_slug === 'contact' || $page_slug === 'contact-us' || $page_slug === 'contact-langgam-fikir' ) {
            $contact_template = locate_template( 'page-contact.php' );
            if ( $contact_template ) {
                return $contact_template;
            }
        }
    }
    return $template;
}
add_filter( 'template_include', 'langgam_fikir_contact_template' );

/**
 * Add Contact Page Meta Boxes
 */
function langgam_fikir_add_contact_meta_boxes() {
    add_meta_box(
        'contact_info_meta_box',
        __( 'Contact Page Information', 'langgam-fikir' ),
        'langgam_fikir_contact_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'langgam_fikir_add_contact_meta_boxes' );

/**
 * Contact Meta Box Callback
 */
function langgam_fikir_contact_meta_box_callback( $post ) {
    // Only show on contact pages
    $slug = $post->post_name;
    if ( $slug !== 'contact' && $slug !== 'contact-us' && $slug !== 'contact-langgam-fikir' ) {
        return;
    }
    
    wp_nonce_field( 'langgam_fikir_contact_meta', 'contact_meta_nonce' );
    
    $description = get_post_meta( $post->ID, '_contact_description', true );
    $email = get_post_meta( $post->ID, '_contact_email', true );
    $phone = get_post_meta( $post->ID, '_contact_phone', true );
    $instagram = get_post_meta( $post->ID, '_contact_instagram', true );
    
    ?>
    <style>
        .contact-meta-field { margin-bottom: 20px; }
        .contact-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
        .contact-meta-field input[type="text"],
        .contact-meta-field input[type="email"],
        .contact-meta-field input[type="url"] { width: 100%; padding: 8px; }
        .contact-meta-field textarea { width: 100%; padding: 8px; rows: 4; }
        .contact-meta-help { font-size: 12px; color: #666; margin-top: 5px; }
    </style>
    
    <div class="contact-meta-field">
        <label for="contact_description"><?php _e( 'Contact Description', 'langgam-fikir' ); ?></label>
        <textarea id="contact_description" name="contact_description" rows="4"><?php echo esc_textarea( $description ); ?></textarea>
        <p class="contact-meta-help"><?php _e( 'A brief description that appears in the contact info section.', 'langgam-fikir' ); ?></p>
    </div>
    
    <div class="contact-meta-field">
        <label for="contact_email"><?php _e( 'Email Address', 'langgam-fikir' ); ?></label>
        <input type="email" id="contact_email" name="contact_email" value="<?php echo esc_attr( $email ); ?>" placeholder="contact@langgamfikir.com">
        <p class="contact-meta-help"><?php _e( 'Your contact email address.', 'langgam-fikir' ); ?></p>
    </div>
    
    <div class="contact-meta-field">
        <label for="contact_phone"><?php _e( 'Phone Number', 'langgam-fikir' ); ?></label>
        <input type="text" id="contact_phone" name="contact_phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="+60 1234 1234">
        <p class="contact-meta-help"><?php _e( 'Your contact phone number.', 'langgam-fikir' ); ?></p>
    </div>
    
    <div class="contact-meta-field">
        <label for="contact_instagram"><?php _e( 'Instagram URL', 'langgam-fikir' ); ?></label>
        <input type="url" id="contact_instagram" name="contact_instagram" value="<?php echo esc_attr( $instagram ); ?>" placeholder="https://instagram.com/yourhandle">
        <p class="contact-meta-help"><?php _e( 'Your Instagram profile URL (optional).', 'langgam-fikir' ); ?></p>
    </div>
    <?php
}

/**
 * Save Contact Meta Box Data
 */
function langgam_fikir_save_contact_meta( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['contact_meta_nonce'] ) || ! wp_verify_nonce( $_POST['contact_meta_nonce'], 'langgam_fikir_contact_meta' ) ) {
        return;
    }
    
    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save fields
    if ( isset( $_POST['contact_description'] ) ) {
        update_post_meta( $post_id, '_contact_description', sanitize_textarea_field( $_POST['contact_description'] ) );
    }
    
    if ( isset( $_POST['contact_email'] ) ) {
        update_post_meta( $post_id, '_contact_email', sanitize_email( $_POST['contact_email'] ) );
    }
    
    if ( isset( $_POST['contact_phone'] ) ) {
        update_post_meta( $post_id, '_contact_phone', sanitize_text_field( $_POST['contact_phone'] ) );
    }
    
    if ( isset( $_POST['contact_instagram'] ) ) {
        update_post_meta( $post_id, '_contact_instagram', esc_url_raw( $_POST['contact_instagram'] ) );
    }
}
add_action( 'save_post', 'langgam_fikir_save_contact_meta' );

/**
 * Add About Page Meta Boxes
 */
function langgam_fikir_add_about_meta_boxes() {
    add_meta_box(
        'about_info_meta_box',
        __( 'About Page Information', 'langgam-fikir' ),
        'langgam_fikir_about_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'langgam_fikir_add_about_meta_boxes' );

/**
 * About Meta Box Callback
 */
function langgam_fikir_about_meta_box_callback( $post ) {
    // Only show on about pages
    $slug = $post->post_name;
    if ( $slug !== 'about' && $slug !== 'about-us' && $slug !== 'about-langgam-fikir' ) {
        return;
    }
    
    wp_nonce_field( 'langgam_fikir_about_meta', 'about_meta_nonce' );
    
    $contact_heading = get_post_meta( $post->ID, '_about_contact_heading', true );
    $contact_phone = get_post_meta( $post->ID, '_about_contact_phone', true );
    $logo_id = get_post_meta( $post->ID, '_about_logo_id', true );
    $logo_url = $logo_id ? wp_get_attachment_url( $logo_id ) : '';
    
    ?>
    <div class="about-meta-note">
        <strong>📝 Note:</strong> Write your main about text in the editor above. Use these fields for the logo and contact section.
    </div>
    
    <div class="about-meta-field">
        <label for="about_logo"><?php _e( 'About Page Logo', 'langgam-fikir' ); ?></label>
        <input type="hidden" id="about_logo_id" name="about_logo_id" value="<?php echo esc_attr( $logo_id ); ?>">
        <button type="button" class="button upload-logo-button"><?php _e( 'Upload/Select Logo', 'langgam-fikir' ); ?></button>
        <button type="button" class="button remove-logo-button" <?php echo $logo_id ? '' : 'style="display:none;"'; ?>><?php _e( 'Remove Logo', 'langgam-fikir' ); ?></button>
        <p class="about-meta-help"><?php _e( 'Upload a logo to display on the left side. Leave empty to use default site logo.', 'langgam-fikir' ); ?></p>
        <div class="about-logo-preview" <?php echo $logo_url ? '' : 'style="display:none;"'; ?>>
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="Logo preview">
        </div>
    </div>
    
    <div class="about-meta-field">
        <label for="about_contact_heading"><?php _e( 'Contact Section Heading', 'langgam-fikir' ); ?></label>
        <input type="text" 
               id="about_contact_heading" 
               name="about_contact_heading" 
               value="<?php echo esc_attr( $contact_heading ); ?>" 
               placeholder="Have any questions?">
        <p class="about-meta-help"><?php _e( 'The heading that appears above the phone number (e.g., "Have any questions?").', 'langgam-fikir' ); ?></p>
    </div>
    
    <div class="about-meta-field">
        <label for="about_contact_phone"><?php _e( 'Contact Phone Number', 'langgam-fikir' ); ?></label>
        <input type="text" 
               id="about_contact_phone" 
               name="about_contact_phone" 
               value="<?php echo esc_attr( $contact_phone ); ?>" 
               placeholder="+60 1234 1234">
        <p class="about-meta-help"><?php _e( 'The phone number displayed in the contact section at the bottom.', 'langgam-fikir' ); ?></p>
    </div>
    <?php
}

/**
 * Save About Meta Box Data
 */
function langgam_fikir_save_about_meta( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['about_meta_nonce'] ) || ! wp_verify_nonce( $_POST['about_meta_nonce'], 'langgam_fikir_about_meta' ) ) {
        return;
    }
    
    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save logo ID
    if ( isset( $_POST['about_logo_id'] ) ) {
        update_post_meta( $post_id, '_about_logo_id', intval( $_POST['about_logo_id'] ) );
    } else {
        delete_post_meta( $post_id, '_about_logo_id' );
    }
    
    // Save fields
    if ( isset( $_POST['about_contact_heading'] ) ) {
        update_post_meta( $post_id, '_about_contact_heading', sanitize_text_field( $_POST['about_contact_heading'] ) );
    }
    
    if ( isset( $_POST['about_contact_phone'] ) ) {
        update_post_meta( $post_id, '_about_contact_phone', sanitize_text_field( $_POST['about_contact_phone'] ) );
    }
}
add_action( 'save_post', 'langgam_fikir_save_about_meta' );

/**
 * Automatically use Privacy Policy template for page with slug 'privacy'
 */
function langgam_fikir_privacy_template( $template ) {
    if ( is_page() ) {
        global $post;
        $page_slug = $post->post_name;
        
        if ( $page_slug === 'privacy' || $page_slug === 'privacy-policy' ) {
            $privacy_template = locate_template( 'page-privacy.php' );
            if ( $privacy_template ) {
                return $privacy_template;
            }
        }
    }
    return $template;
}
add_filter( 'template_include', 'langgam_fikir_privacy_template' );

/**
 * Customizer Settings for Homepage and Branding
 */
function langgam_fikir_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'langgam_fikir_homepage', array(
        'title'    => __( 'Homepage Settings', 'langgam-fikir' ),
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'featured_books_count', array(
        'default'           => 4,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'featured_books_count', array(
        'label'       => __( 'Number of Featured Publications', 'langgam-fikir' ),
        'section'     => 'langgam_fikir_homepage',
        'type'        => 'number',
    ) );

    $wp_customize->add_setting( 'featured_resources_count', array(
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'featured_resources_count', array(
        'label'       => __( 'Number of Resources on Homepage', 'langgam-fikir' ),
        'section'     => 'langgam_fikir_homepage',
        'type'        => 'number',
    ) );
    
    // Experience Badge Branding
    $wp_customize->add_section( 'lf_homepage_custom', array( 'title' => 'Homepage Branding', 'priority' => 32 ) );
    $wp_customize->add_setting( 'experience_years', array( 'default' => '' ) );
    $wp_customize->add_control( 'experience_years', array( 'label' => 'Experience Years (e.g. 10+)', 'section' => 'lf_homepage_custom', 'type' => 'text' ) );
    $wp_customize->add_setting( 'experience_label', array( 'default' => '' ) );
    $wp_customize->add_control( 'experience_label', array( 'label' => 'Badge Label', 'section' => 'lf_homepage_custom', 'type' => 'text' ) );

    // Company Contact Information Section
    $wp_customize->add_section( 'langgam_fikir_contact', array(
        'title'       => __( 'Contact Information', 'langgam-fikir' ),
        'description' => __( 'Configure your company contact details for the footer.', 'langgam-fikir' ),
        'priority'    => 31,
    ) );
    
    $wp_customize->add_setting( 'company_name', array( 'default' => get_bloginfo( 'name' ), 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'company_name', array( 'label' => __( 'Company Name', 'langgam-fikir' ), 'section' => 'langgam_fikir_contact', 'type' => 'text' ) );
    
    $wp_customize->add_setting( 'company_address', array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'company_address', array( 'label' => __( 'Address', 'langgam-fikir' ), 'section' => 'langgam_fikir_contact', 'type' => 'textarea' ) );
    
    $wp_customize->add_setting( 'company_phone', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'company_phone', array( 'label' => __( 'Phone Number', 'langgam-fikir' ), 'section' => 'langgam_fikir_contact', 'type' => 'text' ) );
    
    $wp_customize->add_setting( 'company_email', array( 'default' => '', 'sanitize_callback' => 'sanitize_email' ) );
    $wp_customize->add_control( 'company_email', array( 'label' => __( 'Email Address', 'langgam-fikir' ), 'section' => 'langgam_fikir_contact', 'type' => 'email' ) );
    
    $wp_customize->add_setting( 'legal_entity_name', array( 'default' => 'Langgam Fikir Enterprise', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'legal_entity_name', array( 'label' => __( 'Legal Entity Name', 'langgam-fikir' ), 'section' => 'langgam_fikir_contact', 'type' => 'text' ) );
    
    $wp_customize->add_setting( 'registration_number', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'registration_number', array( 'label' => __( 'Registration Number', 'langgam-fikir' ), 'section' => 'langgam_fikir_contact', 'type' => 'text' ) );
}
add_action( 'customize_register', 'langgam_fikir_customize_register' );

/**
 * Fallback for the Footer Legal menu
 */
function lf_footer_legal_fallback() {
    $privacy_page = get_privacy_policy_url();
    echo '<ul id="footer-legal-menu" class="menu">';
    if ( $privacy_page ) {
        echo '<li><a href="' . esc_url( $privacy_page ) . '">' . esc_html__( 'Privacy Policy', 'langgam-fikir' ) . '</a></li>';
    }
    echo '<li><a href="' . esc_url( home_url( '/terms-of-service/' ) ) . '">' . esc_html__( 'Terms of Service', 'langgam-fikir' ) . '</a></li>';
    echo '</ul>';
}
