<?php

if (!class_exists('Redux')) {
    return;
}


// This is your option name where all the Redux data is stored.
$opt_name = "xc_woo_whatsapp_order";

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name' => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name' => XC_WOO_WHATSAPP_ORDER_NAME,
    // Name that appears at the top of your panel
    'display_version' => XC_WOO_WHATSAPP_ORDER_VERSION,
    // Version that appears at the top of your panel
    'menu_type' => 'submenu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu' => true,
    // Show the sections below the admin menu item or not
    'menu_title' => __('Orders on Whatsapp', 'xc-woo-whatsapp-order'),
    'page_title' => __('Orders on Whatsapp', 'xc-woo-whatsapp-order'),
    'async_typography' => false,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar' => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon' => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority' => 50,
    'dev_mode' => false,
    // Show the time the page took to load, etc
    'update_notice' => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer' => true,
    // OPTIONAL -> Give you extra features
    'page_priority' => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent' => 'woocommerce',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions' => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon' => '',
    // Specify a custom URL to an icon
    'last_tab' => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon' => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug' => XC_WOO_WHATSAPP_ORDER_SLUG,
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults' => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show' => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark' => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export' => true,
    // Shows the Import/Export panel when not used as a field.
    // CAREFUL -> These options are for advanced use only
    'transient_time' => 60 * MINUTE_IN_SECONDS,
    'output' => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag' => true,
    'use_cdn' => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
    // HINTS
    'hints' => array(
        'icon' => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'red',
            'shadow' => true,
            'rounded' => false,
            'style' => '',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'mouseover',
            ),
            'hide' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'click mouseleave',
            ),
        ),
    )
);


$args['share_icons'][] = array(
    'url' => 'https://www.facebook.com/XpertsClub/',
    'title' => 'Like us on Facebook',
    'icon' => 'el el-facebook'
);


// Add content after the form.
$args['footer_text'] = '&copy; ' . date('Y') . ' XpertsClub';

Redux::setArgs($opt_name, $args);

/*
 * ---> END ARGUMENTS
 */


/*
 * ---> START HELP TABS
 */

$tabs = array(
    array(
        'id' => 'help-tab-1',
        'title' => __('Information', 'xc-woo-whatsapp-order'),
        'content' => __('<p>Need support? Please email us at admin@xperts.club.</p>', 'xc-woo-whatsapp-order')
    )
);
Redux::setHelpTab($opt_name, $tabs);


/*
 * <--- END HELP TABS
 */


/*
 *
 * ---> START SECTIONS
 *
 */

/*

  As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


 */

// -> START Basic Fields
Redux::setSection($opt_name, array(
    'title' => __('Settings', 'xc-woo-whatsapp-order'),
    'id' => 'settings',
    'desc' => __('These are really General fields!', 'xc-woo-whatsapp-order'),
    'customizer_width' => '400px',
    'icon' => 'el el-cogs'
));

