<?php
/**
 * functions.php
 * @package WordPress
 * @subpackage Bacola
 * @since Bacola 1.0.4
 * 
 */

/*************************************************
## Admin style and scripts  
*************************************************/ 
update_option( 'envato_purchase_code_32552148','B5E0B5F8-DD8689E6-ACA49DD6-E6E1A930' );

function bacola_admin_styles() {
	wp_enqueue_style('bacola-klbtheme',   get_template_directory_uri() .'/assets/css/admin/klbtheme.css');
	wp_enqueue_script('bacola-init', 	  get_template_directory_uri() .'/assets/js/init.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('bacola-register',  get_template_directory_uri() .'/assets/js/admin/register.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'bacola_admin_styles');

 /*************************************************
## Bacola Fonts
*************************************************/
function bacola_fonts_url_inter() {
	$fonts_url = '';

	$inter = _x( 'on', 'Inter font: on or off', 'bacola' );		

	if ( 'off' !== $inter ) {
		$font_families = array();

		if ( 'off' !== $inter ) {
		$font_families[] = 'Inter:wght@100;200;300;400;500;600;700;800;900';
		}
		
		$query_args = array( 
		'family' => rawurldecode( implode( '|', $font_families ) ), 
		'subset' => rawurldecode( 'latin,latin-ext' ), 
		); 
		 
		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css2' );
	}
 
	return esc_url_raw( $fonts_url );
}

function bacola_fonts_url_dosis() {
	$fonts_url = '';

	$dosis = _x( 'on', 'Dosis font: on or off', 'bacola' );	

	if ( 'off' !== $dosis ) {
		$font_families = array();

		if ( 'off' !== $dosis ) {
		$font_families[] = 'Dosis:wght@200;300;400;500;600;700;800';
		}
		
		$query_args = array( 
		'family' => rawurldecode( implode( '|', $font_families ) ), 
		'subset' => rawurldecode( 'latin,latin-ext' ), 
		); 
		 
		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css2' );
	}
 
	return esc_url_raw( $fonts_url );
}

/*************************************************
## Styles and Scripts
*************************************************/ 
define('BACOLA_INDEX_CSS', 	  get_template_directory_uri()  . '/assets/css');
define('BACOLA_INDEX_JS', 	  get_template_directory_uri()  . '/assets/js');
define('BACOLA_INDEX_FONTS',    get_template_directory_uri()  . '/assets/fonts');

function bacola_scripts() {

	if ( is_admin_bar_showing() ) {
		wp_enqueue_style( 'bacola-klbtheme', BACOLA_INDEX_CSS . '/admin/klbtheme.css', false, '1.0');    
	}	

	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

	wp_enqueue_style( 'bootstrap', 				BACOLA_INDEX_CSS . '/bootstrap.min.css', false, '1.0');
	wp_enqueue_style( 'select2', 				BACOLA_INDEX_CSS . '/select2.min.css', false, '1.0');
	wp_enqueue_style( 'bacola-base', 			BACOLA_INDEX_CSS . '/base.css', false, '1.0');
	wp_style_add_data( 'bacola-base', 'rtl', 'replace' );
	wp_enqueue_style( 'bacola-font-dmsans',  	bacola_fonts_url_inter(), array(), null );
	wp_enqueue_style( 'bacola-font-crimson',  	bacola_fonts_url_dosis(), array(), null );
	wp_enqueue_style( 'bacola-style',         	get_stylesheet_uri() );
	wp_style_add_data( 'bacola-style', 'rtl', 'replace' );

	$mapkey = get_theme_mod('bacola_mapapi');

	wp_enqueue_script( 'imagesloaded');
	wp_enqueue_script( 'bootstrap-bundle',    	 BACOLA_INDEX_JS . '/bootstrap.bundle.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'select2-full',    	 	 BACOLA_INDEX_JS . '/select2.full.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'gsap',    	    		 BACOLA_INDEX_JS . '/vendor/gsap.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'jquery-magnific-popup',  BACOLA_INDEX_JS . '/vendor/jquery.magnific-popup.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'perfect-scrolllbar',     BACOLA_INDEX_JS . '/vendor/perfect-scrollbar.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script( 'slick',    	    	 	 BACOLA_INDEX_JS . '/vendor/slick.min.js', array('jquery'), '1.0', true);
	wp_register_script( 'bacola-googlemap',    '//maps.googleapis.com/maps/api/js?key='. $mapkey .'', array('jquery'), '1.0', true);
	wp_enqueue_script( 'bacola-bundle',     	 BACOLA_INDEX_JS . '/bundle.js', array('jquery'), '1.0', true);

}
add_action( 'wp_enqueue_scripts', 'bacola_scripts' );

/*************************************************
## Theme Setup
*************************************************/ 

if ( ! isset( $content_width ) ) $content_width = 960;

function bacola_theme_setup() {
	
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-background' );
	add_theme_support( 'post-formats', array('gallery', 'audio', 'video'));
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'woocommerce', array('gallery_thumbnail_image_width' => 99,'thumbnail_image_width' => 90,) );
	load_theme_textdomain( 'bacola', get_template_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'bacola_theme_setup' );


/*************************************************
## Include the TGM_Plugin_Activation class.
*************************************************/ 

require_once get_template_directory() . '/includes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'bacola_register_required_plugins' );

function bacola_register_required_plugins() {

	$url = 'http://klbtheme.com/bacola/plugins/';
	$mainurl = 'http://klbtheme.com/plugins/';

	$plugins = array(
		
        array(
            'name'                  => esc_html__('Meta Box','bacola'),
            'slug'                  => 'meta-box',
        ),

        array(
            'name'                  => esc_html__('Contact Form 7','bacola'),
            'slug'                  => 'contact-form-7',
        ),
		
		array(
            'name'                  => esc_html__('WooCommerce Wishlist','bacola'),
            'slug'                  => 'ti-woocommerce-wishlist',
        ),
		
		array(
            'name'                  => esc_html__('WooCommerce Compare','bacola'),
            'slug'                  => 'woo-smart-compare',
        ),
		
        array(
            'name'                  => esc_html__('Kirki','bacola'),
            'slug'                  => 'kirki',
        ),
		
		array(
            'name'                  => esc_html__('MailChimp Subscribe','bacola'),
            'slug'                  => 'mailchimp-for-wp',
        ),
		
        array(
            'name'                  => esc_html__('Elementor','bacola'),
            'slug'                  => 'elementor',
            'required'              => true,
        ),
		
        array(
            'name'                  => esc_html__('WooCommerce','bacola'),
            'slug'                  => 'woocommerce',
            'required'              => true,
        ),
		
		array(
            'name'                  => esc_html__('Variation Swatches','bacola'),
            'slug'                  => 'woo-variation-swatches',
        ),

		array(
            'name'                  => esc_html__('WP Ajax Search','bacola'),
            'slug'                  => 'ajax-search-for-woocommerce',
        ),

        array(
            'name'                  => esc_html__('Bacola Core','bacola'),
            'slug'                  => 'bacola-core',
            'source'                => $url . 'bacola-core.zip',
            'required'              => true,
            'version'               => '1.0.5',
            'force_activation'      => false,
            'force_deactivation'    => false,
            'external_url'          => '',
        ),

        array(
            'name'                  => esc_html__('Envato Market','bacola'),
            'slug'                  => 'envato-market',
            'source'                => $mainurl . 'envato-market.zip',
            'required'              => true,
            'version'               => '2.0.6',
            'force_activation'      => false,
            'force_deactivation'    => false,
            'external_url'          => '',
        ),


	);

	$config = array(
		'id'           => 'bacola',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

/*************************************************
## Bacola Register Menu 
*************************************************/

function bacola_register_menus() {
	register_nav_menus( array( 'main-menu' 	   => esc_html__('Primary Navigation Menu','bacola')) );

	if(get_theme_mod('bacola_footer_menu',0) == '1'){
		register_nav_menus( array( 'footer-menu'     => esc_html__('Footer Menu','bacola')) );
	}
	
	$topheader = get_theme_mod('bacola_top_header','0');
	$sidebarmenu = get_theme_mod('bacola_header_sidebar','0');

	if($sidebarmenu == '1'){
		register_nav_menus( array( 'sidebar-menu'     => esc_html__('Sidebar Menu','bacola')) );
	}
	
	if($topheader == '1'){
		register_nav_menus( array( 'canvas-bottom' 	   => esc_html__('Canvas Bottom','bacola')) );
		register_nav_menus( array( 'top-right-menu'    => esc_html__('Top Right Menu','bacola')) );
		register_nav_menus( array( 'top-left-menu'     => esc_html__('Top Left Menu','bacola')) );
	}
}
add_action('init', 'bacola_register_menus');

/*************************************************
## Bacola Main Menu
*************************************************/ 
class bacola_main_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'',
			( $display_depth % 2  ? '' : '' ),
			( $display_depth >=2 ? '' : '' ),
			
			);
		$class_names = implode( ' ', $classes );
	  
		// build html
		$output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
	}

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
      function start_el(&$output, $object, $depth = 0, $args = Array() , $current_object_id = 0) {
           
           global $wp_query;

           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';
		   
		   $classes = empty( $object->classes ) ? array() : (array) $object->classes;
           $icon_class = $classes[0];
		   $classes = array_slice($classes,1);
		   
		   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );
		   
		   if ( $args->has_children ) {
		   $class_names = 'class="dropdown '.esc_attr($icon_class).' '. esc_attr( $class_names ) . '"';
		   } else {
		   $class_names = 'class=" '. esc_attr( $class_names ) . '"';
		   }
			
			$output .= $indent . '<li ' . $value . $class_names .'>';

			$datahover = str_replace(' ','',$object->title);


			$attributes = ! empty( $object->url ) ? ' href="'   . esc_attr( $object->url ) .'"' : '';

				
			$object_output = $args->before;

			$object_output .= '<a'. $attributes .'  >';
			if($icon_class){
			$object_output .= '<i class="'.esc_attr($icon_class).'"></i> ';
			}
			$object_output .= $args->link_before .  apply_filters( 'the_title', $object->title, $object->ID ) . '';
	        $object_output .= $args->link_after;
			$object_output .= '</a>';


			$object_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $object_output, $object, $depth, $args );            	              	
      }
}

