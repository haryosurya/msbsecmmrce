<?php
/**
 * @link              https://xperts.club/
 * @since             1.0.0
 * @package           Xc_Woo_Whatsapp_Order
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Orders on Whatsapp
 * Plugin URI:        https://wp.xperts.club/wooordersonwhatsapp/
 * Description:       Woocommerce Orders on Whatsapp allows your customers to contact you and chat via Whatsapp directly from your wordpress/woocommerce products pages to the mobile.
 * Version:           1.1.1
 * Author:            XpertsClub
 * Author URI:        https://xperts.club/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       xc-woo-whatsapp-order
 * Domain Path:       /languages
 * Requires at least: 4.4
 * WC requires at least: 3.0.0
 * WC tested up to:   4.9 *
 * Tested up to:      5.6 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'XC_WOO_WHATSAPP_ORDER_VERSION', '1.1.1' );

/**
 * plugin slug.
 */
define( 'XC_WOO_WHATSAPP_ORDER_SLUG', 'xc-woo-whatsapp-order' );


/**
 * Currently plugin name.
 */
define( 'XC_WOO_WHATSAPP_ORDER_NAME', 'Woocommerce Orders on Whatsapp' );

// Define XC_WOO_WHATSAPP_OORDER_FILE.
if ( ! defined( 'XC_WOO_WHATSAPP_ORDER_FILE' ) ) {
	define( 'XC_WOO_WHATSAPP_ORDER_FILE', __FILE__ );
}

if ( ! defined( 'XC_WOO_WHATSAPP_ORDER_BASENAME' ) ) {
	define( 'XC_WOO_WHATSAPP_ORDER_BASENAME', plugin_basename( XC_WOO_WHATSAPP_ORDER_FILE ) );
}

/**
 * plugin directory.
 */
define( 'XC_WOO_WHATSAPP_ORDER_DIR', untrailingslashit(plugin_dir_path(__FILE__)) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-xc-woo-whatsapp-order-activator.php
 */
function activate_xc_woo_whatsapp_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xc-woo-whatsapp-order-activator.php';
	Xc_Woo_Whatsapp_Order_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-xc-woo-whatsapp-order-deactivator.php
 */
function deactivate_xc_woo_whatsapp_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-xc-woo-whatsapp-order-deactivator.php';
	Xc_Woo_Whatsapp_Order_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_xc_woo_whatsapp_order' );
register_deactivation_hook( __FILE__, 'deactivate_xc_woo_whatsapp_order' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-xc-woo-whatsapp-order.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_xc_woo_whatsapp_order() {
	$plugin = new Xc_Woo_Whatsapp_Order();
	$plugin->run();

}

/*
* check Woocommerce Activation
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php') && is_plugin_active('redux-framework/redux-framework.php') ){
	run_xc_woo_whatsapp_order();
} else {
	add_action( 'admin_notices', 'xc_woo_whatsapp_order_installed_notice' );
}

function xc_woo_whatsapp_order_installed_notice()
{
	?>
    <div class="error">
      <p><?php _e( 'Woocommerce Orders on Whatsapp requires the WooCommerce & Redux Framework plugin. Please install or activate them before!', 'xc-woo-whatsapp-order'); ?></p>
    </div>
    <?php
}