Redux::setSection($opt_name, array(
    'title' => __('General', 'xc-woo-whatsapp-order'),
    'id' => 'settings-general',
    'subsection' => true,
    'customizer_width' => '450px',
    //'desc' => __('', 'xc-woo-whatsapp-order'),
    'fields' => array(
        array(
            'id' => 'enable',
            'type' => 'checkbox',
            'title' => __('Enable', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Enable Orders on Whatsapp options below', 'xc-woo-whatsapp-order'),
        ),
        array(
            'id' => 'enable-on-category',
            'type' => 'checkbox',
            'title' => __('Enable Category Products', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Enable Orders on Whatsapp in category pages', 'xc-woo-whatsapp-order'),
        ),
        array(
            'id' => 'remove-add-to-cart-botton',
            'type' => 'checkbox',
            'title' => __('Remove add to cart button', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Removes the add to cart button on single product and category pages.', 'xc-woo-whatsapp-order'),
            //'desc'     => __( 'This is the description field, again good for additional info.', 'xc-woo-whatsapp-order' ),
            'default' => '1'// 1 = on | 0 = off
        ),
        array(
            'id' => 'remove-price',
            'type' => 'checkbox',
            'title' => __('Remove price', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Removes price on single product and category pages.', 'xc-woo-whatsapp-order'),
            //'desc'     => __( 'This is the description field, again good for additional info.', 'xc-woo-whatsapp-order' ),
            'default' => '1'// 1 = on | 0 = off
        ),
        array(
            'id' => 'redirect',
            'type' => 'checkbox',
            'title' => __('Redirect Cart / Checkout Page', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Redirects the cart and checkout page to another page.', 'xc-woo-whatsapp-order'),
        ),
        array(
            'id' => 'select-page',
            'type' => 'select',
            'title' => __('Select Page', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Redirects the cart and checkout page to', 'xc-woo-whatsapp-order'),
            'data' => 'pages',
            'args' => array(
                'posts_per_page' => -1,
            ),
			'required' => array('redirect','equals','1'),
        ),
		
		array(
            'id' => 'hide_desktop',
            'type' => 'checkbox',
            'title' => __('Hide WhatsApp button on Desktops', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Larger Than : 786px', 'xc-woo-whatsapp-order'),
        ),
		
		array(
            'id' => 'hide_mobile',
            'type' => 'checkbox',
            'title' => __('Hide WhatsApp button on Mobiles', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Smaller Than : 785px', 'xc-woo-whatsapp-order'),
        ),
		
    )
));
Redux::setSection($opt_name, array(
    'title' => __('WhatsApp', 'xc-woo-whatsapp-order'),
    'id' => 'settings-whatsapp',
    'subsection' => true,
    'customizer_width' => '500px',
    'fields' => array(
        array(
            'id' => 'whatsapp-no',
            'type' => 'text',
            'title' => __('Whatsapp Number', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Whatsapp Number', 'xc-woo-whatsapp-order'),
        ),
        array(
            'id' => 'whatsapp-message',
            'type' => 'textarea',
            'title' => __('Default Message', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Available placeholder {product_name}, {product_price}, {product_link}', 'xc-woo-whatsapp-order'),
            'default' => 'Hi I would like to buy {product_name}',
        ),
        array(
            'id' => 'button',
            'type' => 'text',
            'title' => __('Button Text', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Buy on whatsapp', 'xc-woo-whatsapp-order'),
            'default' => 'Buy on whatsapp',
        ),
    )
));

Redux::setSection($opt_name, array(
    'title' => __('WhatsApp Cart', 'xc-woo-whatsapp-order'),
    'id' => 'settings-whatsapp-cart',
    'subsection' => true,
    'customizer_width' => '500px',
	'desc'    => __("Instead of the checkout Process, your customers will see a “Buy on WhatsApp” button on the cart page, where they can send you a WhatsApp message with all the products they have in their current cart.", 'xc-woo-whatsapp-order'),
    'fields' => array(
		array(
            'id' => 'enable-whatsapp-cart',
            'type' => 'checkbox',
            'title' => __('Enable WhatsApp Cart', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Enable Orders on Whatsapp options below', 'xc-woo-whatsapp-order'),
			'desc'    => __("If you enable the WhatsApp Cart Make sure you have not enabled to remove the add to cart button in general settings. Add to cart changes to Add to WhatsApp Cart", 'xc-woo-whatsapp-order'),
        ),
		array(
            'id' => 'add-to-cart-text',
            'type' => 'text',
            'title' => __('Add to cart button text', 'xc-woo-whatsapp-order'),
            'default' => 'Add to WhatsApp Cart',
        ),
		
		array(
            'id' => 'buy-on-whatsapp-text',
            'type' => 'text',
            'title' => __('Buy on WhatsApp button text', 'xc-woo-whatsapp-order'),
            'default' => 'Buy on WhatsApp',
			'desc'    => __("This button display on cart page instead of the checkout button", 'xc-woo-whatsapp-order'),
        ),
		
		array(
            'id' => 'buy-on-whatsapp-prefix',
            'type' => 'textarea',
            'title' => __('WhatsApp cart message prefix text', 'xc-woo-whatsapp-order'),
            'default' => 'Hi, I would like to buy the following products.',
			'desc'    => __("This message display on whatsapp message before cart products", 'xc-woo-whatsapp-order'),
        ),
		
		array(
            'id' => 'buy-on-whatsapp-cart-inludes',
            'type' => 'select',
			'multi' => true,
            'title' => __('WhatsApp cart product details includes', 'xc-woo-whatsapp-order'),
			'desc'    => __("select what product fields are included on whatsapp cart message", 'xc-woo-whatsapp-order'),
			'options' => array(
							'qty' => __('Quantity', 'xc-woo-whatsapp-order'),
							'name' => __('Product Name', 'xc-woo-whatsapp-order'),
							'price' => __('Line Total', 'xc-woo-whatsapp-order'),
							'link' => __('Product Link', 'xc-woo-whatsapp-order'),
							'total' => __('Cart Total', 'xc-woo-whatsapp-order'),
						),
			'default' => array('qty' => '1', 'name' => '1', 'price' => '1', 'link' => '0', 'total' => '1'),			
        ),
		
		
		array(
            'id' => 'buy-on-whatsapp-suffix',
            'type' => 'textarea',
            'title' => __('WhatsApp cart message suffix text', 'xc-woo-whatsapp-order'),
            'default' => 'Thank you.',
			'desc'    => __("This message display on whatsapp message after cart products", 'xc-woo-whatsapp-order'),
        ),
		
		array(
            'id' => 'remove-coupons',
            'type' => 'checkbox',
            'title' => __('Remove Coupons from Cart', 'xc-woo-whatsapp-order'),
        ),
		
		array(
            'id' => 'remove-cross-sells',
            'type' => 'checkbox',
            'title' => __('Remove Cart Cross Sells', 'xc-woo-whatsapp-order'),
        ),
		array(
            'id' => 'empty-cart',
            'type' => 'checkbox',
            'title' => __('Empty cart', 'xc-woo-whatsapp-order'),
			'subtitle'    => __("cart will be cleared once user clicks on 'Buy on WhatsApp' button on cart page.", 'xc-woo-whatsapp-order'),
        ),
		
		array(
            'id' => 'thankyou-page',
            'type' => 'select',
            'title' => __('Select Page to redirect', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Redirects the user to this page after click on "Buy on WhatsApp" button.', 'xc-woo-whatsapp-order'),
            'data' => 'pages',
            'args' => array(
                'posts_per_page' => -1,
            ),
			//'required' => array('redirect','equals','1'),
        ),
		
	)
));

if(class_exists('WeDevs_Dokan')){
	Redux::setSection($opt_name, array(
		'title' => __('Dokan', 'xc-woo-whatsapp-order'),
		'id' => 'settings-dokan',
		'subsection' => true,
		'customizer_width' => '500px',
		'fields' => array(
			array(
				'id' => 'enable-dokan-whatsapp',
				'type' => 'checkbox',
				'title' => __('Use Dokan Vendor WhatsApp Number', 'xc-woo-whatsapp-order'),
				'subtitle' => __('Use Dokan vendor\'s WhatsApp Number instead of admin WhatsApp number on the single product page.', 'xc-woo-whatsapp-order'),
			),
			
			array(
				'id' => 'enable-dokan-whatsapp-cart',
				'type' => 'checkbox',
				'title' => __('Use Dokan Vendor WhatsApp Number for Cart', 'xc-woo-whatsapp-order'),
				'subtitle' => __('Use the Dokan vendor\'s WhatsApp Number instead of the admin WhatsApp number. for Cart. It will force user to add only one vendor products to the cart at a time', 'xc-woo-whatsapp-order'),
        )
			
		)
	));
}

Redux::setSection($opt_name, array(
    'title' => __('Limitations', 'xc-woo-whatsapp-order'),
    'id' => 'settings-limitations',
    'subsection' => true,
    'customizer_width' => '500px',
    'fields' => array(
        array(
            'id' => 'apply-user',
            'type' => 'select',
            'title' => __('Apply for users', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Select user group, where the plugin should apply.', 'xc-woo-whatsapp-order'),
            'options' => array(
                'all' => 'All',
                'registered' => 'Registered',
                'non-registered' => 'Non registered',
            ),
            'default' => '3',
        ),
        array(
            'id' => 'exclude-user',
            'type' => 'select',
            'multi' => true,
            'title' => __('Exclude User Roles', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Select user roles, where the plugin should NOT apply.', 'xc-woo-whatsapp-order'),
            'data' => 'roles',
            'args' => array(
                'posts_per_page' => -1,
            )
        ),
		
		array(
            'id' => 'exclude-product',
            'type' => 'select',
            'multi' => true,
            'title' => __('Exclude Products', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Select Products, where the plugin should NOT apply.', 'xc-woo-whatsapp-order'),
            'data' => 'posts',
            'args' => array(
                'post_type' => array( 'product' ),
				'posts_per_page' => -1
            )
        ),
		
    )
));

Redux::setSection($opt_name, array(
    'title' => __('Typography', 'xc-woo-whatsapp-order'),
    'id' => 'settings-typography',
    'subsection' => true,
    'customizer_width' => '500px',
    'fields' => array(
        array(
            'id' => 'whatapp-button-typography',
            'type' => 'typography',
            'title' => __('Whatsapp button typography', 'xc-woo-whatsapp-order'),
            'google' => true,
            'font-backup' => true,
            'output' => array('a.xc-woo-order-whatsapp-btn', 'a.xc-woo-order-whatsapp-btn svg'),
            'units' => 'px',
            'text-align' => false,
            'line-height' => false,
            'color' => false,
            'subtitle' => __('Whatsapp button typography option with each property can be called individually.', 'xc-woo-whatsapp-order'),
        ),
        array(
            'id' => 'whatapp-button-background-color',
            'type' => 'color',
            'title' => __('Whatsapp button background color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick whatsapp button background color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array('background-color' => 'a.xc-woo-order-whatsapp-btn')
        ),
        array(
            'id' => 'whatapp-button-hover-color',
            'type' => 'color',
            'title' => __('Whatsapp button hover color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick whatsapp button hover color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array('background-color' => 'a.xc-woo-order-whatsapp-btn:hover'),
        ),
        array(
            'id' => 'whatapp-text-color',
            'type' => 'color',
            'title' => __('Whatsapp text color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick whatsapp text color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array('color' => 'a.xc-woo-order-whatsapp-btn',
                'fill' => 'a.xc-woo-order-whatsapp-btn svg'
            )
        ),
        array(
            'id' => 'whatapp-text-hover-color',
            'type' => 'color',
            'title' => __('Whatsapp text hover color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick whatsapp text hover color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array(
                'color' => 'a.xc-woo-order-whatsapp-btn:hover',
                'fill' => 'a.xc-woo-order-whatsapp-btn:hover svg'
            ),
        ),
    )
));


/* floating buttons */
Redux::setSection($opt_name, array(
    'title' => __('Flouting Button Settings', 'xc-woo-whatsapp-order'),
    'id' => 'floutingsettings',
    'desc' => __('Flouting Button Settings', 'xc-woo-whatsapp-order'),
    'customizer_width' => '400px',
    'icon' => 'el el-cogs'
));

Redux::setSection($opt_name, array(
    'title' => __('Flouting Button', 'xc-woo-whatsapp-order'),
    'id' => 'settings-flouting',
    'subsection' => true,
    'customizer_width' => '500px',
    'fields' => array(
        array(
            'id' => 'floatingbutton',
            'type' => 'checkbox',
            'title' => __('Display floating button', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Display Whatsapp button on website bottom left or right corner', 'xc-woo-whatsapp-order'),
        ),
		array(
            'id' => 'floating_use_different_number',
            'type' => 'checkbox',
            'title' => __('Use different WhatsApp number for floating button', 'xc-woo-whatsapp-order'),
			'required' => array('floatingbutton','equals','1'),
        ),
		array(
            'id' => 'floating_number',
            'type' => 'text',
            'title' => __('Floating button WhatsApp number', 'xc-woo-whatsapp-order'),
			'required' => array('floating_use_different_number','equals','1'),
        ),
		array(
            'id' => 'floatingpos',
            'type' => 'radio',
            'title' => __('Floating button position on screen', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Select Whatsapp button on website bottom left or right corner', 'xc-woo-whatsapp-order'),
			'options' => array(
                'left' => __('Left bottom', 'xc-woo-whatsapp-order'),
                'right' => __('Right bottom', 'xc-woo-whatsapp-order'),
            ),
            'default' => 'left',
			'required' => array('floatingbutton','equals','1'),
        ),
		
		array(
            'id' => 'floating-message',
            'type' => 'textarea',
            'title' => __('Default Message', 'xc-woo-whatsapp-order'),
            'default' => 'Hi I need more information about your website',
			'required' => array('floatingbutton','equals','1'),
        ),
		
		array(
            'id' => 'floating_hide_desktop',
            'type' => 'checkbox',
            'title' => __('Hide Floating button on Desktops', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Larger Than : 786px', 'xc-woo-whatsapp-order'),
        ),
		
		array(
            'id' => 'floating_hide_mobile',
            'type' => 'checkbox',
            'title' => __('Hide Floating button on Mobiles', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Smaller Than : 785px', 'xc-woo-whatsapp-order'),
        ),
		
    )
));


Redux::setSection($opt_name, array(
    'title' => __('Styles', 'xc-woo-whatsapp-order'),
    'id' => 'floating-styles',
    'subsection' => true,
    'customizer_width' => '500px',
    'fields' => array(
        array(
            'id' => 'floating-button-background-color',
            'type' => 'color',
            'title' => __('Floating button background color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick floating button background color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array('background-color' => '.xc-woo-floating-whatsapp-btn .xc-woo-floating-whatsapp-button')
        ),
        array(
            'id' => 'floating-button-hover-color',
            'type' => 'color',
            'title' => __('Floating button hover color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick floting button hover color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array('background-color' => '.xc-woo-floating-whatsapp-btn .xc-woo-floating-whatsapp-button:hover'),
        ),
        array(
            'id' => 'floating-text-color',
            'type' => 'color',
            'title' => __('Floating button icon color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick floating button icon color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array('color' => '.xc-woo-floating-whatsapp-btn .xc-woo-floating-whatsapp-button svg',
                'fill' => '.xc-woo-floating-whatsapp-btn .xc-woo-floating-whatsapp-button svg'
            )
        ),
        array(
            'id' => 'floating-text-hover-color',
            'type' => 'color',
            'title' => __('Floating button icon hover color', 'xc-woo-whatsapp-order'),
            'subtitle' => __('Pick floating button icon hover color.', 'xc-woo-whatsapp-order'),
            'validate' => 'color',
            'transparent' => false,
            'output' => array(
                'color' => '.xc-woo-floating-whatsapp-btn .xc-woo-floating-whatsapp-button:hover svg',
                'fill' => '.xc-woo-floating-whatsapp-btn .xc-woo-floating-whatsapp-button:hover svg'
            ),
        ),
    )
));