/*************************************************
## Bacola Sidebar Menu
*************************************************/ 
class bacola_sidebar_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'',
			( $display_depth % 2  ? '' : '' ),
			( $display_depth >=2 ? '' : '' ),
			
			);
		$class_names = implode( ' ', $classes );
	  
		// build html
		$output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
	}

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
      function start_el(&$output, $object, $depth = 0, $args = Array() , $current_object_id = 0) {
           
           global $wp_query;

           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';
		   
		   $classes = empty( $object->classes ) ? array() : (array) $object->classes;
		   $myclasses = empty( $object->classes ) ? array() : (array) $object->classes;
           $icon_class = $classes[0];
		   $classes = array_slice($classes,1);
		   
		 
		   
		   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $object ) );
		   
		   if ( $args->has_children ) {
		   $class_names = 'class="category-parent parent  '. esc_attr( $class_names ) . '"';
		   }elseif(in_array('bottom',$myclasses)){
		   $class_names = 'class="link-parent  '. esc_attr( $class_names ) . '"';   
		   } else {
		   $class_names = 'class="category-parent  '. esc_attr( $class_names ) . '"';
		   }
			
			$output .= $indent . '<li ' . $value . $class_names .'>';

			$datahover = str_replace(' ','',$object->title);


			$attributes = ! empty( $object->url ) ? ' href="'   . esc_attr( $object->url ) .'"' : '';

				
			$object_output = $args->before;

			$object_output .= '<a'. $attributes .'  >';
			if($icon_class){
			$object_output .= '<i class="'.esc_attr($icon_class).'"></i> ';
			}
			$object_output .= $args->link_before .  apply_filters( 'the_title', $object->title, $object->ID ) . '';
	        $object_output .= $args->link_after;
			$object_output .= '</a>';


			$object_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $object_output, $object, $depth, $args );            	              	
      }
}

