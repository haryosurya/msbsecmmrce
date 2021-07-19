<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://xperts.club/
 * @since      1.0.0
 *
 * @package    Xc_Woo_Whatsapp_Order
 * @subpackage Xc_Woo_Whatsapp_Order/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Xc_Woo_Whatsapp_Order
 * @subpackage Xc_Woo_Whatsapp_Order/public
 * @author     XpertsClub <admin@xperts.club>
 */
class Xc_Woo_Whatsapp_Order_Public {

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
    private $options;
	private $ismobile;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
	
	/*
	* initilize plugin functions
	*/
	public function init(){
		global $xc_woo_whatsapp_order;
		$this->options = $xc_woo_whatsapp_order;	
		$this->ismobile = $this->is_mobile();
		
		if(!$this->get_option('enable')){
			return false;
		}
		
		if(!$this->xc_order_on_whatsapp_users()) {	
		 	return false;	
		 }
		 
		 add_action('wp_head', array($this, 'xc_hard_hide'),100);
		 
		 if($this->get_option('redirect')){
			 add_action('wp', array($this, 'xc_order_on_whatsapp_page_redirect'));
		 }
		 
		 if(!$this->get_option('enable-whatsapp-cart')){
			 // display whatsapp button on loop
			 if ($this->get_option('enable-on-category')) {
				 add_action('woocommerce_after_shop_loop_item', array($this, 'xc_order_on_whatsapp_shortcode_category'), 11);
			 }
			 
			 // display whatsapp button on single product
			 add_action('woocommerce_single_product_summary', array($this, 'xc_order_on_whatsapp_shortcode_single_product'), 31);
		 }else{
			add_filter( 'woocommerce_product_add_to_cart_text' , array($this, 'whatsapp_cart_button_text'));
			add_filter( 'woocommerce_product_single_add_to_cart_text' , array($this, 'whatsapp_cart_button_text')); 
			
			if($this->get_option('remove-coupons')) {
				add_filter( 'woocommerce_coupons_enabled' , '__return_false');
			}
			
			if($this->get_option('remove-cross-sells')) {
				remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
			}
			
			if(!$this->get_option('enable-whatsapp-checkout', false)){
				remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals');
				add_action('woocommerce_cart_actions', array($this, 'add_buy_on_whatsapp_button'));
			}
			
			if(apply_filters("xc_woo_whatsapp_order_cart_display_cart_total", true)){
				add_action( 'woocommerce_cart_contents', array($this, 'display_cart_total') );
			}
		 }
		 
		 add_action( 'wp_footer',   array($this, 'footer_html') );
		 
		 add_action('wp_ajax_xc_woo_whatsapp_order_send', array($this, 'xc_woo_whatsapp_order_send'));
		 add_action('wp_ajax_nopriv_xc_woo_whatsapp_order_send', array($this, 'xc_woo_whatsapp_order_send'));
		
		if(!is_admin()){
			add_filter( 'woocommerce_is_purchasable', array($this, 'is_purchasable'), 99, 2);
			add_filter( 'woocommerce_loop_add_to_cart_link', array($this, 'change_add_to_cart_btn'), 10, 3);
			add_filter( 'woocommerce_get_price_html', array($this, 'prie_html'), 10, 2);
			
			if ($this->get_option('remove-price') === "1"){
				add_filter( 'woocommerce_cart_item_price', '__return_false' );
				add_filter( 'woocommerce_cart_item_subtotal', '__return_false' );
			}
			
		}
		
		if($this->get_option('enable-dokan-whatsapp-cart') && class_exists('WeDevs_Dokan')){
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_cart' ), 10, 3 );	
			
		}
		
	}

