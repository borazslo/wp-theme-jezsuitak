<?php

require 'plugin-update-checker-4.11/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/borazslo/wp-theme-jezsuitak',
	__FILE__, //Full path to the main plugin file or functions.php.
	'jezsuitak-theme'
);
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//remove popup_% from editor custom filed selected list
add_filter( 'postmeta_form_limit', 'wpse_73543_hide_meta_start' );
function wpse_73543_hide_meta_start( $num ) {
    add_filter( 'query', 'wpse_73543_hide_meta_filter' );

    return $num;
}
function wpse_73543_hide_meta_filter( $query ) {
    // Protect further queries.
    remove_filter( current_filter(), __FUNCTION__ );
    $where     = " AND meta_key NOT RLIKE '^(popup_|sg_|ml-)' ";
    $find      = "HAVING ";
    $query     = str_replace( $find, "$where\n$find", $query );
    return $query;
}


function getnevtarbyenter_func( $atts ) {
	$retstring = file_get_contents('http://jezsuita.hu/nevtar/wp-content/themes/jezsuitanevtar/getnamebycat.php');
	return $retstring;
}
add_shortcode('getnevtarbyenter', 'getnevtarbyenter_func' );



function arckepcsarnokrand_func( $atts ) {

	$retstring = file_get_contents('http://arckepcsarnok.jezsuita.hu/wp-content/themes/arckepcsarnok/getrand.php');

	return $retstring;

}
add_shortcode('arckepcsarnokrand', 'arckepcsarnokrand_func' );


function getpostbycat_func( $atts ) {
	$a = shortcode_atts( array('slug' => 'blog', 'db'=>3), $atts );
	$args = array(
		'post_type' => 'post',
		'orderby'=>'date',
		'order' => 'DESC',
		'category_name' => $a['slug'],
		'posts_per_page'=>$a['db'],
	);

	// The Query
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {
		$retstring .= '<ul class="getpostbycat">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$retstring .= '<li class="getpostbycattitle"><a href="'.get_post_field( 'post_name', get_the_ID() ).'">'.get_the_title().'</a></li>';
		}
		$retstring .= '</ul>';
		// Restore original Post Data 
		wp_reset_postdata();
	} else {
		// no posts found
		$retstring = '';
	}
	return $retstring;

}
add_shortcode('getpostbycat', 'getpostbycat_func' );


// [programok]
add_filter('widget_text', 'do_shortcode');
function programok_func( $atts ) {
	$a = shortcode_atts( array('db' => ''), $atts );
	if ($a['db'] < 1) {
		$a['db'] = -1;
	}

	$args = array(
		'post_type' => 'post',
		'relation' => 'AND',
		'orderby'=>'meta_value',
		'order' => 'ASC',
		'posts_per_page'=>$a['db'],
		'meta_query' => array(
			array(
				'key' => 'datum',
				'compare' => 'EXISTS',
			),
			array(
				'key'   => 'datum',
				'value'   =>  date('Y. m. d.'),
				'compare' => '>=',
			),
		)
	);

	// The Query
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$postdate = get_post_meta(get_the_ID(), 'datum', false);

			// ha csak egy datum van megadva
			if (count($postdate) == 1) {
				$datestring = ''.$postdate[0];
				$datestring_alt = ''.$postdate[0];
			// ha tobb datum is meg van adva, akkor kikeressuk a kovetkezőt
			} else {
				sort($postdate);
				foreach ($postdate as $pd) {
					$datestring = ''.$pd;
					$datestring_alt = ''.$pd;
					if ($pd >= date('Y. m. d.')) {
						break;
					}
				}
			}

			$honap = array('','Január','Február','Március','Április','Május','Június','Július','Augusztus','Szeptember','Október','November','December');
			$datestring_ho = ucfirst(mb_substr($honap[preg_replace('/^([0-9]{4})\.\s+0?([0-9]{1,2})\.\s+0?([0-9]{1,2})\..*/', '$2', $datestring)], 0, 3));
			$datestring_nap = preg_replace('/^([0-9]{4})\.\s+0?([0-9]{1,2})\.\s+0?([0-9]{1,2})\..*/', '$3', $datestring);


			$program_title = get_post_meta(get_the_ID(), 'program_title', true);
			if (strlen($program_title) < 1) {
				$program_title = get_the_title();
			}

			$program_description = get_post_meta(get_the_ID(), 'program_description', true);
			if (strlen($program_description) < 1) {
				$program_description = get_the_excerpt();
			}
			$program_description = preg_replace('/^(.{100}[^\s]+)\s.*/s', '$1...', $program_description);
			$program_description = preg_replace('/<\s*br\s*\/?\s*>\s*/', '', $program_description);
			trim($program_description);

			$retprgs_array[$datestring_alt] .= '
			<div class="programitem">
				<a href="'.get_permalink().'"><div class="date"><div class="date_ho">'.$datestring_ho.'</span> <span class="date_nap">'.$datestring_nap.'</div></div></a>
				<div class="programtext">
					<div class="programtitle"><a href="'.get_permalink().'">' . $program_title . '</a></div>
					<div class="programdescription"><a href="'.get_permalink().'">'.$program_description.'</a></div>
				</div>
			</div>';
		}
		//sorbarendezük a programokat
		ksort($retprgs_array);
		$retstring = '<div class="programwidget">';
		foreach ($retprgs_array as $p) {
			$retstring .= $p;
		}
		$retstring .= '</div>';
		// Restore original Post Data 
		wp_reset_postdata();
	} else {
		// no posts found
		$retstring = '';
	}
	return $retstring;
}
add_shortcode('programok', 'programok_func' );