/*************************************************
## Excerpt More
*************************************************/ 

function bacola_excerpt_more($more) {
  global $post;
  return '<div class="klb-readmore entry-button"><a class="button" href="'. esc_url(get_permalink($post->ID)) . '">' . esc_html__('Read More', 'bacola') . '</a></div>';
  }
 add_filter('excerpt_more', 'bacola_excerpt_more');
 
/*************************************************
## Word Limiter
*************************************************/ 
function bacola_limit_words($string, $limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $limit));
}

/*************************************************
## Widgets
*************************************************/ 

function bacola_widgets_init() {
	register_sidebar( array(
	  'name' => esc_html__( 'Blog Sidebar', 'bacola' ),
	  'id' => 'blog-sidebar',
	  'description'   => esc_html__( 'These are widgets for the Blog page.','bacola' ),
	  'before_widget' => '<div class="widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Shop Sidebar', 'bacola' ),
	  'id' => 'shop-sidebar',
	  'description'   => esc_html__( 'These are widgets for the Shop.','bacola' ),
	  'before_widget' => '<div class="widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer First Column', 'bacola' ),
	  'id' => 'footer-1',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Second Column', 'bacola' ),
	  'id' => 'footer-2',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Third Column', 'bacola' ),
	  'id' => 'footer-3',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Fourth Column', 'bacola' ),
	  'id' => 'footer-4',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );

	register_sidebar( array(
	  'name' => esc_html__( 'Footer Fifth Column', 'bacola' ),
	  'id' => 'footer-5',
	  'description'   => esc_html__( 'These are widgets for the Footer.','bacola' ),
	  'before_widget' => '<div class="klbfooterwidget widget %2$s">',
	  'after_widget'  => '</div>',
	  'before_title'  => '<h4 class="widget-title">',
	  'after_title'   => '</h4>'
	) );
}
add_action( 'widgets_init', 'bacola_widgets_init' );
 
