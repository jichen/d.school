<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package dschool
 * @subpackage Functions
 * @version 0.2.0
 * @author Jason Conroy <jason@findingsimple.com>
 * @copyright Copyright (c) 2010 - 2011, Jason Conroy
 * @link http://dschool.stanford.edu/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'hybrid-core/hybrid.php' );
$theme = new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'dschool_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
function dschool_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-post-meta-box' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'footer', 'about' ) );
	//add_theme_support( 'hybrid-core-meta-box-footer' );
	//add_theme_support( 'hybrid-core-drop-downs' );
	add_theme_support( 'hybrid-core-seo' );
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* Add theme support for framework extensions. */
	//add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r', '3c-l', '3c-r', '3c-c' ) );
	//add_theme_support( 'post-stylesheets' );
	//add_theme_support( 'dev-stylesheet' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );
	//add_custom_background();

	/* Add the breadcrumb trail just after the container is open.
	if (!is_front_page()) {
	add_action( "{$prefix}_open_main", 'breadcrumb_trail' );
	}
	*/

	/* Filter the breadcrumb trail arguments. */
	add_filter( 'breadcrumb_trail_args', 'dschool_breadcrumb_trail_args' );

	/* Add the search form to the header. */
	add_action( "{$prefix}_close_header", 'get_search_form' );

	/* Add the logo to the end of the primary menu. */
	add_action( "{$prefix}_close_menu_primary", 'add_small_logo' );	

   /* Additional JS */
    if (!is_admin()) {
    	$url = get_bloginfo('template_url');
    	
 		//wp_deregister_script( 'jquery');
 		
 		/* Register Scripts */
 		wp_register_script( 'modernizr', $url . '/js/modernizr-1.7.min.js','','',false);
 		wp_register_script( 'jquery-cycle', $url . '/js/jquery.cycle.min.js','','',true);
        //wp_register_script( 'jquery-masonry', $url . '/js/jquery.masonry.min.js','','',true);
		wp_register_script( 'jquery-qtip', $url . '/js/jquery.qtip-1.0.0-rc3.min.js','','',true);
		wp_register_script( 'jquery-hoverintent', $url . '/js/jquery.hoverIntent.minified.js','','',true);
		
		/* Enqueue Scripts */
		wp_enqueue_script( 'modernizr' );
		wp_enqueue_script( 'jquery-cycle' );
		//wp_enqueue_script( 'jquery-masonry' );
		wp_enqueue_script( 'jquery-qtip' );
		wp_enqueue_script( 'jquery-hoverintent' );
        
	}
	
	add_filter("{$prefix}_page_meta_box_args", 'quote_meta', $meta);
	add_filter("{$prefix}_page_meta_box_args", 'class_meta', $meta);

}

/**
 * Custom site title (include logo)
 *
 * @since 0.1.0
 */
function dschool_site_title() {
	$tag = ( is_front_page() ) ? 'h1' : 'div';
	$template_url = get_bloginfo('stylesheet_directory');

	if ( $title = get_bloginfo( 'name' ) )
		$title = '<' . $tag . ' id="site-title"><a href="' . home_url() . '" title="' . esc_attr( $title ) . '" rel="home"><img src="' . $template_url . '/images/logo.png" alt="' . $title . '" /> <span>' . $title . '</span></a></' . $tag . '>';

	echo apply_atomic( 'site_title', $title );
}

/**
 * Custom breadcrumb trail arguments.
 *
 * @since 0.1.0
 */
function dschool_breadcrumb_trail_args( $args ) {

	/* Change the text before the breadcrumb trail. */
	$args['before'] = __( '', hybrid_get_textdomain() );

	/* Return the filtered arguments. */
	return $args;
}

function detect_mobile() {
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
		return true;
	}
}


function add_small_logo() {
	$template_url = get_bloginfo('stylesheet_directory');
	echo '<a href="/" title="d.school" class="menu-logo"><img src="' . $template_url . '/images/logo-small.png" alt="d.school" /></a>';
}