    /**
     * Get Option Values from option-init and return .
     *
     * @since    1.0.0
     */
    public function get_option($option, $default="") {

        if (!is_array($this->options)) {
            return false;
        }
        if (array_key_exists($option, $this->options)) {
            return $this->options[$option];
        } else {
			if(!empty($default)) return $default;
            return false;
        }
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/xc-woo-whatsapp-order-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/xc-woo-whatsapp-order-public.js', array('jquery'), $this->version, false);
		
		$data = array(
				'nonce'						  => wp_create_nonce( 'xc_woo_whatsapp_order_nonce' ),
				'ajax_url'                    => admin_url( 'admin-ajax.php' ),
		);
		$name = str_replace('-','_',$this->plugin_name);
		wp_localize_script( $this->plugin_name, $name."_public_params",  $data );
    }
	
	/**
     * Check Mobile or not
     *
     * @since    1.0.7
     */
	public function is_mobile(){
		if ( function_exists( 'wp_is_mobile' ) ) {
			return wp_is_mobile();	
		}else{
			if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				$is_mobile = false;
			} elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) !== false // many mobile devices (all iPhone, iPad, etc.)
				|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) !== false
				|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Silk/' ) !== false
				|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Kindle' ) !== false
				|| strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' ) !== false
				|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false
				|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false ) {
					$is_mobile = true;
			} else {
				$is_mobile = false;
			}
			return apply_filters( 'wp_is_mobile', $is_mobile );
		}
		return false;
	}
	
	public function get_whatsapp_urlbase(){
		if($this->is_mobile()){
			return apply_filters('xc_woo_whatsapp_order_whatsapp_urlbase', 'https://api.whatsapp.com/', $this->is_mobile());	
		}else{
			return apply_filters('xc_woo_whatsapp_order_whatsapp_urlbase', 'https://web.whatsapp.com/', $this->is_mobile());		
		}
	}

    /**
     * Check User Limitation 
     *
     * @since    1.0.0
     */
    public function xc_order_on_whatsapp_users() {
        $current_user = wp_get_current_user();
        if ($this->get_option('apply-user') === "all" || empty($this->get_option('apply-user'))) {
            if (!empty($this->get_option('exclude-user'))) {
                if (!array_intersect($current_user->roles, $this->get_option('exclude-user'))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else if ($this->get_option('apply-user') === "registered" && is_user_logged_in()) {
            if (!empty($this->get_option('exclude-user'))) {
                if (!array_intersect($current_user->roles, $this->get_option('exclude-user'))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else if ($this->get_option('apply-user') === "non-registered" && !is_user_logged_in()) {
            return true;
        } else {
            return false;
        }
    }
	
	/*
	* check exclude product
	*
	* @Since    1.0.2
	*/
	public function xc_order_on_whatsapp_exclude_product( $product_id ){
		$exclude_products = $this->get_option('exclude-product');
		if(is_array($exclude_products) && in_array($product_id,$exclude_products) ) return true;
		return false;
	}

    /**
     * Display The Short code Content in category product
     *
     * @since    1.0.0
     */
    public function xc_order_on_whatsapp_shortcode_category() {
		global $product;
		if(!$this->xc_order_on_whatsapp_exclude_product($product->get_id())){
			$this->xc_order_on_whatsapp_shortcode_content();
		}
    }

    /**
     * Display The Short code Content in single product
     *
     * @since    1.0.0
     */
    public function xc_order_on_whatsapp_shortcode_single_product() {
		global $product;
		if(!$this->xc_order_on_whatsapp_exclude_product($product->get_id())){
			$this->xc_order_on_whatsapp_shortcode_content('xc-woo-order-whatsapp-variable-product');
		}
    }

    /**
     * Display The Short code Content callback function
     *
     * @since    1.0.0
     */
    public function xc_order_on_whatsapp_shortcode_content($class='') {
		global $product;
		$xc_whatsapp_message = $this->get_option('whatsapp-message');
		$link = get_permalink($product->get_id());
		
		$xc_whatsapp_message = str_replace('{product_name}', $product->get_title(), $xc_whatsapp_message);
		$xc_whatsapp_message = str_replace('{product_price}', $product->get_price(), $xc_whatsapp_message);
		$xc_whatsapp_message = str_replace('{product_link}', $link, $xc_whatsapp_message);
		$xc_whatsapp_message = apply_filters('xc_order_on_whatsapp_single_product_message', $xc_whatsapp_message, $product);
		$url_base = $this->get_whatsapp_urlbase();
		$href = $url_base."send?phone=" . $this->get_whatsapp_number() . "&text=" . $xc_whatsapp_message;
		echo '<a class="xc-woo-order-whatsapp-btn '.$class.'" data-href="'.$href.'" href="'.$href.'" target="_blank">'. $this->_whatsapp_icon() . $this->get_option('button') . ' </a>';
    }

    /**
     * Remove Hooks add to cart or price tag
     *
     * @since    1.0.0
     */
    public function xc_order_on_whatsapp_remove_hooks() {
		global $post;
        if ($this->get_option('remove-price') === "1" && !$this->xc_order_on_whatsapp_exclude_product($post->ID)) {
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
        }
        if ($this->get_option('remove-add-to-cart-botton') === "1" && !$this->xc_order_on_whatsapp_exclude_product($post->ID)) {
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        }
    }
	
	
	/*
	* check product is purchasable
	*
	* @Since    1.0.2
	*/
	public function is_purchasable($ret, $product){
        if ( $this->get_option('remove-add-to-cart-botton') === "1" && !$this->xc_order_on_whatsapp_exclude_product($product->get_id())) {
			if(is_product_category() || is_shop()){
				if($this->get_option('enable-on-category') === "1"){
					return false;	
				}else{
					return $ret;	
				}
			}
			return false;
		}
		return $ret;
	}
	
	
	/*
	* returns add to cart button
	*
	* @Since    1.0.2
	*/
	public function change_add_to_cart_btn( $link, $product, $args=array() ){
		$ret_link = true;
		if ($this->get_option('remove-add-to-cart-botton') === "1" && !$this->xc_order_on_whatsapp_exclude_product($product->get_id())) {
			if(is_product_category() || is_shop()){
				if($this->get_option('enable-on-category') === "1"){
					return '';	
				}else{
					$ret_link = true;
				}
			}
			$ret_link = false;
		}
		
		if( false === $ret_link){
			return '';	
		}else{
			if($this->get_option('enable-whatsapp-cart')){
				$args['class'] = (isset( $args['class']))?$args['class'].'  xc-woo-order-whatsapp-btn':'button xc-woo-order-whatsapp-btn';
				return sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
						esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
						isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
						$product->add_to_cart_text());	
			}else{
				return $link;
			}
		}
	}
	
	
	/*
	* returns Price Html
	*
	* @Since    1.0.2
	*/
	public function prie_html($price, $product){
		if ($this->get_option('remove-price') === "1" && !$this->xc_order_on_whatsapp_exclude_product($product->get_id())) {
			/*if(is_product_category() || is_shop()){
				if($this->get_option('enable-on-category') === "1"){
					return '';
				}else{
					return $price;	
				}
			}*/
			return '';	
		}
		return $price;
	}

    /**
     * page redirect for cart and checkout pages
     *
     * @since    1.0.0
     */
    public function xc_order_on_whatsapp_page_redirect() {
		$redirect = false;
		if(is_cart() && !$this->get_option('enable-whatsapp-cart')){
			$redirect = true;	
		}
		
		if (is_checkout() && !$this->get_option('enable-whatsapp-checkout', false)){
			$redirect = true;	
		}
		
		
		if (true === $redirect) {
			$page_id = $this->get_option('select-page');
			$url = (!empty($page_id))?get_permalink($page_id):site_url();
			wp_redirect($url);
			die();
		}
		
    }
	
	/**
	* Hide add to cart and price 
	*
	* @since  1.0.0
	*/
	public function xc_hard_hide(){
		global $post;
		echo '<style type="text/css">';
		/*if ($this->get_option('enable') === "1" && $this->get_option('remove-price') === "1" && !$this->xc_order_on_whatsapp_exclude_product($post->ID)) {
			echo '.product .price, .price {
						display:none !important;
					}';	
		}
		if ($this->get_option('enable') === "1" && $this->get_option('remove-add-to-cart-botton') === "1" && !$this->xc_order_on_whatsapp_exclude_product($post->ID))  {
			echo '.product .add_to_cart_button, .add_to_cart_button,
					.product form.cart {
						display:none !important;
					}';	
		}*/
		
		if ($this->get_option('remove-price') === "1"){
			echo '.woocommerce table.shop_table thead th.product-price, 
				  .woocommerce-page table.shop_table thead th.product-price,
				  .woocommerce table.shop_table thead th.product-subtotal, 
				  .woocommerce-page table.shop_table thead th.product-subtotal,
				  .woocommerce table.shop_table  td.product-price, 
				  .woocommerce-page table.shop_table  td.product-price,
				  .woocommerce table.shop_table  td.product-subtotal, 
				  .woocommerce-page table.shop_table  td.product-subtotal,
				  body .elementor-menu-cart__subtotal,
				  body .elementor-menu-cart__product-price.product-price,
				  body .product-price,
				  body .woocommerce-Price-amount{
					display:none!important;  
				  }';	
		}
		
		if( "1"  === $this->get_option('hide_desktop') ){
			echo "@media only screen and (min-width: 786px) {
				body a.xc-woo-order-whatsapp-btn{ display:none!important;}
			}";
		}
		
		if( "1"  === $this->get_option('hide_mobile') ){
			echo "@media only screen and (max-width: 785px) {
				body a.xc-woo-order-whatsapp-btn{ display:none!important;}
			}";
		}
		
		if( "1"  === $this->get_option('floating_hide_desktop') ){
			echo "@media only screen and (min-width: 786px) {
				body .xc-woo-floating-whatsapp-btn{ display:none!important;}
			}";
		}
		
		if( "1"  === $this->get_option('floating_hide_mobile') ){
			echo "@media only screen and (max-width: 785px) {
				body .xc-woo-floating-whatsapp-btn{ display:none!important;}
			}";
		}
		
		echo '</style>';
	}
	
	
	/**
	 * Outputs WhatsApp button html and settings on footer
	 *
	 * @since    1.0.1
	 */
	public function footer_html(){
		if( "1" === $this->get_option('floatingbutton')){
			$pos = $this->get_option("floatingpos","left");
			$phone = $this->get_whatsapp_number();
			if( "1" === $this->get_option('floating_use_different_number')){
				$phone = $this->get_option('floating_number', $phone);
			}
			
			$whatsapp_message = $this->get_option('floating-message');
			$url_base = $this->get_whatsapp_urlbase();
			
			?>

<div class="xc-woo-floating-whatsapp-btn xc-woo-floating-whatsapp-btn-<?php echo $pos;?>">
  <div class="xc-woo-floating-whatsapp-button"> <a href="<?php echo $url_base;?>send?phone=<?php echo $phone;?>&text=<?php echo $whatsapp_message;?>" target="_blank"> <?php echo $this->_whatsapp_icon(); ?> </a> </div>
</div>
<?php	
		}
	}
	
	public function _whatsapp_icon(){
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>';	
	}
	
	public function whatsapp_cart_button_text(){
		global $product;
		$button_text = $this->get_option('add-to-cart-text');	
		return $button_text;
	}
	
	public function add_buy_on_whatsapp_button(){
		$button_text = $this->get_option('buy-on-whatsapp-text');
		$cart = WC()->cart->get_cart();	
		
		$is_empty_after_whatsapp = $this->get_option('empty-cart');
		if($is_empty_after_whatsapp === "1"){
			$url = $this->get_whatsapp_cart_message();
			$extra_class = 'xc-woo-order-whatsapp-send';
			$target = '_blank';
		}else{
			$url = $this->get_whatsapp_cart_message();	
			$extra_class = '';
			$target = '_blank';
		}
		?>
<a href="<?php echo $url;?>" target="<?php echo $target;?>" class="xc-woo-order-whatsapp-btn btn button btn-primary btn-lg <?php echo $extra_class;?>"> <?php echo $this->_whatsapp_icon() . $button_text ?> </a>
<?php
	}
	
	public function get_whatsapp_cart_message(){
		$message_includes = $this->get_option('buy-on-whatsapp-cart-inludes');
    	$cart = WC()->cart->get_cart();	
		$phone = $this->get_whatsapp_number();
		$whatsapp_message = '';
		
		$products = array();
		foreach ($cart as $cart_key => $cart_item) {
			if(!empty($cart_item['data'])) {
				$product = $cart_item['data'];
				$product_data = '';
				if(in_array('qty', $message_includes)){
					$product_data.= $cart_item['quantity'].' x ';
				}
				if(in_array('name', $message_includes)){
					$product_data.= $product->get_name();
				}
				
				if(in_array('price', $message_includes)){
					$line_total = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ), $cart_item, $cart_key );
					$product_data.=" - ".html_entity_decode(strip_tags($line_total));
				}
				
				if(in_array('link', $message_includes)){
					$link = get_permalink($product->get_id());
					$product_data.=" - ".$link;
				}
				
				if(apply_filters("xc_woo_whatsapp_order_cart_product_data_include_cart_item_data", true)){
					ob_start();
					echo PHP_EOL;
					echo wc_get_formatted_cart_item_data( $cart_item , true);
					$dd = ob_get_clean();
					$product_data.=strip_tags(html_entity_decode($dd));
				}
				
	
				$products[] = apply_filters("xc_woo_whatsapp_order_cart_product_data", $product_data, $cart, $cart_key, $cart_item);
			}
		}
		
		$prefix = __("Hi, I would like to buy the following products.", 'xc-woo-whatsapp-order');
		
		$prefix = $this->get_option('buy-on-whatsapp-prefix', $prefix);
		$prefix = apply_filters("xc_woo_whatsapp_order_cart_message_prefix",$prefix);
		
		$suffix = __("Thank you.", 'xc-woo-whatsapp-order');
		$suffix = $this->get_option('buy-on-whatsapp-suffix', $suffix);
		$suffix = apply_filters("xc_woo_whatsapp_order_cart_message_suffix",$suffix);
		ob_start();
		echo $prefix;
		echo PHP_EOL;	
		echo PHP_EOL;	
		foreach($products as $p){
			echo $p;
			echo PHP_EOL;
		}
		echo PHP_EOL;
		
		if(in_array('total', $message_includes)){
			if(WC()->cart->get_coupons()){
				$total = sprintf(__("Sub Total : %s","xc-woo-whatsapp-order"), html_entity_decode(strip_tags(WC()->cart->get_cart_subtotal())));
				echo $total;
				echo PHP_EOL;
				ob_start();
				foreach ( WC()->cart->get_coupons() as $code => $coupon ) :
					wc_cart_totals_coupon_label( $coupon ); 
					echo " : ";
					$this->cart_totals_coupon_html( $coupon );
					echo PHP_EOL;
				endforeach;
				$coupons = ob_get_clean();
				echo html_entity_decode(strip_tags($coupons));
			}
			$total = sprintf(__("Total : %s","xc-woo-whatsapp-order"), html_entity_decode(strip_tags(WC()->cart->get_cart_total())));
			echo $total;
			echo PHP_EOL;	
			echo PHP_EOL;			
		}
		

		echo $suffix;	
		$output = ob_get_clean();
		
		$output = apply_filters("xc_woo_whatsapp_order_cart_message",$output);
		$whatsapp_message = rawurlencode($output);
		$url_base = $this->get_whatsapp_urlbase();
		$url = $url_base."send?phone={$phone}&text={$whatsapp_message}";
		return $url;
	}
	
	public function get_whatsapp_number(){
		$phone = $this->get_option('whatsapp-no');
		
		if(class_exists('WeDevs_Dokan')){
			if(is_product() || (isset($GLOBALS['woocommerce_loop']))){
				if($this->get_option('enable-dokan-whatsapp')){
					global $product;
					$vendor_id = get_post_field( 'post_author', $product->get_id() );
					if(dokan_is_valid_owner($product->get_id(), $vendor_id)){
						$store_info = dokan_get_store_info( $vendor_id );
						$vendor_phone = (isset($store_info['phone']))?$store_info['phone']:'';
						if(!empty($vendor_phone)){
							$phone = $vendor_phone;	
						}
					}
				}
			}
		
			if(is_cart()){
				if($this->get_option('enable-dokan-whatsapp-cart')){
					$products = WC()->cart->get_cart();
					if ( $products ) {
						$payees      = array();
						foreach ( $products as $key => $data ) {
							$product_id = $data['product_id'];
							$seller_id  = get_post_field( 'post_author', $product_id );
			
							if ( ! array_key_exists( $seller_id, $payees ) ) {
								$payees[] = $seller_id;
							}
						}
						if(!empty($payees)){
							$vendor_id = $payees[0];
							$store_info = dokan_get_store_info( $vendor_id );
							$vendor_phone = (isset($store_info['phone']))?$store_info['phone']:'';
							if(!empty($vendor_phone)){
								$phone = $vendor_phone;	
							}	
						}
			
					}
				}
			}
		}
		
		$phone = apply_filters("xc_woo_whatsapp_order_phone_number", $phone);
		return $phone;
	}
	
	public function xc_woo_whatsapp_order_send(){
		if (!wp_verify_nonce($_POST['nonce'], 'xc_woo_whatsapp_order_nonce')) {
			die();
		}
		$url = $this->get_whatsapp_cart_message();
		WC()->cart->empty_cart();
		$dt = array('whatsapp' => $url);
		$redirect = $this->get_option('thankyou-page');
		if(!empty($redirect)){
			$dt['redirect'] = get_permalink($redirect);	
		}else{
			$dt['redirect'] = site_url();	
		}
		wp_send_json_success($dt);
	}
	
	public function display_cart_total(){
		ob_start();
			if(WC()->cart->get_coupons()){
				?>
<tr class="cart-subtotal">
  <td colspan="4" ></td>
  <td><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></td>
  <td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
</tr>
<?php
			foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
  <td colspan="4" ></td>
  <td><?php wc_cart_totals_coupon_label( $coupon ); ?></td>
  <td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
</tr>
<?php endforeach; ?>
<?php } ?>
<tr class="order-total">
  <td colspan="4" ></td>
  <td><?php esc_html_e( 'Total', 'woocommerce' ); ?></td>
  <td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
</tr>
<?php
		echo ob_get_clean();	
	}
	
	
	public function cart_totals_coupon_html( $coupon ) {
		if ( is_string( $coupon ) ) {
			$coupon = new WC_Coupon( $coupon );
		}
	
		$discount_amount_html = '';
	
		$amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
		$discount_amount_html = '-' . wc_price( $amount );
	
		if ( $coupon->get_free_shipping() && empty( $amount ) ) {
			$discount_amount_html = __( 'Free shipping coupon', 'woocommerce' );
		}
	
		$discount_amount_html = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_amount_html, $coupon );
		echo $discount_amount_html;
	}
	
	public function validate_cart( $valid, $product_id, $quantity ){
		$products = WC()->cart->get_cart();
		$products[$product_id] = array( 'product_id' => $product_id );
		if ( $products ) {
            $payees      = array();
            $single_mode = true; // buyer will buy products from only one vendor at a time

            foreach ( $products as $key => $data ) {
                $product_id = $data['product_id'];
                $seller_id  = get_post_field( 'post_author', $product_id );

                if ( ! array_key_exists( $seller_id, $payees ) ) {
                    $payees[$seller_id] = $seller_id;
                }
            }

            // single seller mode
            if ( $single_mode && count( $payees ) > 1 ) {
                $error_message = __( 'You can not add more than one vendors product in the cart', 'xc-woo-whatsapp-order' );
                wc_add_notice( $error_message, 'error' );
                return false;
            }
        }
        return $valid;	
	}
}