/*************************************************
## Bacola Comment
*************************************************/

if ( ! function_exists( 'bacola_comment' ) ) :
 function bacola_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ) :
   case 'pingback' :
   case 'trackback' :
  ?>

   <article class="post pingback">
   <p><?php esc_html_e( 'Pingback:', 'bacola' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( '(Edit)', 'bacola' ), ' ' ); ?></p>
  <?php
    break;
   default :
  ?>
  
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-avatar">
				<div class="comment-author vcard">
					<img src="<?php echo get_avatar_url( $comment, 90 ); ?>" alt="<?php comment_author(); ?>" class="avatar">
				</div>
			</div>
			<div class="comment-content">
				<div class="comment-meta">
					<b class="fn"><a class="url"><?php comment_author(); ?></a></b>
					<div class="comment-metadata">
						<time><?php comment_date(); ?></time>
					</div>
				</div>
				<div class="klb-post">
					<?php comment_text(); ?>
					<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'bacola' ); ?></em>
					<?php endif; ?>
				</div>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
			</div>

		</div>
	</li>


  <?php
    break;
  endswitch;
 }
endif;

/*************************************************
## Bacola Widget Count Filter
 *************************************************/

function bacola_cat_count_span($links) {
  $links = str_replace('</a> (', '</a> <span class="catcount">(', $links);
  $links = str_replace(')', ')</span>', $links);
  return bacola_sanitize_data($links);
}
add_filter('wp_list_categories', 'bacola_cat_count_span');
 
function bacola_archive_count_span( $links ) {
	$links = str_replace( '</a>&nbsp;(', '</a><span class="catcount">(', $links );
	$links = str_replace( ')', ')</span>', $links );
	return bacola_sanitize_data($links);
}
add_filter( 'get_archives_link', 'bacola_archive_count_span' );


/*************************************************
## Pingback url auto-discovery header for single posts, pages, or attachments
 *************************************************/
function bacola_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'bacola_pingback_header' );

/************************************************************
## DATA CONTROL FROM PAGE METABOX OR ELEMENTOR PAGE SETTINGS
*************************************************************/
function bacola_page_settings( $opt_id){
	
	if ( class_exists( '\Elementor\Core\Settings\Manager' ) ) {
		// Get the current post id
		$post_id = get_the_ID();

		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model( $post_id );

		// Retrieve the color we added before
		$output = $page_settings_model->get_settings( 'bacola_elementor_'.$opt_id );
		
		return $output;
	}
}

/*************************************************
## Bacola Get options
*************************************************/
function bacola_get_option(){	
	$getopt  = isset( $_GET['opt'] ) ? $_GET['opt'] : '';

	return esc_html($getopt);
}
/*************************************************
## yosu customize
*************************************************/
/**

* Add custom field to the checkout page

*/

add_action('woocommerce_after_order_notes', 'custom_checkout_field');
function custom_checkout_field($checkout)
{
echo '<div id="custom_checkout_field"><h2>' . __('New Heading') . '</h2>';
woocommerce_form_field('custom_field_name', array(
'type' => 'text',
'class' => array(
'my-field-class form-row-wide'
) ,
'label' => __('Custom Additional Field') ,
'placeholder' => __('New Custom Field') ,
) ,
$checkout->get_value('custom_field_name'));
echo '</div>';
}