// Add filter to specific menus 
/*
add_filter('wp_nav_menu_args', 'add_filter_to_menus');
function add_filter_to_menus($args) {

    // You can test agasint things like $args['menu'], $args['menu_id'] or $args['theme_location']
    if( $args['theme_location'] == 'primary') {
        add_filter( 'wp_setup_nav_menu_item', 'filter_menu_items' );
    }

    return $args;
}
function filter_menu_items($item) {

    if( $item->type == 'taxonomy') {

        // For category menu items
        $cat_base = get_option('category_base');
        if( empty($cat_base) ) {
            $cat_base = 'category';
        }

        // Get the path to the category (excluding the home and category base parts of the URL)
        $cat_path = str_replace(home_url().'/'.$cat_base, '', $item->url);

        // Get category and image ID
        $cat = get_category_by_path($cat_path, true);
        $thumb_id = get_term_meta($cat->term_id, '_term_image_id', true); // I'm using the 'Simple Term Meta' plugin to store an attachment ID as the featured image

    } else {
        // Get post and image ID
	// csak, ha nem subitem
	if ($item->menu_item_parent == 0) {
        	$post_id = url_to_postid( $item->url );
	        $thumb_id = get_post_thumbnail_id( $post_id );
	}
    }

    if( !empty($thumb_id) ) {
        // Make the title just be the featured image.
        $item->title = wp_get_attachment_image($thumb_id, array('196', '*')).$item->title;
    }

    return $item;
}
*/

// custom theme settings
/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Thirteen 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function twentythirteen_customize_register( $wp_customize ) {
	//$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	//$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	//$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
		/*
	$wp_customize->add_section( 'sample_custom_controls_section',
	   array(
		  'title' => __( 'Google Analytics Code' ),
		  'description' => esc_html__( 'These are an example of Customizer Custom Controls.' ),
		  
		  'priority' => 160, // Not typically needed. Default is 160
		  'capability' => 'edit_theme_options', // Not typically needed. Default is edit_theme_options
		  'theme_supports' => '', // Rarely needed
		  'active_callback' => '', // Rarely needed
		  'description_hidden' => 'false', // Rarely needed. Default is False
	   )
	); */

	$wp_customize->add_setting( 'googleanalytics_id',
	   array(
		  'default' => '',
		  'transport' => 'postMessage', //refresh postMessage
		  'sanitize_callback' => 'googleanalytics_id_sanitization'
		  )
	);
 
	$wp_customize->add_control( 'googleanalytics_id',
	   array(
		  'label' => __( 'Google Analytics ID' ),     
		  'section' => 'title_tagline',
		  'priority' => 100, // Optional. Order priority to load the control. Default: 10
		  'type' => 'text', // Can be either text, email, url, number, hidden, or date
		  'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		  'input_attrs' => array( // Optional.
			 'class' => 'my-custom-class',
			 'style' => 'border: 1px solid rebeccapurple',
			 'placeholder' => __( 'Enter ID...' ),
		  ),
	   )
	);
		
	$wp_customize->add_setting( 'facebookpixel_id',
	   array(
		  'default' => '',
		  'transport' => 'postMessage', //refresh postMessage
		  'sanitize_callback' => 'facebookpixel_id_sanitization'
		  )
	);
 
	$wp_customize->add_control( 'facebookpixel_id',
	   array(
		  'label' => __( 'Facebook Pixel Code' ),     
		  'section' => 'title_tagline',
		  'priority' => 100, // Optional. Order priority to load the control. Default: 10
		  'type' => 'text', // Can be either text, email, url, number, hidden, or date
		  'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		  'input_attrs' => array( // Optional.
			 'class' => 'my-custom-class',
			 'style' => 'border: 1px solid rebeccapurple',
			 'placeholder' => __( 'Enter ID...' ),
		  ),
	   )
	);

}
add_action( 'customize_register', 'twentythirteen_customize_register' );

function googleanalytics_id_sanitization($text) {
	if($text == '') return '';
	if(preg_match('/^ua-\d{4,9}-\d{1,4}$/i',$text)) return $text;
	return null;
}

function facebookpixel_id_sanitization($text) {
	if($text == '') return '';
	if(preg_match('/^\d{8,16}$/i',$text)) return $text;
	return null;
}

/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */


//fooldalon csak a hirek jelenjenek meg:
function my_home_category( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$query->set( 'cat', '9');
	}
}
add_action( 'pre_get_posts', 'my_home_category' );

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentysixteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteen', get_template_directory() . '/languages' );

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
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 70,
		'width'       => 70,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'twentysixteen' ),
		'social'  => __( 'Social Links Menu', 'twentysixteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
/*		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
*/
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'twentysixteen_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Lábléc', 'twentysixteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Főlapi 6-os box hírek alatt', 'twentysixteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20160412' );
	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteen-style' ), '20160412' );
	wp_style_add_data( 'twentysixteen-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteen-style' ), '20160412' );
	wp_style_add_data( 'twentysixteen-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'twentysixteen-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160412', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160412' );
	}

	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160412', true );

	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteen' ),
		'collapse' => __( 'collapse child menu', 'twentysixteen' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function twentysixteen_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args' );
