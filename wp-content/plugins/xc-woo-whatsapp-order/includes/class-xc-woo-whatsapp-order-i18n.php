<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://xperts.club/
 * @since      1.0.0
 *
 * @package    Xc_Woo_Whatsapp_Order
 * @subpackage Xc_Woo_Whatsapp_Order/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Xc_Woo_Whatsapp_Order
 * @subpackage Xc_Woo_Whatsapp_Order/includes
 * @author     XpertsClub <admin@xperts.club>
 */
class Xc_Woo_Whatsapp_Order_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'xc-woo-whatsapp-order',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
