<?php
/**
 * Theme functions and definitions
 * @package Themehunk
 * @subpackage gogo
 * @since 1.0
 */
if ( ! function_exists( 'gogo_setup' ) ) :
define( 'GOGO_THEME_VERSION','1.0.0');
define( 'GOGO_THEME_DIR', get_template_directory() . '/' );
define( 'GOGO_THEME_URI', get_template_directory_uri() . '/' );
define( 'GOGO_THEME_SETTINGS', 'gogo-settings' );
define( 'GOGO_THEME_NAME', 'gogo' );
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function gogo_setup(){
		/*
		 * Make theme available for translation.
		 */
		load_theme_textdomain( 'gogo' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'woocommerce' );
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		/**
		 * Add support for core custom logo.
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
		// Add support for Custom Header.
		add_theme_support( 'custom-header', apply_filters( 'gogo_custom_header_args', array(
				'default-image' => '',
				'width'         => 1920,
				'height'        => 500,
				'flex-height'   => true,
				'header-text'   => false,
		) ) );
		// Add support for Custom Background.
        $args = array(
	    'default-color' => 'eaeaea',
        );
        add_theme_support( 'custom-background',$args );
         // Recommend plugins
        add_theme_support( 'recommend-plugins', array(
            'hunk-companion' => array(
                'name' => esc_html__( 'Hunk Companion', 'gogo' ),
                'active_filename' => 'hunk-companion/hunk-companion.php',
            ),
            'lead-form-builder' => array(
                'name' => esc_html__( 'Lead Form Builder', 'gogo' ),
                'active_filename' => 'lead-form-builder/lead-form-builder.php',
            ),
            'woocommerce' => array(
                'name' => esc_html__( 'Woocommerce', 'gogo' ),
                'active_filename' => 'woocommerce/woocommerce.php',
            ),
            'elementor' => array(
                'name' => esc_html__( 'Elementor', 'gogo' ),
                'active_filename' => 'elementor/elementor.php',
            )
        ) );
	}
endif;
add_action( 'after_setup_theme', 'gogo_setup' );
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gogo_content_width(){
	$GLOBALS['content_width'] = apply_filters( 'gogo_content_width', 640 );
}
add_action( 'after_setup_theme', 'gogo_content_width', 0 );
/**
 * Register widget area.
 */
function gogo_widgets_init(){
	register_sidebar( array(
		'name'          => esc_html__( 'Primary Sidebar', 'gogo' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your primary sidebar.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="gogo-widget-content">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Above Header First Widget', 'gogo' ),
		'id'            => 'top-header-widget-col1',
		'description'   => esc_html__( 'Add widgets here to appear in top header.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Above Header Second Widget', 'gogo' ),
		'id'            => 'top-header-widget-col2',
		'description'   => esc_html__( 'Add widgets here to appear in top header.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    register_sidebar(array(
		'name'          => esc_html__( 'Above Header Third Widget', 'gogo' ),
		'id'            => 'top-header-widget-col3',
		'description'   => esc_html__( 'Add widgets here to appear in top header.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Below Header First Widget', 'gogo' ),
		'id'            => 'bottom-header-widget-col1',
		'description'   => esc_html__( 'Add widgets here to appear in below header.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Below Header Second Widget', 'gogo' ),
		'id'            => 'bottom-header-widget-col2',
		'description'   => esc_html__( 'Add widgets here to appear in below header.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Below Header Third Widget', 'gogo' ),
		'id'            => 'bottom-header-widget-col3',
		'description'   => esc_html__( 'Add widgets here to appear in below header.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Main Header Widget', 'gogo' ),
		'id'            => 'main-header-widget',
		'description'   => esc_html__( 'Add widgets here to appear in main header.', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Top First Widget', 'gogo' ),
		'id'            => 'footer-top-first',
		'description'   => esc_html__( 'Add widgets here to appear in Top Footer', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Top Second Widget', 'gogo' ),
		'id'            => 'footer-top-second',
		'description'   => esc_html__( 'Add widgets here to appear in Top Footer', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Top Third Widget', 'gogo' ),
		'id'            => 'footer-top-third',
		'description'   => esc_html__( 'Add widgets here to appear in Top Footer', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
    register_sidebar(array(
		'name'          => esc_html__( 'Footer Below First Widget', 'gogo' ),
		'id'            => 'footer-bottom-first',
		'description'   => esc_html__( 'Add widgets here to appear in below footer', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Below Second Widget', 'gogo' ),
		'id'            => 'footer-bottom-second',
		'description'   => esc_html__( 'Add widgets here to appear in below footer','gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar(array(
		'name'          => esc_html__( 'Footer Below Third Widget', 'gogo' ),
		'id'            => 'footer-bottom-third',
		'description'   => esc_html__( 'Add widgets here to appear in below Footer', 'gogo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	for ( $i = 1; $i <= 4; $i++ ){
		register_sidebar( array(
			'name'          => sprintf( esc_html__( 'Footer Widget Area %d', 'gogo' ), $i ),
			'id'            => 'footer-' . $i,
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'gogo_widgets_init' );
/**
 * Enqueue scripts and styles.
 */
function gogo_scripts(){
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/third-party/font-awesome/css/font-awesome.css', '', '4.7.0' );
	wp_enqueue_style( 'gogo-menu-style', get_template_directory_uri() . '/css/menu.css', '', '4.7.0' );
	wp_enqueue_style( 'gogo-style', get_stylesheet_uri(), array(), '1.0.2' );
	wp_add_inline_style( 'gogo-style', gogo_typography_style());
	wp_add_inline_style( 'gogo-style', gogo_custom_style());
    wp_enqueue_style('gogo-frontpage-style', get_template_directory_uri() . '/css/gogo-css/frontpage-style.css', array(), '1.0.0');
    wp_add_inline_style( 'gogo-frontpage-style', custom_style());
    wp_enqueue_script( 'gogo-menu-js', get_parent_theme_file_uri().'/js/menu.js', array( 'jquery' ), '', true );
    wp_enqueue_script("jquery-effects-core",array( 'jquery' ));
    if (class_exists( 'WooCommerce' ) ){
    wp_enqueue_script( 'gogo-woocommerce-js', get_parent_theme_file_uri().'/inc/woocommerce/js/woocommerce.js', array( 'jquery' ), '', true );
     }
    wp_enqueue_script( 'gogo-custom-js', get_parent_theme_file_uri().'/js/custom.js', array( 'jquery' ), '', true );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ){
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gogo_scripts' );

//*************GOGO functions for function.php
//==================================================
/**
 * Load init.
 */
require_once trailingslashit( get_template_directory()).'inc/init.php';