function new_excerpt_more($more) {
	global $post;
	return '... <br/><a class="readmore" href="'. get_permalink($post->ID) . '">More</a>';
	//return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');


function quote_meta($meta) {
	
	$domain = hybrid_get_textdomain();

	$meta['quote'] = array( 'name' => 'Quote', 'title' => __( 'Quote:', $domain ), 'type' => 'textarea' );
	return $meta;
}

function class_meta($meta) {
	
	$domain = hybrid_get_textdomain();

	$meta['class-website'] = array( 'name' => 'Class Website', 'title' => __( 'Class Website:', $domain ), 'type' => 'text' );
	return $meta;
}

function get_subpages($id) {
	global $wpdb;

	$query = $wpdb->prepare("
		SELECT wpposts.ID 
		FROM $wpdb->posts wpposts 
		WHERE wpposts.post_status = 'publish' 
		AND wpposts.post_type = 'page' 
		AND wpposts.post_parent = $id 
		ORDER BY wpposts.menu_order ASC
		");

	$subsarray = $wpdb->get_results($query);

	$subs = '';
	foreach ($subsarray as $sub) {
		$subs .= $sub->ID . ',';
	}
	
	return $subs;
}


/******************* CREATE Bio POST TYPE ******************/

add_action('init', 'create_bio_type');

function create_bio_type() 
{
  $labels = array(
    'name' => _x('Bios', 'post type general name'),
    'singular_name' => _x('Bio', 'post type singular name'),
    'add_new' => _x('Add New', 'bio'),
    'add_new_item' => __('Add New Bio'),
    'edit_item' => __('Edit Bio'),
    'new_item' => __('New Bio'),
    'view_item' => __('View Bio'),
    'search_items' => __('Search Bios'),
    'not_found' =>  __('No bios found'),
    'not_found_in_trash' => __('No bios found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    '_builtin' => false,
    'capability_type' => 'page',
    'hierarchical' => false,
    'menu_position' => null,
    'taxonomies' => array(),
    'supports' => array('title','editor','thumbnail','excerpt','custom-fields')
  ); 
  register_post_type('bio',$args);
}

add_filter('post_updated_messages', 'bio_updated_messages');

function bio_updated_messages( $messages ) {

  $messages['bio'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Bio updated. <a href="%s">View bio</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Bio updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Bio restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Bio published. <a href="%s">View bio</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Bio saved.'),
    8 => sprintf( __('Bio submitted. <a target="_blank" href="%s">Preview bio</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Bio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview bio</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Bio draft updated. <a target="_blank" href="%s">Preview bio</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}


/******************* CREATE Student POST TYPE ******************/

add_action('init', 'create_student_type');

function create_student_type() 
{
  $labels = array(
    'name' => _x('Students', 'post type general name'),
    'singular_name' => _x('Student', 'post type singular name'),
    'add_new' => _x('Add New', 'student'),
    'add_new_item' => __('Add New Student'),
    'edit_item' => __('Edit Student'),
    'new_item' => __('New Student'),
    'view_item' => __('View Student'),
    'search_items' => __('Search Students'),
    'not_found' =>  __('No students found'),
    'not_found_in_trash' => __('No students found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    '_builtin' => false,
    'capability_type' => 'page',
    'hierarchical' => false,
    'menu_position' => null,
    'taxonomies' => array(),
    'supports' => array('title','editor','thumbnail','excerpt','custom-fields')
  ); 
  register_post_type('student',$args);
}

add_filter('post_updated_messages', 'student_updated_messages');

function student_updated_messages( $messages ) {

  $messages['student'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Student updated. <a href="%s">View student</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Student updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Student restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Student published. <a href="%s">View student</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Student saved.'),
    8 => sprintf( __('Student submitted. <a target="_blank" href="%s">Preview student</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Student scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview student</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Student draft updated. <a target="_blank" href="%s">Preview student</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

/******************* CREATE PRESS POST TYPE ******************/

add_action('init', 'create_press_type');

function create_press_type() 
{
  $labels = array(
    'name' => _x('Press', 'post type general name'),
    'singular_name' => _x('Press', 'post type singular name'),
    'add_new' => _x('Add New', 'press'),
    'add_new_item' => __('Add New Press Item'),
    'edit_item' => __('Edit Press Item'),
    'new_item' => __('New Press Item'),
    'view_item' => __('View Press Item'),
    'search_items' => __('Search Press'),
    'not_found' =>  __('No press items found'),
    'not_found_in_trash' => __('No press items found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    '_builtin' => false,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'taxonomies' => array(),
    'supports' => array('title','editor','thumbnail','excerpt','custom-fields')
  ); 
  register_post_type('press',$args);
}

add_filter('post_updated_messages', 'press_updated_messages');

function press_updated_messages( $messages ) {

  $messages['press'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Press item updated. <a href="%s">View press item</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Press item updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Press item restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Press item published. <a href="%s">View press item</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Press item saved.'),
    8 => sprintf( __('Press item submitted. <a target="_blank" href="%s">Preview press item</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Press item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview press item</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Press item draft updated. <a target="_blank" href="%s">Preview press item</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

?>