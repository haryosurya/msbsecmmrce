<?php
/*
Plugin Name: WPC Smart Compare for WooCommerce
Plugin URI: https://wpclever.net/
Description: Smart products compare for WooCommerce.
Version: 3.7.0
Author: WPClever
Author URI: https://wpclever.net
Text Domain: woo-smart-compare
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 5.8
WC requires at least: 3.0
WC tested up to: 5.5.1
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOSC_VERSION' ) && define( 'WOOSC_VERSION', '3.7.0' );
! defined( 'WOOSC_URI' ) && define( 'WOOSC_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOSC_PATH' ) && define( 'WOOSC_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'WOOSC_SUPPORT' ) && define( 'WOOSC_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=woosc&utm_campaign=wporg' );
! defined( 'WOOSC_REVIEWS' ) && define( 'WOOSC_REVIEWS', 'https://wordpress.org/support/plugin/woo-smart-compare/reviews/?filter=5' );
! defined( 'WOOSC_CHANGELOG' ) && define( 'WOOSC_CHANGELOG', 'https://wordpress.org/plugins/woo-smart-compare/#developers' );
! defined( 'WOOSC_DISCUSSION' ) && define( 'WOOSC_DISCUSSION', 'https://wordpress.org/support/plugin/woo-smart-compare' );
! defined( 'WPC_NOTICE' ) && define( 'WPC_NOTICE', plugin_dir_url( __FILE__ ) );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOSC_URI );

include 'includes/wpc-dashboard.php';
include 'includes/wpc-menu.php';
include 'includes/wpc-kit.php';
include 'includes/wpc-notice.php';
include 'notice/notice.php';

if ( ! function_exists( 'wooscp_init' ) ) {
	add_action( 'plugins_loaded', 'wooscp_init', 11 );

	function wooscp_init() {
		// load text-domain
		load_plugin_textdomain( 'woo-smart-compare', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wooscp_notice_wc' );

			return;
		}

		if ( ! class_exists( 'WPCleverWooscp' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWooscp {
				protected static $woosc_fields = array();
				protected static $woosc_attributes = array();

				function __construct() {
					self::$woosc_fields = array(
						'image'             => esc_html__( 'Image', 'woo-smart-compare' ),
						'sku'               => esc_html__( 'SKU', 'woo-smart-compare' ),
						'rating'            => esc_html__( 'Rating', 'woo-smart-compare' ),
						'price'             => esc_html__( 'Price', 'woo-smart-compare' ),
						'stock'             => esc_html__( 'Stock', 'woo-smart-compare' ),
						'availability'      => esc_html__( 'Availability', 'woo-smart-compare' ),
						'add_to_cart'       => esc_html__( 'Add to cart', 'woo-smart-compare' ),
						'description'       => esc_html__( 'Description', 'woo-smart-compare' ),
						'content'           => esc_html__( 'Content', 'woo-smart-compare' ),
						'weight'            => esc_html__( 'Weight', 'woo-smart-compare' ),
						'dimensions'        => esc_html__( 'Dimensions', 'woo-smart-compare' ),
						'additional'        => esc_html__( 'Additional information', 'woo-smart-compare' ),
						'attributes'        => esc_html__( 'Attributes', 'woo-smart-compare' ),
						'custom_attributes' => esc_html__( 'Custom attributes', 'woo-smart-compare' ),
						'custom_fields'     => esc_html__( 'Custom fields', 'woo-smart-compare' ),
					);

					// init
					add_action( 'init', array( $this, 'wooscp_init' ) );
					add_action( 'wp_footer', array( $this, 'wooscp_wp_footer' ) );
					add_action( 'wp_enqueue_scripts', array( $this, 'wooscp_wp_enqueue_scripts' ) );
					add_action( 'admin_enqueue_scripts', array( $this, 'wooscp_admin_enqueue_scripts' ) );

					// search
					add_action( 'wp_ajax_wooscp_search', array( $this, 'wooscp_search' ) );
					add_action( 'wp_ajax_nopriv_wooscp_search', array( $this, 'wooscp_search' ) );

					// after user login
					add_action( 'wp_login', array( $this, 'wooscp_wp_login' ), 10, 2 );

					// ajax load bar items
					add_action( 'wp_ajax_wooscp_load_compare_bar', array( $this, 'wooscp_load_compare_bar' ) );
					add_action( 'wp_ajax_nopriv_wooscp_load_compare_bar', array( $this, 'wooscp_load_compare_bar' ) );

					// ajax load compare table
					add_action( 'wp_ajax_wooscp_load_compare_table', array( $this, 'wooscp_load_compare_table' ) );
					add_action( 'wp_ajax_nopriv_wooscp_load_compare_table', array(
						$this,
						'wooscp_load_compare_table'
					) );

					// settings page
					add_action( 'admin_menu', array( $this, 'wooscp_admin_menu' ) );

					// settings link
					add_filter( 'plugin_action_links', array( $this, 'wooscp_action_links' ), 10, 2 );
					add_filter( 'plugin_row_meta', array( $this, 'wooscp_row_meta' ), 10, 2 );

					// menu items
					add_filter( 'wp_nav_menu_items', array( $this, 'wooscp_nav_menu_items' ), 99, 2 );

					add_filter( 'wp_dropdown_cats', array( $this, 'wooscp_dropdown_cats_multiple' ), 10, 2 );
				}

				function wooscp_init() {
					// attributes
					$wc_attributes = wc_get_attribute_taxonomies();

					if ( $wc_attributes ) {
						foreach ( $wc_attributes as $wc_attribute ) {
							self::$woosc_attributes[ $wc_attribute->attribute_name ] = $wc_attribute->attribute_label;
						}
					}

					// shortcode
					add_shortcode( 'woosc', array( $this, 'wooscp_shortcode' ) );
					add_shortcode( 'wooscp', array( $this, 'wooscp_shortcode' ) );
					add_shortcode( 'woosc_list', array( $this, 'wooscp_shortcode_list' ) );
					add_shortcode( 'wooscp_list', array( $this, 'wooscp_shortcode_list' ) );

					// image sizes
					add_image_size( 'wooscp-large', 600, 600, true );
					add_image_size( 'wooscp-small', 96, 96, true );

					// add button for archive
					$button_archive = apply_filters( 'filter_wooscp_button_archive', get_option( '_wooscp_button_archive', 'after_add_to_cart' ) );
					$button_archive = apply_filters( 'woosc_button_position_archive', $button_archive );

					switch ( $button_archive ) {
						case 'after_title':
							add_action( 'woocommerce_shop_loop_item_title', array( $this, 'wooscp_add_button' ), 11 );
							break;

						case 'after_rating':
							add_action( 'woocommerce_after_shop_loop_item_title', array(
								$this,
								'wooscp_add_button'
							), 6 );
							break;

						case 'after_price':
							add_action( 'woocommerce_after_shop_loop_item_title', array(
								$this,
								'wooscp_add_button'
							), 11 );
							break;

						case 'before_add_to_cart':
							add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wooscp_add_button' ), 9 );
							break;

						case 'after_add_to_cart':
							add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wooscp_add_button' ), 11 );
							break;
					}

					// add button for single
					$button_single = apply_filters( 'filter_wooscp_button_single', get_option( '_wooscp_button_single', '31' ) );
					$button_single = apply_filters( 'woosc_button_position_single', $button_single );

					if ( ! empty( $button_single ) ) {
						add_action( 'woocommerce_single_product_summary', array(
							$this,
							'wooscp_add_button'
						), (int) $button_single );
					}
				}

				function wooscp_wp_login( $user_login, $user ) {
					if ( isset( $user->data->ID ) ) {
						$user_products = get_user_meta( $user->data->ID, 'wooscp_products', true );
						$user_fields   = get_user_meta( $user->data->ID, 'wooscp_fields', true );

						if ( ! empty( $user_products ) ) {
							setcookie( 'wooscp_products_' . md5( 'wooscp' . $user->data->ID ), $user_products, time() + 604800, '/' );
						}

						if ( ! empty( $user_fields ) ) {
							setcookie( 'wooscp_fields_' . md5( 'wooscp' . $user->data->ID ), $user_fields, time() + 604800, '/' );
						}
					}
				}

				function wooscp_wp_enqueue_scripts() {
					// hint
					wp_enqueue_style( 'hint', WOOSC_URI . 'assets/libs/hint/hint.min.css' );

					// dragarrange
					wp_enqueue_script( 'dragarrange', WOOSC_URI . 'assets/libs/dragarrange/drag-arrange.js', array( 'jquery' ), WOOSC_VERSION, true );

					// table head fixer
					wp_enqueue_script( 'table-head-fixer', WOOSC_URI . 'assets/libs/table-head-fixer/table-head-fixer.js', array( 'jquery' ), WOOSC_VERSION, true );

					// perfect srollbar
					wp_enqueue_style( 'perfect-scrollbar', WOOSC_URI . 'assets/libs/perfect-scrollbar/css/perfect-scrollbar.min.css' );
					wp_enqueue_style( 'perfect-scrollbar-wpc', WOOSC_URI . 'assets/libs/perfect-scrollbar/css/custom-theme.css' );
					wp_enqueue_script( 'perfect-scrollbar', WOOSC_URI . 'assets/libs/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js', array( 'jquery' ), WOOSC_VERSION, true );

					// frontend css & js
					wp_enqueue_style( 'wooscp-frontend', WOOSC_URI . 'assets/css/frontend.css' );
					wp_enqueue_script( 'wooscp-frontend', WOOSC_URI . 'assets/js/frontend.js', array( 'jquery' ), WOOSC_VERSION, true );

					$woosc_limit_notice = get_option( '_wooscp_limit_notice' );

					if ( empty( $woosc_limit_notice ) ) {
						$woosc_limit_notice = esc_html__( 'You can add a maximum of {limit} products to the compare table.', 'woo-smart-compare' );
					}

					$button_text       = get_option( '_wooscp_button_text' );
					$button_text_added = get_option( '_wooscp_button_text_added' );

					if ( empty( $button_text ) ) {
						$button_text = esc_html__( 'Compare', 'woo-smart-compare' );
					}

					if ( empty( $button_text_added ) ) {
						$button_text_added = esc_html__( 'Compare', 'woo-smart-compare' );
					}

					wp_localize_script( 'wooscp-frontend', 'wooscpVars', array(
							'ajaxurl'            => admin_url( 'admin-ajax.php' ),
							'user_id'            => md5( 'wooscp' . get_current_user_id() ),
							'page_url'           => self::wooscp_get_page_url(),
							'open_button'        => $this->wooscp_nice_class_id( get_option( '_wooscp_open_button', '' ) ),
							'open_button_action' => get_option( '_wooscp_open_button_action', 'open_popup' ),
							'menu_action'        => get_option( '_wooscp_menu_action', 'open_popup' ),
							'open_table'         => get_option( '_wooscp_open_immediately', 'yes' ) === 'yes' ? 'yes' : 'no',
							'open_bar'           => get_option( '_wooscp_open_bar_immediately', 'no' ) === 'yes' ? 'yes' : 'no',
							'bar_bubble'         => get_option( '_wooscp_bar_bubble', 'no' ),
							'click_again'        => get_option( '_wooscp_click_again', 'no' ) === 'yes' ? 'yes' : 'no',
							'remove_all'         => esc_html__( 'Do you want to remove all products from the compare?', 'woo-smart-compare' ),
							'hide_empty'         => get_option( '_wooscp_hide_empty', 'no' ),
							'click_outside'      => get_option( '_wooscp_click_outside', 'yes' ),
							'freeze_column'      => get_option( '_wooscp_freeze_column', 'yes' ),
							'freeze_row'         => get_option( '_wooscp_freeze_row', 'yes' ),
							'limit'              => get_option( '_wooscp_limit', '100' ),
							'limit_notice'       => $woosc_limit_notice,
							'button_text'        => apply_filters( 'wooscp_button_text', $button_text ),
							'button_text_added'  => apply_filters( 'wooscp_button_text_added', $button_text_added ),
							'button_text_change' => get_option( '_wooscp_button_text_change', 'yes' ),
							'nonce'              => wp_create_nonce( 'wooscp-nonce' ),
						)
					);
				}

				function wooscp_admin_enqueue_scripts( $hook ) {
					wp_enqueue_style( 'wooscp-backend', WOOSC_URI . 'assets/css/backend.css' );

					if ( strpos( $hook, 'wooscp' ) ) {
						wp_enqueue_style( 'wp-color-picker' );
						wp_enqueue_script( 'dragarrange', WOOSC_URI . 'assets/libs/dragarrange/drag-arrange.js', array( 'jquery' ), WOOSC_VERSION, true );
						wp_enqueue_script( 'wooscp-backend', WOOSC_URI . 'assets/js/backend.js', array(
							'jquery',
							'wp-color-picker'
						) );
					}
				}

				function wooscp_action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings         = '<a href="' . admin_url( 'admin.php?page=wpclever-woosc&tab=settings' ) . '">' . esc_html__( 'Settings', 'woo-smart-compare' ) . '</a>';
						$links['premium'] = '<a href="' . admin_url( 'admin.php?page=wpclever-woosc&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'woo-smart-compare' ) . '</a>';
						array_unshift( $links, $settings );
					}

					return (array) $links;
				}

				function wooscp_row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = array(
							'support' => '<a href="' . esc_url( WOOSC_SUPPORT ) . '" target="_blank">' . esc_html__( 'Support', 'woo-smart-compare' ) . '</a>',
						);

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function wooscp_admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Smart Compare', 'woo-smart-compare' ), esc_html__( 'Smart Compare', 'woo-smart-compare' ), 'manage_options', 'wpclever-woosc', array(
						$this,
						'wooscp_settings_page'
					) );
				}

				function wooscp_dropdown_cats_multiple( $output, $r ) {
					if ( isset( $r['multiple'] ) && $r['multiple'] ) {
						$output = preg_replace( '/^<select/i', '<select multiple', $output );
						$output = str_replace( "name='{$r['name']}'", "name='{$r['name']}[]'", $output );

						foreach ( array_map( 'trim', explode( ',', $r['selected'] ) ) as $value ) {
							$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
						}
					}

					return $output;
				}

				function wooscp_settings_page() {
					$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Smart Compare', 'woo-smart-compare' ) . ' ' . WOOSC_VERSION; ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'woo-smart-compare' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOSC_REVIEWS ); ?>"
                                   target="_blank"><?php esc_html_e( 'Reviews', 'woo-smart-compare' ); ?></a> | <a
                                        href="<?php echo esc_url( WOOSC_CHANGELOG ); ?>"
                                        target="_blank"><?php esc_html_e( 'Changelog', 'woo-smart-compare' ); ?></a>
                                | <a href="<?php echo esc_url( WOOSC_DISCUSSION ); ?>"
                                     target="_blank"><?php esc_html_e( 'Discussion', 'woo-smart-compare' ); ?></a>
                            </p>
                        </div>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woosc&tab=settings' ); ?>"
                                   class="<?php echo( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'woo-smart-compare' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woosc&tab=premium' ); ?>"
                                   class="<?php echo( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>"
                                   style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'woo-smart-compare' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-kit' ) ); ?>"
                                   class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'woo-smart-compare' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) { ?>
                                <form method="post" action="options.php">
									<?php wp_nonce_field( 'update-options' ) ?>
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'woo-smart-compare' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Open compare bar immediately', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="checkbox"
                                                       name="_wooscp_open_bar_immediately"
                                                       value="yes" <?php echo( get_option( '_wooscp_open_bar_immediately', 'no' ) === 'yes' ? 'checked' : '' ); ?>/>
                                                <span class="description">
											<?php esc_html_e( 'Check it if you want to open the compare bar immediately on page loaded.', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Open compare table immediately', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="checkbox"
                                                       name="_wooscp_open_immediately"
                                                       value="yes" <?php echo( get_option( '_wooscp_open_immediately', 'yes' ) === 'yes' ? 'checked' : '' ); ?>/>
                                                <span class="description">
											<?php esc_html_e( 'Check it if you want to open the compare table immediately when click to compare button. If not, it just add product to the compare bar.', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Hide on cart & checkout page', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_hide_checkout">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_hide_checkout', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_hide_checkout', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
											<?php esc_html_e( 'Hide the compare table and compare bar on the cart & checkout page?', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Hide if empty', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_hide_empty">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_hide_empty', 'no' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_hide_empty', 'no' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
													<?php esc_html_e( 'Hide the compare table and compare bar if haven\'t any product.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Limit', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input name="_wooscp_limit" type="number" min="1" max="100" step="1"
                                                       value="<?php echo get_option( '_wooscp_limit', '100' ); ?>"/>
                                                <span class="description">
													<?php esc_html_e( 'The maximum of products can be added to the compare table.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Limit notice', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input name="_wooscp_limit_notice" type="text"
                                                       style="width: 100%; max-width: 600px"
                                                       class="regular-text"
                                                       value="<?php echo get_option( '_wooscp_limit_notice' ); ?>"
                                                       placeholder="<?php esc_html_e( 'You can add a maximum of {limit} products to the compare table.', 'woo-smart-compare' ); ?>"/>
                                                <p class="description">
													<?php esc_html_e( 'The notice when reaching the limit. Use {limit} to show the number. Leave blank if you want to use the default text and can be translated.', 'woo-smart-compare' ); ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Compare page', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php wp_dropdown_pages( array(
													'selected'          => get_option( '_wooscp_page_id', '' ),
													'name'              => '_wooscp_page_id',
													'show_option_none'  => esc_html__( 'Choose a page', 'woo-smart-compare' ),
													'option_none_value' => '',
												) ); ?>
                                                <span class="description">
											<?php printf( esc_html__( 'Add shortcode %s to display the compare table on this page.', 'woo-smart-compare' ), '<code>[woosc_list]</code>' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Custom button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="text" name="_wooscp_open_button"
                                                       value="<?php echo get_option( '_wooscp_open_button', '' ); ?>"
                                                       placeholder="<?php esc_html_e( 'button class or id', 'woo-smart-compare' ); ?>"/>
                                                <span class="description">
											<?php esc_html_e( 'The class or id of the button, when clicking on this button the compare page or compare table will be opened.', 'woo-smart-compare' ); ?>
                                                   <br/><?php printf( esc_html__( 'Example %s or %s', 'woo-smart-compare' ), '<code>.open-compare-btn</code>', '<code>#open-compare-btn</code>' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Custom button action', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_open_button_action">
                                                    <option
                                                            value="open_page" <?php echo( get_option( '_wooscp_open_button_action', 'open_popup' ) === 'open_page' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Open page', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="open_popup" <?php echo( get_option( '_wooscp_open_button_action', 'open_popup' ) === 'open_popup' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Open popup', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select> <span class="description">
											<?php esc_html_e( 'Action when clicking on the "custom button".', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Compare table', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Fields', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <ul class="wooscp-fields">
													<?php
													$saved_fields = $saved_fields_arr = array();

													if ( is_array( get_option( '_wooscp_fields' ) ) ) {
														$saved_fields = get_option( '_wooscp_fields' );
													} else {
														$saved_fields = array_keys( self::$woosc_fields );
													}

													foreach ( $saved_fields as $sf ) {
														if ( isset( self::$woosc_fields[ $sf ] ) ) {
															$saved_fields_arr[ $sf ] = self::$woosc_fields[ $sf ];
														}
													}

													$fields_merge = array_merge( $saved_fields_arr, self::$woosc_fields );

													foreach ( $fields_merge as $key => $value ) {
														echo '<li class="wooscp-fields-item"><input type="checkbox" name="_wooscp_fields[]" value="' . $key . '" ' . ( in_array( $key, $saved_fields, false ) ? 'checked' : '' ) . '/><span class="label">' . $value . '</span></li>';
													}
													?>
                                                </ul>
                                                <span class="description">
                                                    <?php esc_html_e( 'Please choose the fields you want to show on the compare table. You also can drag/drop to rearrange these fields.', 'woo-smart-compare' ); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Attributes', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <ul class="wooscp-attributes">
													<?php
													$saved_attributes = $saved_attributes_arr = array();

													if ( is_array( get_option( '_wooscp_attributes' ) ) ) {
														$saved_attributes = get_option( '_wooscp_attributes' );
													}

													foreach ( $saved_attributes as $sa ) {
														if ( isset( self::$woosc_attributes[ $sa ] ) ) {
															$saved_attributes_arr[ $sa ] = self::$woosc_attributes[ $sa ];
														}
													}

													$attributes_merge = array_merge( $saved_attributes_arr, self::$woosc_attributes );

													foreach ( $attributes_merge as $key => $value ) {
														echo '<li class="wooscp-attributes-item"><input type="checkbox" name="_wooscp_attributes[]" value="' . $key . '" ' . ( in_array( $key, $saved_attributes, false ) ? 'checked' : '' ) . '/><span class="label">' . $value . '</span></li>';
													}
													?>
                                                </ul>
                                                <span class="description">
													<?php esc_html_e( 'Please choose the attributes you want to show on the compare table. You also can drag/drop to rearrange these attributes.', 'woo-smart-compare' ); ?>
												</span>
                                                <p class="description" style="color: #c9356e">
                                                    * This feature only available on Premium Version. Click <a
                                                            href="https://wpclever.net/downloads/woocommerce-smart-compare?utm_source=pro&utm_medium=woosc&utm_campaign=wporg"
                                                            target="_blank">here</a> to buy, just $29!
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Custom attributes', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <textarea name="_wooscp_custom_attributes" rows="10" cols="50"
                                                          class="large-text"><?php echo get_option( '_wooscp_custom_attributes' ); ?></textarea>
                                                <span class="description">
													<?php esc_html_e( 'Add custom attribute names, split by a comma.', 'woo-smart-compare' ); ?>
                                                     E.g: <code>Custom attribute 1, Custom attribute 2</code>
												</span>
                                                <p class="description" style="color: #c9356e">
                                                    * This feature only available on Premium Version. Click <a
                                                            href="https://wpclever.net/downloads/woocommerce-smart-compare?utm_source=pro&utm_medium=woosc&utm_campaign=wporg"
                                                            target="_blank">here</a> to buy, just $29!
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Custom fields', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <textarea name="_wooscp_custom_fields" rows="10" cols="50"
                                                          class="large-text"><?php echo get_option( '_wooscp_custom_fields' ); ?></textarea>
                                                <span class="description">
													<?php esc_html_e( 'Add custom field names/slugs and labels, split by a comma.', 'woo-smart-compare' ); ?>
                                                     E.g: <code>Field name 1 | Label 1, field-slug-2, Field name 3</code>
												</span>
                                                <p class="description" style="color: #c9356e">
                                                    * This feature only available on Premium Version. Click <a
                                                            href="https://wpclever.net/downloads/woocommerce-smart-compare?utm_source=pro&utm_medium=woosc&utm_campaign=wporg"
                                                            target="_blank">here</a> to buy, just $29!
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Image size', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$image_size          = get_option( '_wooscp_image_size', 'wooscp-large' );
												$image_sizes         = $this->wooscp_get_image_sizes();
												$image_sizes['full'] = array(
													'width'  => '',
													'height' => '',
													'crop'   => false
												);

												if ( ! empty( $image_sizes ) ) {
													echo '<select name="_wooscp_image_size">';

													foreach ( $image_sizes as $image_size_name => $image_size_data ) {
														echo '<option value="' . esc_attr( $image_size_name ) . '" ' . ( $image_size_name === $image_size ? 'selected' : '' ) . '>' . esc_attr( $image_size_name ) . ( ! empty( $image_size_data['width'] ) ? ' ' . $image_size_data['width'] . '&times;' . $image_size_data['height'] : '' ) . ( $image_size_data['crop'] ? ' (cropped)' : '' ) . '</option>';
													}

													echo '</select>';
												}
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Link to individual product', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_link">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_link', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes, open in the same tab', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="yes_blank" <?php echo( get_option( '_wooscp_link', 'yes' ) === 'yes_blank' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes, open in the new tab', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="yes_popup" <?php echo( get_option( '_wooscp_link', 'yes' ) === 'yes_popup' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes, open quick view popup', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_link', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select> <span class="description">If you choose "Open quick view popup", please install <a
                                                            href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-quick-view&TB_iframe=true&width=800&height=550' ) ); ?>"
                                                            class="thickbox" title="Install WPC Smart Quick View">WPC Smart Quick View</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Freeze first column', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_freeze_column">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_freeze_column', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_freeze_column', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select> <span class="description">
													<?php esc_html_e( 'Freeze the first column (fields and attributes title) when scrolling horizontally.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Freeze first row', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_freeze_row">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_freeze_row', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_freeze_row', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select> <span class="description">
													<?php esc_html_e( 'Freeze the first row (product name) when scrolling vertically.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Close button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_close_button">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_close_button', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_close_button', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
											<?php esc_html_e( 'Enable the close button at top-right conner of compare table?', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Compare button', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td>
												<?php esc_html_e( 'Settings for the compare button in each product.', 'woo-smart-compare' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Type', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_button_type">
                                                    <option
                                                            value="button" <?php echo( get_option( '_wooscp_button_type', 'button' ) === 'button' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Button', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="link" <?php echo( get_option( '_wooscp_button_type', 'button' ) === 'link' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Link', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="text" name="_wooscp_button_text"
                                                       value="<?php echo get_option( '_wooscp_button_text' ); ?>"
                                                       placeholder="<?php esc_html_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                <span class="description">
													<?php esc_html_e( 'Leave blank if you want to use the default text and can be translated.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Change button text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_button_text_change">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_button_text_change', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_button_text_change', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
													<?php esc_html_e( 'Change the button text after adding to the compare. If not, only add the class CSS name \'woosc-added\'.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Added text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="text" name="_wooscp_button_text_added"
                                                       value="<?php echo get_option( '_wooscp_button_text_added' ); ?>"
                                                       placeholder="<?php esc_html_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                <span class="description">
													<?php esc_html_e( 'Leave blank if you want to use the default text and can be translated.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Extra class (optional)', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="text" name="_wooscp_button_class"
                                                       value="<?php echo get_option( '_wooscp_button_class', '' ); ?>"/>
                                                <span class="description">
													<?php esc_html_e( 'Add extra class for action button/link, split by one space.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position on products list', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$button_archive = apply_filters( 'filter_wooscp_button_archive', 'default' );
												$button_archive = apply_filters( 'woosc_button_position_archive', $button_archive );
												?>
                                                <select name="_wooscp_button_archive" <?php echo( $button_archive !== 'default' ? 'disabled' : '' ); ?>>
													<?php if ( $button_archive === 'default' ) {
														$button_archive = get_option( '_wooscp_button_archive', 'after_add_to_cart' );
													} ?>
                                                    <option value="after_title" <?php echo( $button_archive === 'after_title' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under title', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="after_rating" <?php echo( $button_archive === 'after_rating' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under rating', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="after_price" <?php echo( $button_archive === 'after_price' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under price', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="before_add_to_cart" <?php echo( $button_archive === 'before_add_to_cart' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Above add to cart', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="after_add_to_cart" <?php echo( $button_archive === 'after_add_to_cart' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under add to cart', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="0" <?php echo( ! $button_archive || ( $button_archive === '0' ) ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'None (hide it)', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position on single product page', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$button_single = apply_filters( 'filter_wooscp_button_single', 'default' );
												$button_single = apply_filters( 'woosc_button_position_single', $button_single );
												?>
                                                <select name="_wooscp_button_single" <?php echo( $button_single !== 'default' ? 'disabled' : '' ); ?>>
													<?php if ( $button_single === 'default' ) {
														$button_single = get_option( '_wooscp_button_single', '31' );
													} ?>
                                                    <option value="6" <?php echo( $button_single === '6' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under title', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="11" <?php echo( $button_single === '11' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under price & rating', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="21" <?php echo( $button_single === '21' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under excerpt', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="29" <?php echo( $button_single === '29' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Above add to cart', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="31" <?php echo( $button_single === '31' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under add to cart', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="41" <?php echo( $button_single === '41' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under meta', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="51" <?php echo( $button_single === '51' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Under sharing', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option value="0" <?php echo( ! $button_single || ( $button_single === '0' ) ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'None (hide it)', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Manual', 'woo-smart-compare' ); ?></th>
                                            <td>
												<span class="description">
													<?php
													printf( esc_html__( 'You can use the shortcode %s, eg. %s for the product with ID is 99.', 'woo-smart-compare' ), '<code>[woosc id="{product id}"]</code>', '<code>[woosc id="99"]</code>' );
													?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Categories', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$selected_cats = get_option( '_wooscp_search_cats' );

												if ( empty( $selected_cats ) ) {
													$selected_cats = array( '0' );
												}

												wc_product_dropdown_categories(
													array(
														'name'             => '_wooscp_search_cats',
														'hide_empty'       => 0,
														'value_field'      => 'id',
														'multiple'         => true,
														'show_option_all'  => esc_html__( 'All categories', 'woo-smart-compare' ),
														'show_option_none' => '',
														'selected'         => implode( ',', $selected_cats )
													) );
												?>
                                                <span class="description">
													<?php esc_html_e( 'Only show the Compare button for products in selected categories.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Remove when clicking again', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_click_again">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_click_again', 'no' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_click_again', 'no' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
											<?php esc_html_e( 'Do you want to remove product when clicking again?', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Compare bar', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td>
												<?php esc_html_e( 'Settings for the compare bar.', 'woo-smart-compare' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Bubble', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_bar_bubble">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_bar_bubble', 'no' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_bar_bubble', 'no' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
													<?php esc_html_e( 'Use the bubble instead of a fully compare bar.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Settings" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_bar_settings">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_bar_settings', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_bar_settings', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
													<?php esc_html_e( 'Show the settings popup to customize fields (show/ hide / rearrange).', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Add more" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_bar_add">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_bar_add', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_bar_add', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
													<?php esc_html_e( 'Add the button to search product and add to compare list immediately.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Add more" count', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="number" min="1" max="100" name="_wooscp_search_count"
                                                       value="<?php echo get_option( '_wooscp_search_count', 10 ); ?>"/>
                                                <span class="description">
													<?php esc_html_e( 'The result count of search function when clicking on "Add more" button.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( '"Remove all" button', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_bar_remove">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_bar_remove', 'no' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_bar_remove', 'no' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">
											<?php esc_html_e( 'Add the button to remove all products from compare immediately.', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Background color', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php $woosc_bar_bg_color_default = apply_filters( 'wooscp_bar_bg_color_default', '#292a30' ); ?>
                                                <input type="text" name="_wooscp_bar_bg_color"
                                                       value="<?php echo get_option( '_wooscp_bar_bg_color', $woosc_bar_bg_color_default ); ?>"
                                                       class="wooscp_color_picker"/>
                                                <span class="description">
											<?php printf( esc_html__( 'Choose the background color for the compare bar, default %s', 'woo-smart-compare' ), '<code>' . $woosc_bar_bg_color_default . '</code>' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Button text', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <input type="text" name="_wooscp_bar_btn_text"
                                                       value="<?php echo get_option( '_wooscp_bar_btn_text' ); ?>"
                                                       placeholder="<?php esc_html_e( 'Compare', 'woo-smart-compare' ); ?>"/>
                                                <span class="description">
													<?php esc_html_e( 'Leave blank if you want to use the default text and can be translated.', 'woo-smart-compare' ); ?>
												</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Button color', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$woosc_bar_btn_color_default = apply_filters( 'wooscp_bar_btn_color_default', '#00a0d2' );
												?>
                                                <input type="text" name="_wooscp_bar_btn_color"
                                                       value="<?php echo get_option( '_wooscp_bar_btn_color', $woosc_bar_btn_color_default ); ?>"
                                                       class="wooscp_color_picker"/>
                                                <span class="description">
											<?php printf( esc_html__( 'Choose the color for the button on compare bar, default %s', 'woo-smart-compare' ), '<code>' . $woosc_bar_btn_color_default . '</code>' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_bar_pos">
                                                    <option
                                                            value="bottom" <?php echo( get_option( '_wooscp_bar_pos', 'bottom' ) === 'bottom' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Bottom', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="top" <?php echo( get_option( '_wooscp_bar_pos', 'bottom' ) === 'top' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Top', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Align', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_bar_align">
                                                    <option
                                                            value="right" <?php echo( get_option( '_wooscp_bar_align', 'right' ) === 'right' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Right', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="left" <?php echo( get_option( '_wooscp_bar_align', 'right' ) === 'left' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Left', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Click outside to hide', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_click_outside">
                                                    <option
                                                            value="yes" <?php echo( get_option( '_wooscp_click_outside', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="yes_empty" <?php echo( get_option( '_wooscp_click_outside', 'yes' ) === 'yes_empty' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes if empty', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="no" <?php echo( get_option( '_wooscp_click_outside', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Menu', 'woo-smart-compare' ); ?>
                                            </th>
                                            <td>
												<?php esc_html_e( 'Settings for the Compare menu item.', 'woo-smart-compare' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Menu(s)', 'woo-smart-compare' ); ?></th>
                                            <td>
												<?php
												$nav_args  = array(
													'hide_empty' => false,
													'fields'     => 'id=>name',
												);
												$nav_menus = get_terms( 'nav_menu', $nav_args );

												if ( $nav_menus ) {
													$saved_menus = get_option( '_wooscp_menus', array() );

													foreach ( $nav_menus as $nav_id => $nav_name ) {
														echo '<input type="checkbox" name="_wooscp_menus[]" value="' . $nav_id . '" ' . ( is_array( $saved_menus ) && in_array( $nav_id, $saved_menus, false ) ? 'checked' : '' ) . '/><label>' . $nav_name . '</label><br/>';
													}
												} else {
													echo '<p>' . esc_html__( 'Haven\'t any menu yet. Please go to Appearance > Menus to create one.', 'woo-smart-compare' ) . '</p>';
												}
												?>
                                                <span class="description">
											<?php esc_html_e( 'Choose the menu(s) you want to add the "compare menu" at the end.', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Action', 'woo-smart-compare' ); ?></th>
                                            <td>
                                                <select name="_wooscp_menu_action">
                                                    <option
                                                            value="open_page" <?php echo( get_option( '_wooscp_menu_action', 'open_popup' ) === 'open_page' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Open page', 'woo-smart-compare' ); ?>
                                                    </option>
                                                    <option
                                                            value="open_popup" <?php echo( get_option( '_wooscp_menu_action', 'open_popup' ) === 'open_popup' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Open popup', 'woo-smart-compare' ); ?>
                                                    </option>
                                                </select> <span class="description">
											<?php esc_html_e( 'Action when clicking on the "compare menu".', 'woo-smart-compare' ); ?>
										</span>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
                                                <input type="submit" name="submit" class="button button-primary"
                                                       value="<?php esc_html_e( 'Update Options', 'woo-smart-compare' ); ?>"/>
                                                <input type="hidden" name="action" value="update"/>
                                                <input type="hidden" name="page_options"
                                                       value="_wooscp_open_button,_wooscp_open_button_action,_wooscp_button_type,_wooscp_button_text,_wooscp_button_text_change,_wooscp_button_text_added,_wooscp_button_class,_wooscp_button_archive,_wooscp_button_single,_wooscp_open_bar_immediately,_wooscp_open_immediately,_wooscp_hide_checkout,_wooscp_click_again,_wooscp_close_button,_wooscp_hide_empty,_wooscp_bar_bubble,_wooscp_bar_settings,_wooscp_bar_add,_wooscp_bar_remove,_wooscp_bar_bg_color,_wooscp_bar_btn_text,_wooscp_bar_btn_color,_wooscp_bar_pos,_wooscp_bar_align,_wooscp_click_outside,_wooscp_limit,_wooscp_limit_notice,_wooscp_page_id,_wooscp_fields,_wooscp_attributes,_wooscp_custom_attributes,_wooscp_custom_fields,_wooscp_image_size,_wooscp_link,_wooscp_freeze_column,_wooscp_freeze_row,_wooscp_search_count,_wooscp_search_cats,_wooscp_menus,_wooscp_menu_action"/>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>Get the Premium Version just $29! <a
                                                href="https://wpclever.net/downloads/woocommerce-smart-compare?utm_source=pro&utm_medium=woosc&utm_campaign=wporg"
                                                target="_blank">https://wpclever.net/downloads/woocommerce-smart-compare</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Support customization of all attributes.</li>
                                        <li>- Support customization of all product fields, custom fields.</li>
                                        <li>- Free support of compare button’s adjustment to customers’ theme design.
                                        </li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function wooscp_load_compare_bar() {
					// get items
					$woosc_output   = '';
					$woosc_products = array();

					if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
						$woosc_products = explode( ',', $_POST['products'] );
					} else {
						$woosc_cookie = 'wooscp_products_' . md5( 'wooscp' . get_current_user_id() );

						if ( isset( $_COOKIE[ $woosc_cookie ] ) && ! empty( $_COOKIE[ $woosc_cookie ] ) ) {
							$woosc_products = explode( ',', $_COOKIE[ $woosc_cookie ] );
						}
					}

					if ( ! empty( $woosc_products ) ) {
						foreach ( $woosc_products as $woosc_product ) {
							$woosc_product_obj = wc_get_product( $woosc_product );

							if ( ! $woosc_product_obj ) {
								continue;
							}

							$woosc_product_id   = $woosc_product_obj->get_id();
							$woosc_product_name = apply_filters( 'wooscp_product_name', $woosc_product_obj->get_name() );

							$woosc_output .= '<div class="wooscp-bar-item" data-id="' . $woosc_product_id . '">';
							$woosc_output .= '<span class="wooscp-bar-item-img hint--top" aria-label="' . esc_attr( apply_filters( 'wooscp_product_title', wp_strip_all_tags( $woosc_product_name ), $woosc_product_obj ) ) . '">' . $woosc_product_obj->get_image( 'wooscp-small' ) . '</span>';
							$woosc_output .= '<span class="wooscp-bar-item-remove" data-id="' . $woosc_product_id . '"></span></div>';
						}
					}

					echo $woosc_output;
					wp_die();
				}

				function wooscp_get_compare_table( $ajax = true ) {
					// get items
					$woosc_output        = '';
					$woosc_products      = array();
					$woosc_products_data = array();

					if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
						$woosc_products = explode( ',', $_POST['products'] );
					} else {
						$woosc_cookie = 'wooscp_products_' . md5( 'wooscp' . get_current_user_id() );

						if ( isset( $_COOKIE[ $woosc_cookie ] ) && ! empty( $_COOKIE[ $woosc_cookie ] ) ) {
							if ( is_user_logged_in() ) {
								update_user_meta( get_current_user_id(), 'wooscp_products', $_COOKIE[ $woosc_cookie ] );
							}

							$woosc_products = explode( ',', $_COOKIE[ $woosc_cookie ] );
						}
					}

					if ( is_array( $woosc_products ) && ( count( $woosc_products ) > 0 ) ) {
						$_link = get_option( '_wooscp_link', 'yes' );

						if ( is_array( get_option( '_wooscp_fields' ) ) ) {
							$saved_fields = get_option( '_wooscp_fields' );
						} else {
							$saved_fields = array_keys( self::$woosc_fields );
						}

						foreach ( $woosc_products as $woosc_product ) {
							$product        = wc_get_product( $woosc_product );
							$parent_product = false;

							if ( ! $product ) {
								continue;
							}

							$product_name = apply_filters( 'wooscp_product_name', $product->get_name() );

							if ( $_link !== 'no' ) {
								$woosc_products_data[ $woosc_product ]['title'] = apply_filters( 'wooscp_product_title', '<a ' . ( $_link === 'yes_popup' ? 'class="woosq-btn" data-id="' . $woosc_product . '"' : '' ) . ' href="' . $product->get_permalink() . '" draggable="false" ' . ( $_link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . wp_strip_all_tags( $product_name ) . '</a>', $product );
							} else {
								$woosc_products_data[ $woosc_product ]['title'] = apply_filters( 'wooscp_product_title', wp_strip_all_tags( $product_name ), $product );
							}

							if ( in_array( 'image', $saved_fields, true ) ) {
								if ( $_link !== 'no' ) {
									$woosc_products_data[ $woosc_product ]['image'] = apply_filters( 'wooscp_product_image', '<a ' . ( $_link === 'yes_popup' ? 'class="woosq-btn" data-id="' . $woosc_product . '"' : '' ) . ' href="' . $product->get_permalink() . '" draggable="false" ' . ( $_link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $product->get_image( get_option( '_wooscp_image_size', 'wooscp-large' ), array( 'draggable' => 'false' ) ) . '</a>', $product );
								} else {
									$woosc_products_data[ $woosc_product ]['image'] = apply_filters( 'wooscp_product_image', $product->get_image( get_option( '_wooscp_image_size', 'wooscp-large' ), array( 'draggable' => 'false' ) ), $product );
								}
							}

							if ( in_array( 'sku', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['sku'] = apply_filters( 'wooscp_product_sku', $product->get_sku(), $product );
							}

							if ( in_array( 'price', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['price'] = apply_filters( 'wooscp_product_price', $product->get_price_html(), $product );
							}

							if ( in_array( 'stock', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['stock'] = apply_filters( 'wooscp_product_stock', wc_get_stock_html( $product ), $product );
							}

							if ( in_array( 'add_to_cart', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['add_to_cart'] = apply_filters( 'wooscp_product_add_to_cart', do_shortcode( '[add_to_cart id="' . $woosc_product . '"]' ), $product );
							}

							if ( in_array( 'description', $saved_fields, true ) ) {
								if ( $product->is_type( 'variation' ) ) {
									$woosc_products_data[ $woosc_product ]['description'] = apply_filters( 'wooscp_product_description', $product->get_description(), $product );
								} else {
									$woosc_products_data[ $woosc_product ]['description'] = apply_filters( 'wooscp_product_description', $product->get_short_description(), $product );
								}
							}

							if ( in_array( 'content', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['content'] = apply_filters( 'wooscp_product_content', apply_filters( 'the_content', $product->get_description() ), $product );
							}

							if ( in_array( 'additional', $saved_fields, true ) ) {
								ob_start();
								wc_display_product_attributes( $product );
								$additional = ob_get_clean();

								$woosc_products_data[ $woosc_product ]['additional'] = apply_filters( 'wooscp_product_additional', $additional, $product );
							}

							if ( in_array( 'weight', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['weight'] = apply_filters( 'wooscp_product_weight', $product->get_weight(), $product );
							}

							if ( in_array( 'dimensions', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['dimensions'] = apply_filters( 'wooscp_product_dimensions', wc_format_dimensions( $product->get_dimensions( false ) ), $product );
							}

							if ( in_array( 'rating', $saved_fields, true ) ) {
								$woosc_products_data[ $woosc_product ]['rating'] = apply_filters( 'wooscp_product_rating', wc_get_rating_html( $product->get_average_rating() ), $product );
							}

							if ( in_array( 'availability', $saved_fields, true ) ) {
								$product_availability                                  = $product->get_availability();
								$woosc_products_data[ $woosc_product ]['availability'] = apply_filters( 'wooscp_product_availability', $product_availability['availability'], $product );
							}
						}

						$woosc_table_class = 'wooscp_table';

						if ( count( $woosc_products_data ) === 2 ) {
							$woosc_products_data['p1']['title'] = '';
							$woosc_table_class                  .= ' has-2';
						} elseif ( count( $woosc_products_data ) === 1 ) {
							$woosc_products_data['p1']['title'] = '';
							$woosc_products_data['p2']['title'] = '';
							$woosc_table_class                  .= ' has-1';
						}

						$woosc_output .= '<table ' . ( $ajax ? 'id="wooscp_table"' : '' ) . ' class="' . esc_attr( $woosc_table_class ) . '"><thead><tr><th>&nbsp;</th>';

						foreach ( $woosc_products_data as $woosc_product ) {
							if ( $woosc_product['title'] !== '' ) {
								$woosc_output .= '<th>' . $woosc_product['title'] . '</th>';
							} else {
								$woosc_output .= '<th class="th-placeholder"></th>';
							}
						}

						$woosc_output .= '</tr></thead><tbody>';

						$cookie_fields = $this->wooscp_get_cookie_fields( $saved_fields );
						$saved_fields  = array_unique( array_merge( $cookie_fields, $saved_fields ), SORT_REGULAR );

						foreach ( $saved_fields as $saved_field ) {
							if ( ! isset( self::$woosc_fields[ $saved_field ] ) ) {
								continue;
							}

							if ( ! in_array( $saved_field, array(
								'attributes',
								'custom_attributes',
								'custom_fields'
							) ) ) {
								$woosc_output .= '<tr class="tr-default tr-' . esc_attr( $saved_field ) . ' ' . ( ! in_array( $saved_field, $cookie_fields, false ) ? 'tr-hide' : '' ) . '"><td class="td-label">' . esc_html( self::$woosc_fields[ $saved_field ] ) . '</td>';

								foreach ( $woosc_products_data as $woosc_product ) {
									if ( $woosc_product['title'] !== '' ) {
										if ( isset( $woosc_product[ $saved_field ] ) ) {
											$woosc_output .= '<td>' . $woosc_product[ $saved_field ] . '</td>';
										} else {
											$woosc_output .= '<td>&nbsp;</td>';
										}
									} else {
										$woosc_output .= '<td class="td-placeholder"></td>';
									}
								}

								$woosc_output .= '</tr>';
							}
						}

						$woosc_output .= '</tbody></table>';
					} else {
						$woosc_output = '<div class="wooscp-no-result">' . esc_html__( 'No products in the compare table.', 'woo-smart-compare' ) . '</div>';
					}

					return $woosc_output;
				}

				function wooscp_load_compare_table() {
					echo $this->wooscp_get_compare_table();
					wp_die();
				}

				function wooscp_search() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wooscp-nonce' ) ) {
						die( 'Permissions check failed' );
					}

					$keyword       = sanitize_text_field( $_POST['keyword'] );
					$selected_cats = get_option( '_wooscp_search_cats' );

					if ( empty( $selected_cats ) ) {
						$selected_cats = array( '0' );
					}

					$woosc_query_args = array(
						's'              => $keyword,
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => get_option( '_wooscp_search_count', 10 )
					);

					if ( is_array( $selected_cats ) && ( count( $selected_cats ) > 0 ) && ( $selected_cats[0] !== '0' ) ) {
						$woosc_query_args['tax_query'] = array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'terms'    => $selected_cats,
							),
						);
					}

					$woosc_query = new WP_Query( $woosc_query_args );

					if ( $woosc_query->have_posts() ) {
						echo '<ul>';

						while ( $woosc_query->have_posts() ) {
							$woosc_query->the_post();
							echo '<li>';
							echo '<div class="item-inner">';
							echo '<div class="item-image">' . get_the_post_thumbnail( get_the_ID(), 'wooscp-small' ) . '</div>';
							echo '<div class="item-name">' . get_the_title() . '</div>';
							echo '<div class="item-add wooscp-item-add" data-id="' . get_the_ID() . '"><span>+</span></div>';
							echo '</div>';
							echo '</li>';
						}

						echo '</ul>';
						wp_reset_postdata();
					} else {
						echo '<ul><span>' . sprintf( esc_html__( 'No results found for "%s"', 'woo-smart-compare' ), $keyword ) . '</span></ul>';
					}
					wp_die();
				}

				function wooscp_add_button() {
					echo do_shortcode( '[woosc]' );
				}

				function wooscp_shortcode( $atts ) {
					$output = '';

					$atts = shortcode_atts( array(
						'id'   => null,
						'type' => get_option( '_wooscp_button_type', 'button' )
					), $atts );

					if ( ! $atts['id'] ) {
						global $product;
						$atts['id'] = $product->get_id();
					}

					if ( $atts['id'] ) {
						// check cats
						$selected_cats = get_option( '_wooscp_search_cats' );

						if ( ! empty( $selected_cats ) && ( $selected_cats[0] !== '0' ) ) {
							if ( ! has_term( $selected_cats, 'product_cat', $atts['id'] ) ) {
								return '';
							}
						}

						// button text
						$button_text = get_option( '_wooscp_button_text' );

						if ( empty( $button_text ) ) {
							$button_text = esc_html__( 'Compare', 'woo-smart-compare' );
						}

						if ( $atts['type'] === 'link' ) {
							$output = '<a href="#" class="woosc-btn wooscp-btn wooscp-btn-' . esc_attr( $atts['id'] ) . ' ' . get_option( '_wooscp_button_class' ) . '" data-id="' . esc_attr( $atts['id'] ) . '">' . esc_html( $button_text ) . '</a>';
						} else {
							$output = '<button class="woosc-btn wooscp-btn wooscp-btn-' . esc_attr( $atts['id'] ) . ' ' . get_option( '_wooscp_button_class' ) . '" data-id="' . esc_attr( $atts['id'] ) . '">' . esc_html( $button_text ) . '</button>';
						}
					}

					return apply_filters( 'wooscp_button_html', $output, $atts['id'] );
				}

				function wooscp_shortcode_list( $atts ) {
					return '<div class="wooscp_list wooscp_page">' . $this->wooscp_get_compare_table( false ) . '</div>';
				}

				function wooscp_wp_footer() {
					$woosc_class = 'wooscp-area';
					$woosc_class .= ' wooscp-bar-' . get_option( '_wooscp_bar_pos', 'bottom' ) . ' wooscp-bar-' . get_option( '_wooscp_bar_align', 'right' ) . ' wooscp-bar-click-outside-' . str_replace( '_', '-', get_option( '_wooscp_click_outside', 'yes' ) );

					if ( get_option( '_wooscp_hide_checkout', 'yes' ) === 'yes' ) {
						$woosc_class .= ' wooscp-hide-checkout';
					}

					$woosc_bar_bg_color_default  = apply_filters( 'wooscp_bar_bg_color_default', '#292a30' );
					$woosc_bar_btn_color_default = apply_filters( 'wooscp_bar_btn_color_default', '#00a0d2' );
					?>
					<?php if ( get_option( '_wooscp_bar_add', 'yes' ) === 'yes' ) { ?>
                        <div class="wooscp-popup wooscp-search">
                            <div class="wooscp-popup-inner">
                                <div class="wooscp-popup-content">
                                    <div class="wooscp-popup-content-inner">
                                        <div class="wooscp-popup-close"></div>
                                        <div class="wooscp-search-input">
                                            <input type="search" id="wooscp_search_input"
                                                   placeholder="<?php esc_html_e( 'Type any keyword to search...', 'woo-smart-compare' ); ?>"/>
                                        </div>
                                        <div class="wooscp-search-result"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php } ?>
					<?php if ( get_option( '_wooscp_bar_settings', 'yes' ) === 'yes' ) { ?>
                        <div class="wooscp-popup wooscp-settings">
                            <div class="wooscp-popup-inner">
                                <div class="wooscp-popup-content">
                                    <div class="wooscp-popup-content-inner">
                                        <div class="wooscp-popup-close"></div>
										<?php esc_html_e( 'Select the fields to be shown. Others will be hidden. Drag and drop to rearrange the order.', 'woo-smart-compare' ); ?>
                                        <ul class="wooscp-settings-fields">
											<?php
											if ( is_array( get_option( '_wooscp_fields' ) ) ) {
												$saved_fields = get_option( '_wooscp_fields' );
											} else {
												$saved_fields = array_keys( self::$woosc_fields );
											}

											$cookie_fields = $this->wooscp_get_cookie_fields( $saved_fields );
											$fields_merge  = array_unique( array_merge( $cookie_fields, $saved_fields ), SORT_REGULAR );

											foreach ( $fields_merge as $field ) {
												echo '<li class="wooscp-settings-field-li"><input type="checkbox" class="wooscp-settings-field" value="' . $field . '" ' . ( in_array( $field, $cookie_fields, false ) ? 'checked' : '' ) . '/><span class="label">' . self::$woosc_fields[ $field ] . '</span></li>';
											}
											?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php } ?>
                    <div id="wooscp-area" class="<?php echo esc_attr( $woosc_class ); ?>"
                         data-bg-color="<?php echo apply_filters( 'wooscp_bar_bg_color', get_option( '_wooscp_bar_bg_color', $woosc_bar_bg_color_default ) ); ?>"
                         data-btn-color="<?php echo apply_filters( 'wooscp_bar_btn_color', get_option( '_wooscp_bar_btn_color', $woosc_bar_btn_color_default ) ); ?>">
                        <div class="wooscp-inner">
                            <div class="wooscp-table">
                                <div class="wooscp-table-inner">
									<?php if ( 'yes' === get_option( '_wooscp_close_button', 'yes' ) ) { ?>
                                        <a href="javascript:void(0);" id="wooscp-table-close"
                                           class="wooscp-table-close hint--left"
                                           aria-label="<?php esc_attr_e( 'Close', 'woo-smart-compare' ); ?>"><span
                                                    class="wooscp-table-close-icon"></span></a>
									<?php } ?>
                                    <div class="wooscp-table-items"></div>
                                </div>
                            </div>
                            <div class="wooscp-bar <?php echo esc_attr( get_option( '_wooscp_bar_bubble', 'no' ) === 'yes' ? 'wooscp-bar-bubble' : '' ); ?>">
								<?php if ( get_option( '_wooscp_click_outside', 'yes' ) !== 'no' && get_option( '_wooscp_bar_bubble', 'no' ) !== 'yes' ) { ?>
                                    <div class="wooscp-bar-notice">
										<?php esc_html_e( 'Click outside to hide the compare bar', 'woo-smart-compare' ); ?>
                                    </div>
								<?php }

								if ( get_option( '_wooscp_bar_settings', 'yes' ) === 'yes' ) { ?>
                                    <a href="javascript:void(0);" class="wooscp-bar-settings hint--top"
                                       aria-label="<?php esc_attr_e( 'Select fields', 'woo-smart-compare' ); ?>"></a>
								<?php }

								if ( get_option( '_wooscp_bar_add', 'yes' ) === 'yes' ) { ?>
                                    <a href="javascript:void(0);" class="wooscp-bar-search hint--top"
                                       aria-label="<?php esc_attr_e( 'Add product', 'woo-smart-compare' ); ?>"></a>
								<?php }

								echo '<div class="wooscp-bar-items"></div>';

								if ( get_option( '_wooscp_bar_remove', 'no' ) === 'yes' ) { ?>
                                    <div class="wooscp-bar-remove hint--top"
                                         aria-label="<?php esc_attr_e( 'Remove all', 'woo-smart-compare' ); ?>"></div>
								<?php } ?>

                                <div class="wooscp-bar-btn wooscp-bar-btn-text">
                                    <div class="wooscp-bar-btn-icon-wrapper">
                                        <div class="wooscp-bar-btn-icon-inner"><span></span><span></span><span></span>
                                        </div>
                                    </div>
									<?php
									$bar_btn_text = get_option( '_wooscp_bar_btn_text' );

									if ( empty( $bar_btn_text ) ) {
										$bar_btn_text = esc_html__( 'Compare', 'woo-smart-compare' );
									}

									echo apply_filters( 'wooscp_bar_btn_text', esc_html( $bar_btn_text ) );
									?>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
				}

				function wooscp_get_cookie_fields( $saved_fields ) {
					$cookie_fields = 'wooscp_fields_' . md5( 'wooscp' . get_current_user_id() );

					if ( isset( $_COOKIE[ $cookie_fields ] ) && ! empty( $_COOKIE[ $cookie_fields ] ) ) {
						$woosc_fields = explode( ',', $_COOKIE[ $cookie_fields ] );
					} else {
						$woosc_fields = $saved_fields;
					}

					return $woosc_fields;
				}

				public static function wooscp_get_count() {
					$woosc_products = array();

					if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
						$woosc_products = explode( ',', $_POST['products'] );
					} else {
						$woosc_cookie = 'wooscp_products_' . md5( 'wooscp' . get_current_user_id() );

						if ( isset( $_COOKIE[ $woosc_cookie ] ) && ! empty( $_COOKIE[ $woosc_cookie ] ) ) {
							$woosc_products = explode( ',', $_COOKIE[ $woosc_cookie ] );
						}
					}

					return count( $woosc_products );
				}

				public static function wooscp_get_page_url() {
					$page_id  = get_option( '_wooscp_page_id' );
					$page_url = ! empty( $page_id ) ? get_permalink( $page_id ) : '#';

					return esc_url( $page_url );
				}

				function wooscp_nav_menu_items( $items, $args ) {
					$selected    = false;
					$saved_menus = get_option( '_wooscp_menus', array() );

					if ( ! is_array( $saved_menus ) || empty( $saved_menus ) || ! property_exists( $args, 'menu' ) ) {
						return $items;
					}

					if ( $args->menu instanceof WP_Term ) {
						// menu object
						if ( in_array( $args->menu->term_id, $saved_menus, false ) ) {
							$selected = true;
						}
					} elseif ( is_numeric( $args->menu ) ) {
						// menu id
						if ( in_array( $args->menu, $saved_menus, false ) ) {
							$selected = true;
						}
					} elseif ( is_string( $args->menu ) ) {
						// menu slug or name
						$menu = get_term_by( 'name', $args->menu, 'nav_menu' );

						if ( ! $menu ) {
							$menu = get_term_by( 'slug', $args->menu, 'nav_menu' );
						}

						if ( $menu && in_array( $menu->term_id, $saved_menus, false ) ) {
							$selected = true;
						}
					}

					if ( $selected ) {
						$items .= '<li class="menu-item wooscp-menu-item menu-item-type-wooscp"><a href="' . self::wooscp_get_page_url() . '"><span class="wooscp-menu-item-inner" data-count="' . $this->wooscp_get_count() . '">' . esc_html__( 'Compare', 'woo-smart-compare' ) . '</span></a></li>';
					}

					return $items;
				}

				function wooscp_get_image_sizes() {
					global $_wp_additional_image_sizes;
					$sizes = array();

					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = array(
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							);
						}
					}

					return $sizes;
				}

				function wooscp_nice_class_id( $str ) {
					return preg_replace( '/[^a-zA-Z0-9#._-]/', '', $str );
				}
			}

			new WPCleverWooscp();
		}
	}
} else {
	add_action( 'admin_notices', 'wooscp_notice_premium' );
}

if ( ! function_exists( 'wooscp_notice_wc' ) ) {
	function wooscp_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Smart Compare</strong> require WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'wooscp_notice_premium' ) ) {
	function wooscp_notice_premium() {
		?>
        <div class="error">
            <p>Seems you're using both free and premium version of <strong>WPC Smart Compare</strong>. Please
                deactivate the free version when using the premium version.</p>
        </div>
		<?php
	}
}