// function my_custom_login_stylesheet() {
//     wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/custom-login.css' );
// 	echo '
//     <style type="text/css">
// 	#login h1 a, .login h1 a { background-image: url('.esc_url( wp_get_attachment_url(get_theme_mod( 'bacola_logo' ))).'); height: 65 px; width: 320px; background-size: 320px 65px; background-repeat: no-repeat; margin: 0 auto;  }
//     </style>'.
// 	''
//  ;
// }

//This loads the function above on the login page
// add_action( 'login_enqueue_scripts', 'my_custom_login_stylesheet' );




function isMobile() {
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}


add_action('admin_menu', 'wooWhatsAppAdminMenu');
function wooWhatsAppAdminMenu(){
   add_submenu_page('woocommerce', 'Woo WhatsApp Joss Mantap Crot', 'WooWhatsAppJosz', 'manage_options', 'woo_whatsapp_admin', 'wooWhatsAppAdminPage' );
}
function wooWhatsAppAdminPage()
{
   require_once get_template_directory() .'/includes/admin-display.php';
}


function my_login_logo() { 
	echo '
    <style type="text/css">
	.login h1{margin-bottom:-20px;}
	#login h1 a, .login h1 a { background-image: url('.esc_url( wp_get_attachment_url(get_theme_mod( 'bacola_logo' ))).'); height: 65 px; width: 320px; background-size: 320px 65px; background-repeat: no-repeat; margin: 0 auto;  }
	body.login {
		position: relative;
		height: 100%;
		background-image: url("https://i.imgur.com/UP7fWfg.jpg");
		background-size: cover;
		overflow: auto;
		font-family: "Open Sans", Helvetica, Arial, sans-serif;
	}
	.login label, .login #backtoblog a, .login #nav a{color:#fff}
	#loginform {background: linear-gradient(to bottom, rgba(146, 135, 187, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);border:none;}
	.login #login_error, .login .message, .login .success ,.login form {
		position: relative;
		height: 100%;
		background: -webkit-linear-gradient(top, rgba(146, 135, 187, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);
		background: linear-gradient(to bottom, rgba(146, 135, 187, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);
		-webkit-transition: opacity 0.1s, -webkit-transform 0.3s cubic-bezier(0.17, -0.65, 0.665, 1.25);
		transition: opacity 0.1s, transform 0.3s cubic-bezier(0.17, -0.65, 0.665, 1.25);
		-webkit-transform: scale(1);
		-ms-transform: scale(1);
		transform: scale(1);
		
		
	}

	body.login div#login form#loginform p.submit input#wp-submit  {
		position: relative;
		width: 100%;
		height: 4rem;
		margin: 5rem 0 2.2rem;
		color: rgba(255, 255, 255, 0.8);
		background: #28d214;
		font-size: 1.5rem;
		/* border-radius: 3rem; */
		cursor: pointer;
		overflow: hidden;
		-webkit-transition: width 0.3s 0.15s, font-size 0.1s 0.15s;
				transition: width 0.3s 0.15s, font-size 0.1s 0.15s;
	  }
	  body.login div#login form#loginform p.submit input#wp-submit:after {
		content: "";
		position: absolute;
		top: 50%;
		left: 50%;
		margin-left: -1.5rem;
		margin-top: -1.5rem;
		width: 3rem;
		height: 3rem;
		border: 2px dotted #fff;
		border-radius: 50%;
		border-left: none;
		border-bottom: none;
		-webkit-animation: rotate 0.5s infinite linear;
				animation: rotate 0.5s infinite linear;
		-webkit-transition: opacity 0.1s 0.4s;
				transition: opacity 0.1s 0.4s;
		opacity: 0;
	  }
	  body.login div#login form#loginform p.submit input#wp-submit.processing {
		width: 4rem;
		font-size: 0;
	  }
	  body.login div#login form#loginform p.submit input#wp-submit.processing:after {
		opacity: 1;
	  }
	  body.login div#login form#loginform p.submit input#wp-submit.success {
		-webkit-transition: -webkit-transform 0.3s 0.1s ease-out, opacity 0.1s 0.3s, background-color 0.1s 0.3s;
				transition: transform 0.3s 0.1s ease-out, opacity 0.1s 0.3s, background-color 0.1s 0.3s;
		-webkit-transform: scale(30);
			-ms-transform: scale(30);
				transform: scale(30);
		opacity: 0.9;
	  }
	  body.login div#login form#loginform p.submit input#wp-submit.success:after {
		-webkit-transition: opacity 0.1s 0s;
				transition: opacity 0.1s 0s;
		opacity: 0;
	  }
    </style>'.
	''
 ;}
