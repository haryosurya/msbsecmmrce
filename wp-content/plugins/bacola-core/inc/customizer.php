<?php
/*======
*
* Kirki Settings
*
======*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

Kirki::add_config(
	'bacola_customizer', array(
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	)
);

/*======
*
* Sections
*
======*/
$sections = array(
	'shop_settings' => array (
		esc_attr__( 'Shop Settings', 'bacola' ),
		esc_attr__( 'You can customize the shop settings.', 'bacola' ),
	),
	
	'blog_settings' => array (
		esc_attr__( 'Blog Settings', 'bacola' ),
		esc_attr__( 'You can customize the blog settings.', 'bacola' ),
	),

	'header_settings' => array (
		esc_attr__( 'Header Settings', 'bacola' ),
		esc_attr__( 'You can customize the header settings.', 'bacola' ),
	),

	'main_color' => array (
		esc_attr__( 'Main Color', 'bacola' ),
		esc_attr__( 'You can customize the main color.', 'bacola' ),
	),
	
	'map_settings' => array (
		esc_attr__( 'Map Settings', 'bacola' ),
		esc_attr__( 'You can customize the map settings.', 'bacola' ),
	),

	'footer_settings' => array (
		esc_attr__( 'Footer Settings', 'bacola' ),
		esc_attr__( 'You can customize the footer settings.', 'bacola' ),
	),
	
	'bacola_widgets' => array (
		esc_attr__( 'Bacola Widgets', 'bacola' ),
		esc_attr__( 'You can customize the bacola widgets.', 'bacola' ),
	),

	'gdpr_settings' => array (
		esc_attr__( 'GDPR Settings', 'bacola' ),
		esc_attr__( 'You can customize the GDPR settings.', 'bacola' ),
	),

	'newsletter_settings' => array (
		esc_attr__( 'Newsletter Settings', 'bacola' ),
		esc_attr__( 'You can customize the Newsletter Popup settings.', 'bacola' ),
	),

);

foreach ( $sections as $section_id => $section ) {
	$section_args = array(
		'title' => $section[0],
		'description' => $section[1],
	);

	if ( isset( $section[2] ) ) {
		$section_args['type'] = $section[2];
	}

	if( $section_id == "colors" ) {
		Kirki::add_section( str_replace( '-', '_', $section_id ), $section_args );
	} else {
		Kirki::add_section( 'bacola_' . str_replace( '-', '_', $section_id ) . '_section', $section_args );
	}
}


