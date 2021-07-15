<?php
// 
// if ( ! defined( 'ABSPATH' ) ) {
//     exit;
// }



// include_once (get_template_directory().'/yosu-woo-wa-co.php');

add_action('admin_menu', 'wooWhatsAppAdminMenu');
function wooWhatsAppAdminMenu(){
   add_submenu_page('woocommerce', 'Woo WhatsApp Joss Mantap Crot', 'WooWhatsAppJosz', 'manage_options', 'woo_whatsapp_admin', 'wooWhatsAppAdminPage' );
}
function wooWhatsAppAdminPage()
{
   require_once get_template_directory() .'/includes/admin-display.php';
}
// End submenu setting

// Add WA Button after add to cart button start
function wooWhatsAppButtonAfterAddToCart()
{
	require_once get_template_directory() . '/includes/public.php';
    
}

// if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
   add_action('woocommerce_after_add_to_cart_button', 'wooWhatsAppButtonAfterAddToCart');
// }
// Add WA Button after add to cart button end