add_action( 'login_enqueue_scripts', 'my_login_logo' );


function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
	$bname = get_option('blogname')." ".get_option('blogdescription');
    return $bname;
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

// add_filter('site_url',  'login_filter', 10, 3);
// function login_filter( $url, $path, $orig_scheme )
// {
//  $old  = array( "/(wp-login\.php)/");
//  $new  = array( "login");
//  return preg_replace( $old, $new, $url, 1);
// }






function mixano_load_scripts(){
	
		wp_enqueue_script( 'lazy', BACOLA_INDEX_JS . "/js/lazy.js", array( 'jquery' ), '', true ); //no need to prefix as it's 3rd party script
		// wp_localize_script( 'lazy', 'mixano_object_name', $data );
	// }
	
}
add_action( 'wp_enqueue_scripts' , 'mixano_load_scripts' , 1 );

function yosu_add_shadowlogin() {
	//get htaccess file path
	$htaccess_file = wp_normalize_path( ABSPATH . '.htaccess' );

	if ( file_exists( $htaccess_file ) ) {
		
		$current_htaccess = file_get_contents( $htaccess_file );

		//unique string
		$unique = 'yosushadowlogin';
		
		$exists = false;

		//code is already added
		if ( strpos( $current_htaccess, $unique ) !== false ) {
			$exists = true;
		}
		
		//code is inexistent so we add it
		if ( ! $exists ) {

			$sdlogin  = "\n";
			$sdlogin .= '# yosushadowlogin BEGIN Hide login page' . "\n";
			$sdlogin .= '<IfModule mod_expires.c>' . "\n";
			$sdlogin .= 'RewriteRule ^mylogin$ https://%{SERVER_NAME}/wp-login.php?key=123&redirect_to=https://%{SERVER_NAME}/wp-admin/index.php [L]' . "\n";
			$sdlogin .= 'RewriteCond %{HTTP_REFERER} !^https://%{SERVER_NAME}/wp-admin' . "\n";
			$sdlogin .= 'RewriteCond %{HTTP_REFERER} !^https://%{SERVER_NAME}/wp-login.php' . "\n";
			$sdlogin .= 'RewriteCond %{HTTP_REFERER} !^https://%{SERVER_NAME}/login' . "\n";
			$sdlogin .= 'RewriteCond %{QUERY_STRING} !^key=123' . "\n";
			$sdlogin .= 'RewriteCond %{QUERY_STRING} !^action=logout' . "\n";
			$sdlogin .= 'RewriteCond %{QUERY_STRING} !^action=lostpassword' . "\n";
			$sdlogin .= 'RewriteCond %{REQUEST_METHOD} !POST' . "\n";
			$sdlogin .= '</IfModule>' . "\n";
			$sdlogin  = "\n";
			$sdlogin .= '# END Hide login page yosushadowlogin' . "\n";

			if ( is_readable( $htaccess_file ) && is_writable( $htaccess_file ) ) {
				$final_htaccess = '';
				$final_htaccess .= $current_htaccess.$sdlogin;
				file_put_contents( $htaccess_file, $final_htaccess );
			}
		
		}
	
	} //enf if htaccess_file exists
}
yosu_add_shadowlogin();