/*======
*
* Fields
*
======*/
function bacola_customizer_add_field ( $args ) {
	Kirki::add_field(
		'bacola_customizer',
		$args
	);
}

	/*====== Header ==================================================================================*/
		/*====== Header Panels ======*/
		Kirki::add_panel (
			'bacola_header_panel',
			array(
				'title' => esc_html__( 'Header Settings', 'bacola' ),
				'description' => esc_html__( 'You can customize the header from this panel.', 'bacola' ),
			)
		);

		$sections = array (
			'header_logo' => array(
				esc_attr__( 'Logo', 'bacola' ),
				esc_attr__( 'You can customize the logo which is on header..', 'bacola' )
			),
		
			'header_general' => array(
				esc_attr__( 'Header General', 'bacola' ),
				esc_attr__( 'You can customize the header.', 'bacola' )
			),

			'header_preloader' => array(
				esc_attr__( 'Preloader', 'bacola' ),
				esc_attr__( 'You can customize the loader.', 'bacola' )
			),
			
			'header_color' => array(
				esc_attr__( 'Header Style', 'bacola' ),
				esc_attr__( 'You can customize the color.', 'bacola' )
			),
			
			'header_location_style' => array(
				esc_attr__( 'Location Style', 'bacola' ),
				esc_attr__( 'You can customize the style.', 'bacola' )
			),
			
			'header_search_style' => array(
				esc_attr__( 'Search Style', 'bacola' ),
				esc_attr__( 'You can customize the style.', 'bacola' )
			),
			
			'header_button_style' => array(
				esc_attr__( 'Button Style', 'bacola' ),
				esc_attr__( 'You can customize the style.', 'bacola' )
			),
			
			'header_sidebar_menu_style' => array(
				esc_attr__( 'Sidebar Menu Style', 'bacola' ),
				esc_attr__( 'You can customize the style.', 'bacola' )
			),

		);

		foreach ( $sections as $section_id => $section ) {
			$section_args = array(
				'title' => $section[0],
				'description' => $section[1],
				'panel' => 'bacola_header_panel',
			);

			if ( isset( $section[2] ) ) {
				$section_args['type'] = $section[2];
			}

			Kirki::add_section( 'bacola_' . str_replace( '-', '_', $section_id ) . '_section', $section_args );
		}
		
		/*====== Logo ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'image',
				'settings' => 'bacola_logo',
				'label' => esc_attr__( 'Logo', 'bacola' ),
				'description' => esc_attr__( 'You can upload a logo.', 'bacola' ),
				'section' => 'bacola_header_logo_section',
				'choices' => array(
					'save_as' => 'id',
				),
			)
		);
		
		/*====== Logo ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'image',
				'settings' => 'bacola_mobile_logo',
				'label' => esc_attr__( 'Mobile Logo', 'bacola' ),
				'description' => esc_attr__( 'You can upload a logo for the mobile.', 'bacola' ),
				'section' => 'bacola_header_logo_section',
				'choices' => array(
					'save_as' => 'id',
				),
			)
		);
		
		/*====== Logo Description ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_logo_desc',
				'label' => esc_attr__( 'Set Logo Description', 'bacola' ),
				'description' => esc_attr__( 'You can set logo description.', 'bacola' ),
				'section' => 'bacola_header_logo_section',
				'default' => 'Online Grocery Shopping Center',
			)
		);
		
		/*====== Logo Text ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_logo_text',
				'label' => esc_attr__( 'Set Logo Text', 'bacola' ),
				'description' => esc_attr__( 'You can set logo as text.', 'bacola' ),
				'section' => 'bacola_header_logo_section',
				'default' => 'Bacola',
			)
		);
		
		/*====== Logo Size ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'dimensions',
				'settings' => 'bacola_logo_size',
				'label' => esc_attr__( 'Logo Size', 'bacola' ),
				'description' => esc_attr__( 'You can set size of the logo.', 'bacola-core' ),
				'section' => 'bacola_header_logo_section',
				'default' => array(
					'width' => '164',
					'height' => '44',
				),
			)
		);
		
		bacola_customizer_add_field(
			array (
			'type'        => 'select',
			'settings'    => 'bacola_header_type',
			'label'       => esc_html__( 'Header Type', 'bacola-core' ),
			'section'     => 'bacola_header_general_section',
			'default'     => 'type-1',
			'priority'    => 10,
			'choices'     => array(
				'type1' => esc_attr__( 'Type 1', 'bacola-core' ),
				'type2' => esc_attr__( 'Type 2', 'bacola-core' ),
			),
			) 
		);

		/*====== Mobile Sticky Header Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_mobile_sticky_header',
				'label' => esc_attr__( 'Mobile Sticky Header', 'bacola-core' ),
				'description' => esc_attr__( 'You can choose status of the header on the mobile.', 'bacola-core' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);
		
		/*====== Location Filter Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_location_filter',
				'label' => esc_attr__( 'Location Filter', 'bacola-core' ),
				'description' => esc_attr__( 'You can choose status of the location filter on the header.', 'bacola-core' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);
		
		/*====== Header Search Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_header_search',
				'label' => esc_attr__( 'Header Search', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of the search on the header.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);
		
		/*====== Ajax Search Form ======*/
		if ( class_exists( 'DGWT_WC_Ajax_Search' )){
			bacola_customizer_add_field (
				array(
					'type' => 'toggle',
					'settings' => 'bacola_ajax_search_form',
					'label' => esc_attr__( 'Ajax Search Form Search Holder', 'bacola' ),
					'description' => esc_attr__( 'Replace the search bar which is on the search holder.', 'bacola' ),
					'section' => 'bacola_header_general_section',
					'default' => '0',
					'required' => array(
						array(
						  'setting'  => 'bacola_header_search',
						  'operator' => '==',
						  'value'    => '1',
						),
					),
				)
			);
		}
		
		/*====== Header Cart Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_header_cart',
				'label' => esc_attr__( 'Header Cart', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of the mini cart on the header.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);
		
		/*====== Header Mini Cart Notice ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_header_mini_cart_notice',
				'label' => esc_attr__( 'Mini Cart Notice', 'bacola' ),
				'description' => esc_attr__( 'You can add a text for the mini cart.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_header_cart',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Header Account Icon ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_header_account',
				'label' => esc_attr__( 'Account Icon / Login', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable User Login/Signup on the header.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);
		
		/*====== Header Sidebar ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_header_sidebar',
				'label' => esc_attr__( 'Sidebar Menu', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable Sidebar Menu', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);

		/*====== Header Sidebar Collapse ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_header_sidebar_collapse',
				'label' => esc_attr__( 'Disable Collapse on Frontpage', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable Sidebar Collapse on Home Page.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
				'required' => array(
					array(
					  'setting'  => 'bacola_header_sidebar',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Top Header Notice Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_top_header_notice',
				'label' => esc_attr__( 'Top Header Notice', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable the top header notice.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);
		
		/*====== Top Header Notice Text ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_top_header_notice_text',
				'label' => esc_attr__( 'Header Top Notice Text', 'bacola' ),
				'description' => esc_attr__( 'You can add a text for the top header notice.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => 'Due to the <strong>COVID 19</strong> epidemic, orders may be processed with a slight delay',
				'required' => array(
					array(
					  'setting'  => 'bacola_top_header_notice',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Top Header Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_top_header',
				'label' => esc_attr__( 'Top Header', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable the top header.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '0',
			)
		);
		
		/*====== Top Header Bar Text ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_top_header_text_icon',
				'label' => esc_attr__( 'Top Header Text Icon', 'bacola' ),
				'description' => esc_attr__( 'You can set an icon. for example: secure', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => 'secure',
				'required' => array(
					array(
					  'setting'  => 'bacola_top_header',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Top Header Bar Text ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_top_header_text',
				'label' => esc_attr__( 'Top Header Text', 'bacola' ),
				'description' => esc_attr__( 'You can add a text for the top bar text.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => '100% Secure delivery without contacting the courier',
				'required' => array(
					array(
					  'setting'  => 'bacola_top_header',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Top Header Content Text ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_top_header_content_text',
				'label' => esc_attr__( 'Top Header Content Text', 'bacola' ),
				'description' => esc_attr__( 'You can add a content text for the top bar.', 'bacola' ),
				'section' => 'bacola_header_general_section',
				'default' => 'Need help? Call Us: <strong style="color: #2bbef9;">+ 0020 500</strong>',
				'required' => array(
					array(
					  'setting'  => 'bacola_top_header',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);

		/*====== PreLoader Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_preloader',
				'label' => esc_attr__( 'Enable Loader', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable the loader.', 'bacola' ),
				'section' => 'bacola_header_preloader_section',
				'default' => '0',
			)
		);
		
		/*====== Top Header Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_top_header_size',
				'label'       => esc_attr__( 'Top Header Typography', 'bacola' ),
				'section'     => 'bacola_header_color_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '12px',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.site-header .header-top ',
					],
				],
			)
		);
		
		/*====== Top Header Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_top_bg_color',
				'label' => esc_attr__( 'Top Header Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_header_color_section',
			)
		);

		/*====== Top Header Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#3e445a',
				'settings' => 'bacola_top_color',
				'label' => esc_attr__( 'Top Header Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_header_color_section',
			)
		);
		
		/*====== Top Header Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#2bbef9',
				'settings' => 'bacola_top_hvrcolor',
				'label' => esc_attr__( 'Top Header Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for hover color.', 'bacola-core' ),
				'section' => 'bacola_header_color_section',
			)
		);
		
		/*====== Header Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_header_size',
				'label'       => esc_attr__( 'Header Typography', 'bacola' ),
				'section'     => 'bacola_header_color_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.site-header .all-categories + .primary-menu .menu > .menu-item > a , .site-header .primary-menu .menu .sub-menu .menu-item > a',
					],
				],		
			)
		);

		/*====== Header Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_bg_color',
				'label' => esc_attr__( 'Header Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_header_color_section',
			)
		);
		
		/*====== Header Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#3e445a',
				'settings' => 'bacola_color',
				'label' => esc_attr__( 'Header Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font color.', 'bacola-core' ),
				'section' => 'bacola_header_color_section',
			)
		);
		
		/*====== Header Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#2bbef9',
				'settings' => 'bacola_hvr_color',
				'label' => esc_attr__( 'Header Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for hover color.', 'bacola-core' ),
				'section' => 'bacola_header_color_section',
			)
		);
		
		/*====== Location Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_header_lct_size',
				'label'       => esc_attr__( 'Location Typography', 'bacola' ),
				'section'     => 'bacola_header_location_style_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.site-location a .location-description , .site-location a .current-location ',
					],
				],
			)
		);
		
		/*======  Location Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_lct_bg_color',
				'label' => esc_attr__( 'Location Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Background Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_lct_bg_hvrcolor',
				'label' => esc_attr__( 'Location Background Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for hover background.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Border Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#d9d9e9',
				'settings' => 'bacola_lct_brdr_color',
				'label' => esc_attr__( 'Location Border Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  Border.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Border Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#d9d9e9',
				'settings' => 'bacola_lct_brdr_hvrcolor',
				'label' => esc_attr__( 'Location Border Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for hover  Border.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#3e445a',
				'settings' => 'bacola_lct_color',
				'label' => esc_attr__( ' Location Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#3e445a',
				'settings' => 'bacola_lct_hvrcolor',
				'label' => esc_attr__( ' Location Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for hover color.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Second Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#233a95',
				'settings' => 'bacola_lct_scnd_color',
				'label' => esc_attr__( ' Location Second Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Second Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#233a95',
				'settings' => 'bacola_lct_scnd_hvrcolor',
				'label' => esc_attr__( ' Location Second Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for hover color.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Location Arrow Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#233a95',
				'settings' => 'bacola_lct_arrow_color',
				'label' => esc_attr__( ' Location Arrow Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_header_location_style_section',
			)
		);
		
		/*======  Search Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#f3f4f7',
				'settings' => 'bacola_search_bg_color',
				'label' => esc_attr__( 'Search Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_header_search_style_section',
			)
		);
		
		/*======  Search Border Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#f3f4f7',
				'settings' => 'bacola_search_brdrcolor',
				'label' => esc_attr__( 'Search Border Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_search_style_section',
			)
		);
		
		/*======  Search Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_search_color',
				'label' => esc_attr__( 'Search Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_search_style_section',
			)
		);
		
		/*======  Search Icon Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_search_icon_color',
				'label' => esc_attr__( 'Search Icon Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_search_style_section',
			)
		);
		
		/*======  Login Button Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_login_btn_bg_color',
				'label' => esc_attr__( 'Login Button Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*======  Login Button Border Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#e2e4ec',
				'settings' => 'bacola_login_btn_brdrcolor',
				'label' => esc_attr__( 'Login Button Border Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  border color.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*======  Login Button Icon Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#3e445a',
				'settings' => 'bacola_login_btn_color',
				'label' => esc_attr__( 'Login Button Icon Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for icon color.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*======  Price Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_price_color',
				'label' => esc_attr__( 'Price Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for price color.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*======  Cart Icon Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff1ee',
				'settings' => 'bacola_crt_bg_color',
				'label' => esc_attr__( 'Cart Icon Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*====== Cart Icon Border Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff1ee',
				'settings' => 'bacola_crt_brdrcolor',
				'label' => esc_attr__( 'Cart Icon Border Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  border color.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*======  Cart Icon  Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#ea2b0f',
				'settings' => 'bacola_crt_color',
				'label' => esc_attr__( 'Cart Icon Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for icon color.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*======  Cart Count Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#ea2b0f',
				'settings' => 'bacola_crt_count_bg_color',
				'label' => esc_attr__( 'Cart Count Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*======  Cart Count Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_crt_count_color',
				'label' => esc_attr__( 'Cart Count Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_button_style_section',
			)
		);
		
		/*====== Sidebar Menu Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_header_sidebar_size',
				'label'       => esc_attr__( 'Sidebar Menu Typography', 'bacola' ),
				'section'     => 'bacola_header_sidebar_menu_style_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.menu-list li.link-parent > a , .site-header .all-categories > a ',
					],
				],
			)
		);
		
		/*======  Sidebar Menu Main Title Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#2bbef9',
				'settings' => 'bacola_sidebar_title_bg',
				'label' => esc_attr__( 'Sidebar Title Background', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for background.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Main Title Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_sidebar_title_color',
				'label' => esc_attr__( 'Sidebar Title Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Main Title Arrow Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_title_arrow_color',
				'label' => esc_attr__( 'Main Title Arrow Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Second Main Title Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#edeef5',
				'settings' => 'bacola_title_second_bg',
				'label' => esc_attr__( 'Second Main Title Background', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for background.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Second Main Title Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#71778e',
				'settings' => 'bacola_title_second_color',
				'label' => esc_attr__( 'Second Main Title Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Second Main Title Border Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_title_second_brdrcolor',
				'label' => esc_attr__( 'Second Main Title Border Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		
		/*======  Sidebar Menu Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_sidebar_bg',
				'label' => esc_attr__( 'Sidebar Menu Background', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for background.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Border Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#e4e5ee',
				'settings' => 'bacola_sidebar_brdrcolor',
				'label' => esc_attr__( 'Sidebar Menu Border Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for border color.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#3e445a',
				'settings' => 'bacola_sidebar_color',
				'label' => esc_attr__( 'Sidebar Menu Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		
		/*======  Sidebar Menu Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#2bbef9',
				'settings' => 'bacola_sidebar_hvrcolor',
				'label' => esc_attr__( 'Sidebar Menu Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font-color.', 'bacola-core' ),
				'section' => 'bacola_header_sidebar_menu_style_section',
			)
		);
		

	/*====== SHOP ====================================================================================*/
		/*====== Shop Panels ======*/
		Kirki::add_panel (
			'bacola_shop_panel',
			array(
				'title' => esc_html__( 'Shop Settings', 'bacola-core' ),
				'description' => esc_html__( 'You can customize the shop from this panel.', 'bacola-core' ),
			)
		);

		$sections = array (
			'shop_general' => array(
				esc_attr__( 'General', 'bacola-core' ),
				esc_attr__( 'You can customize shop settings.', 'bacola-core' )
			),
			
			'shop_single' => array(
				esc_attr__( 'Product Detail', 'bacola-core' ),
				esc_attr__( 'You can customize the product single settings.', 'bacola-core' )
			),
			
			'shop_banner' => array(
				esc_attr__( 'Banner', 'bacola-core' ),
				esc_attr__( 'You can customize the banner.', 'bacola-core' )
			),
			
		);

		foreach ( $sections as $section_id => $section ) {
			$section_args = array(
				'title' => $section[0],
				'description' => $section[1],
				'panel' => 'bacola_shop_panel',
			);

			if ( isset( $section[2] ) ) {
				$section_args['type'] = $section[2];
			}

			Kirki::add_section( 'bacola_' . str_replace( '-', '_', $section_id ) . '_section', $section_args );
		}
		
		/*====== Shop Layouts ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'radio-buttonset',
				'settings' => 'bacola_shop_layout',
				'label' => esc_attr__( 'Layout', 'bacola' ),
				'description' => esc_attr__( 'You can choose a layout for the shop.', 'bacola' ),
				'section' => 'bacola_shop_general_section',
				'default' => 'left-sidebar',
				'choices' => array(
					'left-sidebar' => esc_attr__( 'Left Sidebar', 'bacola' ),
					'full-width' => esc_attr__( 'Full Width', 'bacola' ),
					'right-sidebar' => esc_attr__( 'Right Sidebar', 'bacola' ),
				),
			)
		);

		/*====== Shop Width ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'radio-buttonset',
				'settings' => 'bacola_shop_width',
				'label' => esc_attr__( 'Shop Page Width', 'bacola' ),
				'description' => esc_attr__( 'You can choose a layout for the shop page.', 'bacola' ),
				'section' => 'bacola_shop_general_section',
				'default' => 'boxed',
				'choices' => array(
					'boxed' => esc_attr__( 'Boxed', 'bacola' ),
					'wide' => esc_attr__( 'Wide', 'bacola' ),
				),
			)
		);

		bacola_customizer_add_field(
			array (
			'type'        => 'radio-buttonset',
			'settings'    => 'bacola_paginate_type',
			'label'       => esc_html__( 'Pagination Type', 'bacola-core' ),
			'section'     => 'bacola_shop_general_section',
			'default'     => 'default',
			'priority'    => 10,
			'choices'     => array(
				'default' => esc_attr__( 'Default', 'bacola-core' ),
				'loadmore' => esc_attr__( 'Load More', 'bacola-core' ),
				'infinite' => esc_attr__( 'Infinite', 'bacola-core' ),
			),
			) 
		);
		
		/*====== Quantity Box Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_quantity_box',
				'label' => esc_attr__( 'Quantity Box', 'bacola-core' ),
				'description' => esc_attr__( 'Disable or Enable quantity box for the product box.', 'bacola-core' ),
				'section' => 'bacola_shop_general_section',
				'default' => '0',
			)
		);
		
		/*====== Grid-List Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_grid_list_view',
				'label' => esc_attr__( 'Grid List View', 'bacola-core' ),
				'description' => esc_attr__( 'Disable or Enable grid list view on shop page.', 'bacola-core' ),
				'section' => 'bacola_shop_general_section',
				'default' => '0',
			)
		);
		
		/*====== Perpage Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_perpage_view',
				'label' => esc_attr__( 'Perpage View', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable perpage view on shop page.', 'bacola' ),
				'section' => 'bacola_shop_general_section',
				'default' => '0',
			)
		);

		/*====== Quick View Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_quick_view_button',
				'label' => esc_attr__( 'Quick View Button', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of the quick view button.', 'bacola' ),
				'section' => 'bacola_shop_general_section',
				'default' => '0',
			)
		);
		
		/*====== Wishlist Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_wishlist_button',
				'label' => esc_attr__( 'Custom Wishlist Button', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of the wishlist button.', 'bacola' ),
				'section' => 'bacola_shop_general_section',
				'default' => '0',
			)
		);

		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_mobile_bottom_menu',
				'label' => esc_attr__( 'Mobile Bottom Menu', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable the bottom menu on mobile.', 'bacola' ),
				'section' => 'bacola_shop_general_section',
				'default' => '0',
			)
		);

		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_stock_quantity',
				'label' => esc_attr__( 'Stock Quantity', 'bacola' ),
				'description' => esc_attr__( 'Show stock quantity on the label.', 'bacola' ),
				'section' => 'bacola_shop_general_section',
				'default' => '0',
			)
		);

		/*====== Product Image Size ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'dimensions',
				'settings' => 'bacola_product_image_size',
				'label' => esc_attr__( 'Product Image Size', 'bacola-core' ),
				'description' => esc_attr__( 'You can set size of the product image for the shop page.', 'bacola-core' ),
				'section' => 'bacola_shop_general_section',
				'default' => array(
					'width' => '',
					'height' => '',
				),
			)
		);

		/*====== Shop Single Type ======*/
		bacola_customizer_add_field(
			array (
			'type'        => 'radio-buttonset',
			'settings'    => 'bacola_single_type',
			'label'       => esc_html__( 'Type (Product Detail)', 'bacola' ),
			'section'     => 'bacola_shop_single_section',
			'default'     => 'type1',
			'priority'    => 10,
			'choices'     => array(
				'type1' => esc_attr__( 'Type 1', 'bacola' ),
				'type2' => esc_attr__( 'Type 2', 'bacola' ),
				'type3' => esc_attr__( 'Type 3', 'bacola' ),
				'type4' => esc_attr__( 'Type 4', 'bacola' ),
			),
			) 
		);
		
		/*====== Shop Single Gallery Type ======*/
		bacola_customizer_add_field(
			array (
			'type'        => 'radio-buttonset',
			'settings'    => 'bacola_single_gallery_type',
			'label'       => esc_html__( 'Gallery Type (Product Detail)', 'bacola' ),
			'section'     => 'bacola_shop_single_section',
			'default'     => 'horizontal',
			'priority'    => 10,
			'choices'     => array(
				'horizontal' => esc_attr__( 'Horizontal', 'bacola' ),
				'vertical' => esc_attr__( 'Vertical', 'bacola' ),
			),
			) 
		);
		
		/*====== Shop Single Social Share ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_shop_social_share',
				'label' => esc_attr__( 'Social Share (Product Detail)', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable social share buttons.', 'bacola' ),
				'section' => 'bacola_shop_single_section',
				'default' => '0',
			)
		);
		
		/*====== Shop Single Compare  ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_compare_button',
				'label' => esc_attr__( 'Compare', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of the compare button.', 'bacola' ),
				'section' => 'bacola_shop_single_section',
				'default' => '0',
			)
		);
		
		/*====== Shop Single Featured Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_shop_single_featured_toggle',
				'label' => esc_attr__( 'Featured List', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable the featured list.', 'bacola' ),
				'section' => 'bacola_shop_single_section',
				'default' => '0',
			)
		);
		
		/*====== Shop Banner Title ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_shop_single_featured_title',
				'label' => esc_attr__( 'Set Title', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a title.', 'bacola-core' ),
				'section' => 'bacola_shop_single_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_shop_single_featured_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Shop Single Featured List ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'repeater',
				'settings' => 'bacola_single_featured_list',
				'label' => esc_attr__( 'Featured List', 'bacola' ),
				'description' => esc_attr__( 'You can create the featured list.', 'bacola' ),
				'section' => 'bacola_shop_single_section',
				'row_label' => array (
					'type' => 'field',
					'field' => 'link_text',
				),
				'required' => array(
					array(
					  'setting'  => 'bacola_shop_single_featured_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
				'fields' => array(
					'featured_icon' => array(
						'type' => 'text',
						'label' => esc_attr__( 'Featured Icon', 'bacola' ),
						'description' => esc_attr__( 'Icon example; klbth-icon-dollar.', 'bacola' ),
					),
					'featured_text' => array(
						'type' => 'text',
						'label' => esc_attr__( 'Featured Content', 'bacola' ),
						'description' => esc_attr__( 'You can enter a text.', 'bacola' ),
					),
				),
			)
		);
		
		/*====== Shop Banner Image======*/
		bacola_customizer_add_field (
			array(
				'type' => 'image',
				'settings' => 'bacola_shop_banner_image',
				'label' => esc_attr__( 'Image', 'bacola-core' ),
				'description' => esc_attr__( 'You can upload an image.', 'bacola-core' ),
				'section' => 'bacola_shop_banner_section',
				'choices' => array(
					'save_as' => 'id',
				),
			)
		);
		
		/*====== Shop Banner Title ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_shop_banner_title',
				'label' => esc_attr__( 'Set Title', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a title.', 'bacola-core' ),
				'section' => 'bacola_shop_banner_section',
				'default' => '',
			)
		);
		
		/*====== Shop Banner Subtitle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_shop_banner_subtitle',
				'label' => esc_attr__( 'Set Subtitle', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a subtitle.', 'bacola-core' ),
				'section' => 'bacola_shop_banner_section',
				'default' => '',
			)
		);
		
		/*====== Shop Banner Desc ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_shop_banner_desc',
				'label' => esc_attr__( 'Description', 'bacola-core' ),
				'description' => esc_attr__( 'Add a description.', 'bacola-core' ),
				'section' => 'bacola_shop_banner_section',
				'default' => '',
			)
		);

		/*====== Shop Banner URL ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_shop_banner_button_url',
				'label' => esc_attr__( 'Set URL', 'bacola-core' ),
				'description' => esc_attr__( 'Set an url for the button', 'bacola-core' ),
				'section' => 'bacola_shop_banner_section',
				'default' => '#',
			)
		);
		

		/*====== Banner Repeater For each category ======*/
		add_action( 'init', function() {
			bacola_customizer_add_field (
				array(
					'type' => 'repeater',
					'settings' => 'bacola_shop_banner_each_category',
					'label' => esc_attr__( 'Banner For Categories', 'bacola-core' ),
					'description' => esc_attr__( 'You can set banner for each category.', 'bacola-core' ),
					'section' => 'bacola_shop_banner_section',
					'fields' => array(
						
						'category_id' => array(
							'type'        => 'select',
							'label'       => esc_html__( 'Select Category', 'bacola-core' ),
							'description' => esc_html__( 'Set a category', 'bacola-core' ),
							'priority'    => 10,
							'choices'     => Kirki_Helper::get_terms( array('taxonomy' => 'product_cat') )
						),
						
						'category_image' =>  array(
							'type' => 'image',
							'label' => esc_attr__( 'Image', 'bacola-core' ),
							'description' => esc_attr__( 'You can upload an image.', 'bacola-core' ),
						),
						
						'category_title' => array(
							'type' => 'text',
							'label' => esc_attr__( 'Set Title', 'bacola-core' ),
							'description' => esc_attr__( 'You can set a title.', 'bacola-core' ),
						),
						
						'category_subtitle' => array(
							'type' => 'text',
							'label' => esc_attr__( 'Set Subtitle', 'bacola-core' ),
							'description' => esc_attr__( 'You can set a subtitle.', 'bacola-core' ),
						),
			
						'category_desc' => array(
							'type' => 'text',
							'label' => esc_attr__( 'Description', 'bacola-core' ),
							'description' => esc_attr__( 'Add a description.', 'bacola-core' ),
						),
						
						'category_button_url' => array(
							'type' => 'text',
							'label' => esc_attr__( 'Set URL', 'bacola-core' ),
							'description' => esc_attr__( 'Set an url for the button', 'bacola-core' ),
						),
					),
				)
			);
		} );
		

	/*====== Blog Settings =======================================================*/
		/*====== Layouts ======*/
		
		bacola_customizer_add_field (
			array(
				'type' => 'radio-buttonset',
				'settings' => 'bacola_blog_layout',
				'label' => esc_attr__( 'Layout', 'bacola' ),
				'description' => esc_attr__( 'You can choose a layout.', 'bacola' ),
				'section' => 'bacola_blog_settings_section',
				'default' => 'right-sidebar',
				'choices' => array(
					'left-sidebar' => esc_attr__( 'Left Sidebar', 'bacola' ),
					'full-width' => esc_attr__( 'Full Width', 'bacola' ),
					'right-sidebar' => esc_attr__( 'Right Sidebar', 'bacola' ),
				),
			)
		);
		
		/*====== Main color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#233a95',
				'settings' => 'bacola_main_color',
				'label' => esc_attr__( 'Main Color', 'bacola' ),
				'description' => esc_attr__( 'You can customize the main color.', 'bacola' ),
				'section' => 'bacola_main_color_section',
			)
		);

		/*====== Secondary color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#2bbef9',
				'settings' => 'bacola_second_color',
				'label' => esc_attr__( 'Second Color', 'bacola' ),
				'description' => esc_attr__( 'You can customize the secondary color.', 'bacola' ),
				'section' => 'bacola_main_color_section',
			)
		);

		/*====== Map Settings ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_mapapi',
				'label' => esc_attr__( 'Google Map Api key', 'bacola' ),
				'description' => esc_attr__( 'Add your google map api key', 'bacola' ),
				'section' => 'bacola_map_settings_section',
				'default' => '',
			)
		);
		
	/*====== Bacola Widgets ======*/
		/*====== Widgets Panels ======*/
		Kirki::add_panel (
			'bacola_widgets_panel',
			array(
				'title' => esc_html__( 'Bacola Widgets', 'bacola' ),
				'description' => esc_html__( 'You can customize the bacola widgets.', 'bacola' ),
			)
		);

		$sections = array (
			
			'social_list' => array(
				esc_attr__( 'Social List', 'bacola-core' ),
				esc_attr__( 'You can customize the social list widget.', 'bacola-core' )
			),
		);

		foreach ( $sections as $section_id => $section ) {
			$section_args = array(
				'title' => $section[0],
				'description' => $section[1],
				'panel' => 'bacola_widgets_panel',
			);

			if ( isset( $section[2] ) ) {
				$section_args['type'] = $section[2];
			}

			Kirki::add_section( 'bacola_' . str_replace( '-', '_', $section_id ) . '_section', $section_args );
		}

		/*====== Social List Widget ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'repeater',
				'settings' => 'bacola_social_list_widget',
				'label' => esc_attr__( 'Social List Widget', 'bacola-core' ),
				'description' => esc_attr__( 'You can set social icons.', 'bacola-core' ),
				'section' => 'bacola_social_list_section',
				'fields' => array(
					'social_icon' => array(
						'type' => 'text',
						'label' => esc_attr__( 'Icon', 'bacola-core' ),
						'description' => esc_attr__( 'You can set an icon. for example; "facebook"', 'bacola-core' ),
					),

					'social_url' => array(
						'type' => 'text',
						'label' => esc_attr__( 'URL', 'bacola-core' ),
						'description' => esc_attr__( 'You can set url for the item.', 'bacola-core' ),
					),

				),
			)
		);
		
	/*====== Footer ======*/
		/*====== Footer Panels ======*/
		Kirki::add_panel (
			'bacola_footer_panel',
			array(
				'title' => esc_html__( 'Footer Settings', 'bacola' ),
				'description' => esc_html__( 'You can customize the footer from this panel.', 'bacola' ),
			)
		);

		$sections = array (
			'footer_subscribe' => array(
				esc_attr__( 'Subscribe', 'bacola' ),
				esc_attr__( 'You can customize the subscribe area.', 'bacola' )
			),
			
			'footer_featured_box' => array(
				esc_attr__( 'Featured Box', 'bacola' ),
				esc_attr__( 'You can customize the featured box section.', 'bacola' )
			),
			
			'footer_contact' => array(
				esc_attr__( 'Contact Details', 'bacola' ),
				esc_attr__( 'You can customize the contact details section.', 'bacola' )
			),
			
			'footer_general' => array(
				esc_attr__( 'Footer General', 'bacola' ),
				esc_attr__( 'You can customize the footer settings.', 'bacola-core' )
			),
			
			'footer_style' => array(
				esc_attr__( 'Footer Style', 'bacola' ),
				esc_attr__( 'You can customize the footer settings.', 'bacola-core' )
			),
			
		);

		foreach ( $sections as $section_id => $section ) {
			$section_args = array(
				'title' => $section[0],
				'description' => $section[1],
				'panel' => 'bacola_footer_panel',
			);

			if ( isset( $section[2] ) ) {
				$section_args['type'] = $section[2];
			}

			Kirki::add_section( 'bacola_' . str_replace( '-', '_', $section_id ) . '_section', $section_args );
		}

		
		/*====== Subcribe Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_footer_subscribe_area',
				'label' => esc_attr__( 'Subcribe', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable subscribe section.', 'bacola' ),
				'section' => 'bacola_footer_subscribe_section',
				'default' => '0',
			)
		);
		
		/*====== Subcribe FORM ID======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_footer_subscribe_formid',
				'label' => esc_attr__( 'Subscribe Form Id.', 'bacola' ),
				'description' => esc_attr__( 'You can find the form id in Dashboard > Mailchimp For Wp > Form.', 'bacola' ),
				'section' => 'bacola_footer_subscribe_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_subscribe_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Subscribe Title ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_footer_subscribe_title',
				'label' => esc_attr__( 'Title', 'bacola' ),
				'description' => esc_attr__( 'You can set text for subscribe section.', 'bacola' ),
				'section' => 'bacola_footer_subscribe_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_subscribe_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Subscribe Subtitle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_footer_subscribe_subtitle',
				'label' => esc_attr__( 'Subtitle', 'bacola' ),
				'description' => esc_attr__( 'You can set text for subscribe section.', 'bacola' ),
				'section' => 'bacola_footer_subscribe_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_subscribe_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Subscribe Desc ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_footer_subscribe_desc',
				'label' => esc_attr__( 'Description', 'bacola' ),
				'description' => esc_attr__( 'You can set text for subscribe section.', 'bacola' ),
				'section' => 'bacola_footer_subscribe_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_subscribe_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Subscribe Image ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'image',
				'settings' => 'bacola_footer_subscribe_image',
				'label' => esc_attr__( 'Image', 'bacola' ),
				'description' => esc_attr__( 'You can upload an image.', 'bacola' ),
				'section' => 'bacola_footer_subscribe_section',
				'choices' => array(
					'save_as' => 'id',
				),
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_subscribe_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Subscribe Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_subscribe_size',
				'label'       => esc_attr__( 'Subscribe Typography', 'bacola' ),
				'section'     => 'bacola_footer_subscribe_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.site-footer .footer-subscribe .subscribe-content , .site-footer .footer-subscribe .entry-subtitle , .site-footer .footer-subscribe .entry-title , .site-footer .footer-subscribe .entry-teaser p',
					],
				],
			)
		);
		
		/*====== Subscribe Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#233a95',
				'settings' => 'bacola_subscribe_bg',
				'label' => esc_attr__( 'Subscribe Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_footer_subscribe_section',
			)
		);
		
		/*====== Subscribe  Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_subscribe_color',
				'label' => esc_attr__( 'Subscribe  Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_subscribe_section',
			)
		);
		
		/*====== Subscribe Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_subscribe_hvrcolor',
				'label' => esc_attr__( 'Subscribe Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_subscribe_section',
			)
		);
		
		/*====== Featured Box ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'repeater',
				'settings' => 'bacola_footer_featured_box',
				'label' => esc_attr__( 'Featured Box', 'bacola-core' ),
				'description' => esc_attr__( 'You can create featured box.', 'bacola-core' ),
				'section' => 'bacola_footer_featured_box_section',
				'fields' => array(
					'featured_text' => array(
						'type' => 'textarea',
						'label' => esc_attr__( 'Featured Content', 'bacola-core' ),
						'description' => esc_attr__( 'You can enter a text.', 'bacola-core' ),
					),
					
					'featured_icon' => array(
						'type' => 'text',
						'label' => esc_attr__( 'Featured Icon', 'bacola-core' ),
						'description' => esc_attr__( 'set an icon.', 'bacola-core' ),
					),
				),
			)
		);
		
		/*====== Contact Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_footer_contact_area',
				'label' => esc_attr__( 'Contact Section', 'bacola' ),
				'description' => esc_attr__( 'Disable or Enable the contact section.', 'bacola' ),
				'section' => 'bacola_footer_contact_section',
				'default' => '0',
			)
		);
		
		/*====== Contact Phone Icon======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_footer_phone_icon',
				'label' => esc_attr__( 'Phone Icon', 'bacola' ),
				'description' => esc_attr__( 'You can set an icon.', 'bacola' ),
				'section' => 'bacola_footer_contact_section',
				'default' => 'klbth-icon-phone-call',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_contact_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Contact Phone Title======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_footer_phone_title',
				'label' => esc_attr__( 'Phone Title', 'bacola' ),
				'description' => esc_attr__( 'You can set a title.', 'bacola' ),
				'section' => 'bacola_footer_contact_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_contact_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Contact Phone Subtitle======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_footer_phone_subtitle',
				'label' => esc_attr__( 'Phone Subtitle', 'bacola' ),
				'description' => esc_attr__( 'You can set a subtitle.', 'bacola' ),
				'section' => 'bacola_footer_contact_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_contact_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Contact APP Title======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_footer_app_title',
				'label' => esc_attr__( 'APP Title', 'bacola' ),
				'description' => esc_attr__( 'You can set a title.', 'bacola' ),
				'section' => 'bacola_footer_contact_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_contact_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Contact APP Subtitle======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_footer_app_subtitle',
				'label' => esc_attr__( 'APP Subtitle', 'bacola' ),
				'description' => esc_attr__( 'You can set a subtitle.', 'bacola' ),
				'section' => 'bacola_footer_contact_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_contact_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Contact APP Image ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'repeater',
				'settings' => 'bacola_footer_app_image',
				'label' => esc_attr__( 'APP IMAGE', 'bacola-core' ),
				'description' => esc_attr__( 'You can set the app images.', 'bacola-core' ),
				'section' => 'bacola_footer_contact_section',
				'fields' => array(
					'app_image' => array(
						'type' => 'image',
						'label' => esc_attr__( 'Image', 'bacola-core' ),
						'description' => esc_attr__( 'You can upload an image.', 'bacola-core' ),
					),
					
					'app_url' => array(
						'type' => 'text',
						'label' => esc_attr__( 'URL', 'bacola-core' ),
						'description' => esc_attr__( 'set an url for the image.', 'bacola-core' ),
					),
				),
				'required' => array(
					array(
					  'setting'  => 'bacola_footer_contact_area',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Contact Social List ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'repeater',
				'settings' => 'bacola_footer_social_list',
				'label' => esc_attr__( 'Social List', 'bacola-core' ),
				'description' => esc_attr__( 'You can set social icons.', 'bacola-core' ),
				'section' => 'bacola_footer_contact_section',
				'fields' => array(
					'social_icon' => array(
						'type' => 'text',
						'label' => esc_attr__( 'Icon', 'bacola-core' ),
						'description' => esc_attr__( 'You can set an icon. for example; "facebook"', 'bacola-core' ),
					),

					'social_url' => array(
						'type' => 'text',
						'label' => esc_attr__( 'URL', 'bacola-core' ),
						'description' => esc_attr__( 'You can set url for the item.', 'bacola-core' ),
					),

				),
			)
		);
		
		/*====== Copyright ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_copyright',
				'label' => esc_attr__( 'Copyright', 'bacola' ),
				'description' => esc_attr__( 'You can set a copyright text for the footer.', 'bacola' ),
				'section' => 'bacola_footer_general_section',
				'default' => '',
			)
		);
		
		/*====== Subscribe Image ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'image',
				'settings' => 'bacola_footer_payment_image',
				'label' => esc_attr__( 'Image', 'bacola' ),
				'description' => esc_attr__( 'You can upload an image.', 'bacola' ),
				'section' => 'bacola_footer_general_section',
				'choices' => array(
					'save_as' => 'id',
				),
			)
		);

		/*====== Footer Column ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'select',
				'settings' => 'bacola_footer_column',
				'label' => esc_attr__( 'Footer Column', 'bacola' ),
				'description' => esc_attr__( 'You can set footer column.', 'bacola' ),
				'section' => 'bacola_footer_general_section',
				'default' => '5columns',
				'choices' => array(
					'5columns' => esc_attr__( '5 Columns', 'bacola' ),
					'4columns' => esc_attr__( '4 Columns', 'bacola' ),
					'3columns' => esc_attr__( '3 Columns', 'bacola' ),
				),
			)
		);
		
		/*======Footer Menu Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_footer_menu',
				'label' => esc_attr__( 'Footer Menu', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of the footer menu on the footer.', 'bacola' ),
				'section' => 'bacola_footer_general_section',
				'default' => '0',
			)
		);
		
		
		/*====== Footer Featured Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_footer_featured_size',
				'label'       => esc_attr__( 'Footer Featured Typography', 'bacola' ),
				'section'     => 'bacola_footer_style_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
					
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.site-footer .footer-iconboxes .iconbox .iconbox-icon , .site-footer .footer-iconboxes .iconbox .iconbox-detail span ',
					],
				],
			)
		);
		
		/*====== Footer Featured Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#f7f8fd',
				'settings' => 'bacola_featured_bg_color',
				'label' => esc_attr__( 'Footer Featured Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);

		/*====== Footer Featured Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#000000',
				'settings' => 'bacola_featured_color',
				'label' => esc_attr__( 'Footer Featured Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Featured Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#000000',
				'settings' => 'bacola_featured_hvrcolor',
				'label' => esc_attr__( 'Footer Featured Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_footer_size',
				'label'       => esc_attr__( 'Footer Typography', 'bacola' ),
				'section'     => 'bacola_footer_style_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
					
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.site-footer .footer-widgets .widget , .klbfooterwidget h4.widget-title',
					],
				],
			)
		);
		
		/*====== Footer Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#f7f8fd',
				'settings' => 'bacola_footer_bg_color',
				'label' => esc_attr__( 'Footer Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  background.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);

		/*====== Footer Header Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_footer_header_color',
				'label' => esc_attr__( 'Footer Header Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Header Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_footer_header_hvrcolor',
				'label' => esc_attr__( 'Footer Header Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#71778e',
				'settings' => 'bacola_footer_color',
				'label' => esc_attr__( 'Footer Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#71778e',
				'settings' => 'bacola_footer_hvrcolor',
				'label' => esc_attr__( 'Footer Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Phone Icon Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_footer_phone_icon_bg',
				'label' => esc_attr__( 'Footer Phone Icon Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Phone Icon Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_footer_phone_icon_color',
				'label' => esc_attr__( 'Footer Phone Icon Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Contact Typography ======*/
		bacola_customizer_add_field (
			array(
				'type'        => 'typography',
				'settings' => 'bacola_footer_contact_size',
				'label'       => esc_attr__( 'Footer Contact Typography', 'bacola' ),
				'section'     => 'bacola_footer_style_section',
				'default'     => [
					'font-family'    => '',
					'variant'        => '',
					'font-size'      => '',
					'line-height'    => '',
					'letter-spacing' => '',
					'text-transform' => '',
					
				],
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => [
					[
						'element' => '.site-footer .footer-contacts .site-phone .entry-title, .site-footer .footer-contacts .site-phone span , .site-footer .footer-contacts .site-mobile-app .app-content .entry-title , .site-footer .footer-contacts .site-mobile-app .app-content span',
					],
				],
			)
		);
		
		/*====== Footer Contact Background ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_footer_contact_background',
				'label' => esc_attr__( 'Footer Contact Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for background.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Contact Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_footer_contact_phone_color',
				'label' => esc_attr__( 'Footer Contact Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Contact Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_footer_contact_phone_hvrcolor',
				'label' => esc_attr__( 'Footer Contact Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Contact Second Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_footer_contact_color',
				'label' => esc_attr__( 'Footer Contact Second Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Contact Second Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#202435',
				'settings' => 'bacola_footer_contact_hvrcolor',
				'label' => esc_attr__( 'Footer Contact Second Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Social Icon Background Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_footer_social_icon_bg',
				'label' => esc_attr__( 'Footer Social Icon Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer Social Icon Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#233a95',
				'settings' => 'bacola_footer_social_icon_color',
				'label' => esc_attr__( 'Footer Social Icon Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		
		/*====== Footer General Background ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#fff',
				'settings' => 'bacola_footer_general_background',
				'label' => esc_attr__( 'Footer General Background Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for background.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer General Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#9b9bb4',
				'settings' => 'bacola_footer_general_color',
				'label' => esc_attr__( 'Footer General Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a font color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);
		
		/*====== Footer General Hover Color ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'color',
				'default' => '#9b9bb4',
				'settings' => 'bacola_footer_general_hvrcolor',
				'label' => esc_attr__( 'Footer General Hover Color', 'bacola-core' ),
				'description' => esc_attr__( 'You can set a color for  color.', 'bacola-core' ),
				'section' => 'bacola_footer_style_section',
			)
		);

		/*====== GDPR Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_gdpr_toggle',
				'label' => esc_attr__( 'Enable GDPR', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of GDPR.', 'bacola' ),
				'section' => 'bacola_gdpr_settings_section',
				'default' => '0',
			)
		);
		
		
		/*====== GDPR Text ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_gdpr_text',
				'label' => esc_attr__( 'GDPR Text', 'bacola-core' ),
				'section' => 'bacola_gdpr_settings_section',
				'default' => 'In order to provide you a personalized shopping experience, our site uses cookies. <br><a href="#">cookie policy</a>.',
				'required' => array(
					array(
					  'setting'  => 'bacola_gdpr_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== GDPR Expire Date ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_gdpr_expire_date',
				'label' => esc_attr__( 'GDPR Expire Date', 'bacola-core' ),
				'section' => 'bacola_gdpr_settings_section',
				'default' => '15',
				'required' => array(
					array(
					  'setting'  => 'bacola_gdpr_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== GDPR Button Text ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_gdpr_button_text',
				'label' => esc_attr__( 'GDPR Button Text', 'bacola-core' ),
				'section' => 'bacola_gdpr_settings_section',
				'default' => 'Accept Cookies',
				'required' => array(
					array(
					  'setting'  => 'bacola_gdpr_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);

		/*====== Newsletter Toggle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'toggle',
				'settings' => 'bacola_newsletter_popup_toggle',
				'label' => esc_attr__( 'Enable Newsletter', 'bacola' ),
				'description' => esc_attr__( 'You can choose status of Newsletter Popup.', 'bacola' ),
				'section' => 'bacola_newsletter_settings_section',
				'default' => '0',
			)
		);
		
		
		/*====== Newsletter Title ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_newsletter_popup_title',
				'label' => esc_attr__( 'Newsletter Title', 'bacola-core' ),
				'section' => 'bacola_newsletter_settings_section',
				'default' => 'Subscribe To Newsletter',
				'required' => array(
					array(
					  'setting'  => 'bacola_newsletter_popup_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Newsletter Subtitle ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'textarea',
				'settings' => 'bacola_newsletter_popup_subtitle',
				'label' => esc_attr__( 'Newsletter Subtitle', 'bacola-core' ),
				'section' => 'bacola_newsletter_settings_section',
				'default' => 'Subscribe to the Bacola mailing list to receive updates on new arrivals, special offers and our promotions.',
				'required' => array(
					array(
					  'setting'  => 'bacola_newsletter_popup_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Subcribe Popup FORM ID======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_newsletter_popup_formid',
				'label' => esc_attr__( 'Newsletter Form Id.', 'bacola-core' ),
				'description' => esc_attr__( 'You can find the form id in Dashboard > Mailchimp For Wp > Form.', 'bacola-core' ),
				'section' => 'bacola_newsletter_settings_section',
				'default' => '',
				'required' => array(
					array(
					  'setting'  => 'bacola_newsletter_popup_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);
		
		/*====== Subcribe Popup Expire Date ======*/
		bacola_customizer_add_field (
			array(
				'type' => 'text',
				'settings' => 'bacola_newsletter_popup_expire_date',
				'label' => esc_attr__( 'Newsletter Expire Date', 'bacola-core' ),
				'section' => 'bacola_newsletter_settings_section',
				'default' => '15',
				'required' => array(
					array(
					  'setting'  => 'bacola_newsletter_popup_toggle',
					  'operator' => '==',
					  'value'    => '1',
					),
				),
			)
		);