function yosu_add_leverage_broswer_caching() {
	//get htaccess file path
	$htaccess_file = wp_normalize_path( ABSPATH . '.htaccess' );

	if ( file_exists( $htaccess_file ) ) {
		
		$current_htaccess = file_get_contents( $htaccess_file );

		//unique string
		$unique = 'yosuBROWSERCACHE';
		
		$exists = false;

		//code is already added
		if ( strpos( $current_htaccess, $unique ) !== false ) {
			$exists = true;
		}
		
		//code is inexistent so we add it
		if ( ! $exists ) {

			$expires  = "\n";
			$expires .= '# yosuBROWSERCACHESTART Browser Caching' . "\n";
			$expires .= '<IfModule mod_expires.c>' . "\n";
			$expires .= 'ExpiresActive On' . "\n";
			$expires .= 'ExpiresByType image/gif "access 1 year"' . "\n";
			$expires .= 'ExpiresByType image/jpg "access 1 year"' . "\n";
			$expires .= 'ExpiresByType image/jpeg "access 1 year"' . "\n";
			$expires .= 'ExpiresByType image/png "access 1 year"' . "\n";
			$expires .= 'ExpiresByType image/x-icon "access 1 year"' . "\n";
			$expires .= 'ExpiresByType text/css "access 1 month"' . "\n";
			$expires .= 'ExpiresByType text/javascript "access 1 month"' . "\n";
			$expires .= 'ExpiresByType text/html "access 1 month"' . "\n";
			$expires .= 'ExpiresByType application/javascript "access 1 month"' . "\n";
			$expires .= 'ExpiresByType application/x-javascript "access 1 month"' . "\n";
			$expires .= 'ExpiresByType application/xhtml-xml "access 1 month"' . "\n";
			$expires .= 'ExpiresByType application/pdf "access 1 month"' . "\n";
			$expires .= 'ExpiresByType application/x-shockwave-flash "access 1 month"' . "\n";
			$expires .= 'ExpiresDefault "access 1 month"' . "\n";
			$expires .= '</IfModule>' . "\n";
			$expires .= '# END Caching yosuBROWSERCACHEEND' . "\n";

			if ( is_readable( $htaccess_file ) && is_writable( $htaccess_file ) ) {
				$final_htaccess = '';
				$final_htaccess .= $current_htaccess.$expires;
				file_put_contents( $htaccess_file, $final_htaccess );
			}
		
		}
	
	} //enf if htaccess_file exists
}
yosu_add_leverage_broswer_caching();




function yosu_enable_gzip() {
	//get htaccess file path
	$htaccess_file = wp_normalize_path( ABSPATH . '.htaccess' );

	if ( file_exists( $htaccess_file ) ) {
		
		$current_htaccess = file_get_contents( $htaccess_file );

		//unique string
		$unique = 'yosuGZIPSTART';
		
		$exists = false;

		//code is already added
		if ( strpos( $current_htaccess, $unique ) !== false ) {
			$exists = true;
		}
		
		//code is inexistent so we add it
		if ( ! $exists ) {

			$gzip  = "\n";
			$gzip .= '# yosuGZIPSTART' . "\n";
			$gzip .= '<IfModule mod_deflate.c>' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/javascript' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/rss+xml' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/vnd.ms-fontobject' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/x-font' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/x-font-opentype' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/x-font-otf' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/x-font-truetype' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/x-font-ttf' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/x-javascript' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/xhtml+xml' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE application/xml' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE font/opentype' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE font/otf' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE font/ttf' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE image/svg+xml' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE image/x-icon' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE text/css' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE text/html' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE text/javascript' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE text/plain' . "\n";
			$gzip .= 'AddOutputFilterByType DEFLATE text/xml' . "\n";
			$gzip .= '</IfModule>' . "\n";
			$gzip .= '# yosuGZIPEND' . "\n";

			if ( is_readable( $htaccess_file ) && is_writable( $htaccess_file ) ) {
				$final_htaccess = '';
				$final_htaccess .= $current_htaccess.$gzip;
				file_put_contents( $htaccess_file, $final_htaccess );
			}
		
		}
	
	} //enf if htaccess_file exists
}
yosu_enable_gzip();

add_filter( 'woocommerce_cart_total', 'custom_total_message' );
function custom_total_message( $price ) {
    $msg = '<br><a style="color:#ea3319;">Harga Belum termasuk ongkir.</a><br />';

    return $price . $msg;
}

add_action('woocommerce_before_checkout_form', 'my_custom_message');
function my_custom_message() {
	$pesannya = '';
    if ( ! is_user_logged_in() ) {
        // wc_print_notice( __('This is my custom message'), 'asaasa' );
		echo '<div class="woocommerce-form-coupon-toggle"> <div class="woocommerce-info"> Dapatkan Promo Menarik dengan cara Register <a href="'.site_url('/my-account-2/', 'https' ).'" class="">Klik di sini </a>untuk register	</div> </div>';
    }
}

/*************************************************
## yosu customize
*************************************************/







/*************************************************
## Bacola Theme options
*************************************************/

	require_once get_template_directory() . '/includes/metaboxes.php';
	require_once get_template_directory() . '/includes/woocommerce.php';
	require_once get_template_directory() . '/includes/woocommerce-filter.php';
	require_once get_template_directory() . '/includes/sanitize.php';
	require_once get_template_directory() . '/includes/merlin/theme-register.php';
	require_once get_template_directory() . '/includes/merlin/setup-wizard.php';
