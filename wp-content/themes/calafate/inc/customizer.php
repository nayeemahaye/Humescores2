<?php
/**
 * Calafate Theme Customizer.
 *
 * @package Calafate
 */

function calafate_customize_register( $wp_customize ) {

	$google_fonts_lib = require_once get_template_directory() . '/inc/customizer-fonts.php';
	require_once get_template_directory() . '/inc/customizer-classes.php';

	// Sections

 	$wp_customize->add_section( 'logo', array(
		'title' => esc_html__( 'Logo', 'calafate' ),
		'priority' => 90
	));
	 $wp_customize->add_section( 'header', array(
		'title' => esc_html__( 'Header', 'calafate' ),
		'priority' => 99
	));
	$wp_customize->add_panel( 'cs_panel', array(
    'priority'       => 100,
    'title'          => esc_html__( 'Color scheme', 'calafate' ),
	));
	$wp_customize->add_section( 'cs_general', array(
    'priority'       => 1,
    'title'          => esc_html__( 'General', 'calafate' ),
    'panel'  => 'cs_panel',
  ));
	$wp_customize->add_section( 'cs_blog', array(
    'priority'       => 2,
    'title'          => esc_html__( 'Blog', 'calafate' ),
    'panel'  => 'cs_panel',
  ));
	$wp_customize->add_section( 'cs_shop', array(
    'priority'       => 3,
    'title'          => esc_html__( 'Shop', 'calafate' ),
    'panel'  => 'cs_panel',
  ));
	$wp_customize->add_section( 'cs_404', array(
    'priority'       => 4,
    'title'          => esc_html__( '404', 'calafate' ),
    'panel'  => 'cs_panel',
  ));
	 $wp_customize->add_section('type', array(
		'title' => esc_html__( 'Typography', 'calafate' ),
		'priority' => 101
	));
	 $wp_customize->add_section( 'portfolio', array(
		'title' => esc_html__( 'Portfolio', 'calafate' ),
		'priority' => 102
	));
	 $wp_customize->add_section( 'blog', array(
		'title' => esc_html__( 'Blog', 'calafate' ),
		'priority' => 103
	));
	 $wp_customize->add_section( 'shop', array(
		'title' => esc_html__( 'Shop', 'calafate' ),
		'priority' => 104
	));
	 $wp_customize->add_section( 'general', array(
		'title' => esc_html__( 'General', 'calafate' ),
		'priority' => 105
	));
	 $wp_customize->add_section( 'social', array(
		'title' => esc_html__( 'Social', 'calafate' ),
		'priority' => 106
	));

	// Shop settings

	$wp_customize->add_setting(
		'calafate_shop_style', array(
			'default' => 'Flexible',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_shop_style',
		array(
			'label' => esc_html__( 'Masonry grid type', 'calafate' ),
			'section' => 'shop',
			'settings' => 'calafate_shop_style',
			'type' => 'select',
			'choices' => array(
				'Flexible' => esc_html__( 'Flexible', 'calafate' ),
				'Fixed' => esc_html__( 'Fixed', 'calafate' ),
				'Regular' => esc_html__( 'Regular', 'calafate' )
			),
			'priority' => 1
		)
	);

	$wp_customize->add_setting(
		'calafate_shop_hover', array(
			'default' => 'one',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_shop_hover',
		array(
			'label' => esc_html__( 'Thumbnail\'s style', 'calafate' ),
			'section' => 'shop',
			'settings' => 'calafate_shop_hover',
			'type' => 'select',
			'choices' => array(
				'one' => esc_html__( 'Caption over image', 'calafate' ),
				'two' => esc_html__( 'Caption underneath', 'calafate' )
			),
			'priority' => 2
		)
	);

	$wp_customize->add_setting(
		'calafate_shop_aspect_ratio', array(
			'default' => '4:3',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_shop_aspect_ratio',
		array(
			'label' => esc_html__( 'Regular grid aspect ratio', 'calafate' ),
			'section' => 'shop',
			'settings' => 'calafate_shop_aspect_ratio',
			'type' => 'select',
			'choices' => array(
				'4:3' => esc_html__( '4:3', 'calafate' ),
				'16:9' => esc_html__( '16:9', 'calafate' ),
				'3:4' => esc_html__( '3:4', 'calafate' ),
				'1:1' => esc_html__( '1:1', 'calafate' )
			),
			'priority' => 3
		)
	);

	$wp_customize->add_setting(
		'calafate_shop_columns', array(
			'default' => '3',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_shop_columns',
		array(
			'label' => esc_html__( 'Number of columns', 'calafate' ),
			'section' => 'shop',
			'settings' => 'calafate_shop_columns',
			'type' => 'select',
			'choices' => array(
				'1' => esc_html__( '1', 'calafate' ),
				'2' => esc_html__( '2', 'calafate' ),
				'3' => esc_html__( '3', 'calafate' ),
				'4' => esc_html__( '4', 'calafate' ),
				'5' => esc_html__( '5', 'calafate' ),
				'6' => esc_html__( '6', 'calafate' ),
			),
			'priority' => 4
		)
	);

	$wp_customize->add_setting(
		'calafate_shop_gap', array(
			'default' => '10',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_basic_number'
		)
	);
	$wp_customize->add_control('calafate_shop_gap',
		array(
			'label' => esc_html__( 'Columns gap', 'calafate' ),
			'section' => 'shop',
			'settings' => 'calafate_shop_gap',
			'type' => 'text',
			'priority' => 5
		)
	);

	$wp_customize->add_setting(
		'calafate_shop_page', array(
			'default' => '-1',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_shop_page',
		array(
			'label' => esc_html__( 'Pagination', 'calafate' ),
			'section' => 'shop',
			'settings' => 'calafate_shop_page',
			'type' => 'select',
			'choices' => array(
				'-1' => esc_html__( 'None - show all products', 'calafate' ),
				'6' => esc_html__( '6 products per page', 'calafate' ),
				'12' => esc_html__( '12 products per page', 'calafate' ),
				'18' => esc_html__( '18 products per page', 'calafate' ),
				'24' => esc_html__( '24 products per page', 'calafate' ),
				'30' => esc_html__( '30 products per page', 'calafate' )
			),
			'priority' => 6
		)
	);

	// Logo settings

	$wp_customize->add_setting(
		'calafate_image_logo', array(
			'default' => 'disabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_image_logo',
		array(
			'label' => esc_html__( 'Site image logo', 'calafate' ),
			'section' => 'logo',
			'settings' => 'calafate_image_logo',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			),
			'priority' => 1
		)
	);

	$wp_customize->add_setting(
		'calafate_logo', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	$wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'calafate_logo',
      array(
        'label' => esc_html__( 'Logo image', 'calafate' ),
        'section' => 'logo',
        'settings' => 'calafate_logo',
				'priority' => 2
      )
    )
	);

	$wp_customize->add_setting(
		'calafate_logo_blog', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	$wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'calafate_logo_blog',
      array(
        'label' => esc_html__( 'Blog pages logo image', 'calafate' ),
        'section' => 'logo',
        'settings' => 'calafate_logo_blog',
				'priority' => 3
      )
    )
	);

	$wp_customize->add_setting(
		'calafate_logo_woocommerce', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	$wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'calafate_logo_woocommerce',
      array(
        'label' => esc_html__( 'WooCommerce pages logo image', 'calafate' ),
        'section' => 'logo',
        'settings' => 'calafate_logo_woocommerce',
				'priority' => 4
      )
    )
	);

	$wp_customize->add_setting(
		'calafate_logo_height', array(
			'default' => '70',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_basic_number'
		)
	);
	$wp_customize->add_control('calafate_logo_height', 
		array(
			'label' => esc_html__( 'Image logo height', 'calafate' ),
			'section' => 'logo',
			'settings' => 'calafate_logo_height',
			'type' => 'text',
			'priority' => 5
		)
	);

	// Color settings

	$wp_customize->add_setting(
		'calafate_bg_color', array(
			'default' => '#000',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_bg_color', 
		array(
			'label'      => esc_html__( 'Background', 'calafate' ),
			'section'    => 'cs_general',
			'settings'   => 'calafate_bg_color'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_txt_color', array(
			'default' => '#fff',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_txt_color', 
		array(
			'label'      => esc_html__( 'Text', 'calafate' ),
			'section'    => 'cs_general',
			'settings'   => 'calafate_txt_color'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_acc_color', array(
			'default' => '#0FFFBE',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_acc_color', 
		array(
			'label'      => esc_html__( 'Accent', 'calafate' ),
			'section'    => 'cs_general',
			'settings'   => 'calafate_acc_color'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_acc_fgd_color', array(
			'default' => '#111',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_acc_fgd_color', 
		array(
			'label'      => esc_html__( 'Accent foreground', 'calafate' ),
			'section'    => 'cs_general',
			'settings'   => 'calafate_acc_fgd_color'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_blog_bg', array(
			'default' => '#000',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_blog_bg', 
		array(
			'label'      => esc_html__( 'Blog background', 'calafate' ),
			'section'    => 'cs_blog',
			'settings'   => 'calafate_blog_bg'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_blog_txt', array(
			'default' => '#fff',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_blog_txt', 
		array(
			'label'      => esc_html__( 'Blog text', 'calafate' ),
			'section'    => 'cs_blog',
			'settings'   => 'calafate_blog_txt'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_comments_bg', array(
			'default' => '#fff',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_comments_bg', 
		array(
			'label'      => esc_html__( 'Comments section background', 'calafate' ),
			'section'    => 'cs_blog',
			'settings'   => 'calafate_comments_bg'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_comments_txt', array(
			'default' => '#000',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_comments_txt', 
		array(
			'label'      => esc_html__( 'Comments section text', 'calafate' ),
			'section'    => 'cs_blog',
			'settings'   => 'calafate_comments_txt'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_sidebar_bg', array(
			'default' => '#191919',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_sidebar_bg', 
		array(
			'label'      => esc_html__( 'Sidebar background', 'calafate' ),
			'section'    => 'cs_blog',
			'settings'   => 'calafate_sidebar_bg'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_sidebar_txt', array(
			'default' => '#fff',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_sidebar_txt', 
		array(
			'label'      => esc_html__( 'Sidebar text', 'calafate' ),
			'section'    => 'cs_blog',
			'settings'   => 'calafate_sidebar_txt'
		) ) 
	);


	$wp_customize->add_setting(
		'calafate_shop_bg', array(
			'default' => '#000',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_shop_bg', 
		array(
			'label'      => esc_html__( 'Shop pages background', 'calafate' ),
			'section'    => 'cs_shop',
			'settings'   => 'calafate_shop_bg'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_shop_txt', array(
			'default' => '#fff',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_shop_txt', 
		array(
			'label'      => esc_html__( 'Shop pages text', 'calafate' ),
			'section'    => 'cs_shop',
			'settings'   => 'calafate_shop_txt'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_shop_grid_bg', array(
			'default' => '#fff',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_shop_grid_bg', 
		array(
			'label'      => esc_html__( 'Shop products caption background', 'calafate' ),
			'section'    => 'cs_shop',
			'settings'   => 'calafate_shop_grid_bg'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_shop_grid_txt', array(
			'default' => '#000',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_shop_grid_txt', 
		array(
			'label'      => esc_html__( 'Shop products caption text', 'calafate' ),
			'section'    => 'cs_shop',
			'settings'   => 'calafate_shop_grid_txt'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_shop_cart_bg', array(
			'default' => '#fff',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_shop_cart_bg', 
		array(
			'label'      => esc_html__( 'Cart sidebar background', 'calafate' ),
			'section'    => 'cs_shop',
			'settings'   => 'calafate_shop_cart_bg'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_shop_cart_txt', array(
			'default' => '#000',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'calafate_shop_cart_txt', 
		array(
			'label'      => esc_html__( 'Cart sidebar text', 'calafate' ),
			'section'    => 'cs_shop',
			'settings'   => 'calafate_shop_cart_txt'
		) ) 
	);

	$wp_customize->add_setting(
		'calafate_404_bg', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	$wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'calafate_404_bg',
      array(
        'label' => esc_html__( '404 page image background', 'calafate' ),
        'section' => 'cs_404',
        'settings' => 'calafate_404_bg'
      )
    )
	);

	// Typography settings

	$wp_customize->add_setting(
		'calafate_type_menu', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_font'
		)
	);
	$wp_customize->add_control('calafate_type_menu', 
		array(
			'label' => esc_html__( 'Menus / Logo', 'calafate' ),
			'section' => 'type',
			'settings' => 'calafate_type_menu',
			'type' => 'select',
			'choices' => $google_fonts_lib
		)
	);

	$wp_customize->add_setting(
		'calafate_type_heading_main', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_font'
		)
	);
	$wp_customize->add_control('calafate_type_heading_main', 
		array(
			'label' => esc_html__( 'Main Headings', 'calafate' ),
			'section' => 'type',
			'settings' => 'calafate_type_heading_main',
			'type' => 'select',
			'choices' => $google_fonts_lib
		)
	);

	$wp_customize->add_setting(
		'calafate_type_heading', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_font'
		)
	);
	$wp_customize->add_control('calafate_type_heading', 
		array(
			'label' => esc_html__( 'Other Headings', 'calafate' ),
			'section' => 'type',
			'settings' => 'calafate_type_heading',
			'type' => 'select',
			'choices' => $google_fonts_lib
		)
	);

	$wp_customize->add_setting(
		'calafate_type_body', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_font'
		)
	);
	$wp_customize->add_control('calafate_type_body', 
		array(
			'label' => esc_html__( 'Body', 'calafate' ),
			'section' => 'type',
			'settings' => 'calafate_type_body',
			'type' => 'select',
			'choices' => $google_fonts_lib
		)
	);

	$wp_customize->add_setting(
		'calafate_type_quote', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_font'
		)
	);
	$wp_customize->add_control('calafate_type_quote', 
		array(
			'label' => esc_html__( 'Captions', 'calafate' ),
			'section' => 'type',
			'settings' => 'calafate_type_quote',
			'type' => 'select',
			'choices' => $google_fonts_lib
		)
	);

	// Portfolio settings

	$wp_customize->add_setting(
		'calafate_portfolio_page', array(
			'default' => 'enabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_sanitize_font'
		)
	);
	$wp_customize->add_control('calafate_portfolio_page', 
		array(
			'label' => 'Select default portfolio page',
			'section' => 'portfolio',
			'settings' => 'calafate_portfolio_page',
			'type' => 'dropdown-pages',
			'priority' => 1
		)
	);

	/*
	$wp_customize->add_setting(
		'calafate_portfolio_comments', array(
			'default' => 'disabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_portfolio_comments',
		array(
			'label' => esc_html__( 'Portfolio comments', 'calafate' ),
			'section' => 'portfolio',
			'settings' => 'calafate_portfolio_comments',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			),
			'priority' => 2
		)
	);
	*/
	
	// General settings

	$wp_customize->add_setting(
		'calafate_site_sticky', array(
			'default' => 'enabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_site_sticky',
		array(
			'label' => esc_html__( 'Sticky header', 'calafate' ),
			'section' => 'header',
			'settings' => 'calafate_site_sticky',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'On scoll up', 'calafate' ),
				'always' => esc_html__( 'Always visible', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			)
		)
	);
	
	$wp_customize->add_setting(
		'calafate_site_actions', array(
			'default' => 'enabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_site_actions',
		array(
			'label' => esc_html__( 'Page actions', 'calafate' ),
			'section' => 'general',
			'settings' => 'calafate_site_actions',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			)
		)
	);

	$wp_customize->add_setting(
		'calafate_site_sharing', array(
			'default' => 'enabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_site_sharing',
		array(
			'label' => esc_html__( 'Design', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_site_sharing',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Show text', 'calafate' ),
				'enabled-icons' => esc_html__( 'Show icons', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			)
		)
	);

	$wp_customize->add_setting(
		'calafate_site_social', array(
			'default' => 'sharing',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_site_social',
		array(
			'label' => esc_html__( 'Type', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_site_social',
			'type' => 'select',
			'choices' => array(
				'sharing' => esc_html__( 'Sharing links', 'calafate' ),
				'network' => esc_html__( 'Links to social profile websites', 'calafate' )
			)
		)
	);

	$wp_customize->add_setting(
		'calafate_site_menu', array(
			'default' => 'disabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_site_menu',
		array(
			'label' => esc_html__( 'Always display hamburger menu', 'calafate' ),
			'section' => 'header',
			'settings' => 'calafate_site_menu',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			)
		)
	);

	$wp_customize->add_setting(
		'calafate_social_500px', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_500px', 
		array(
			'label' => esc_html__( '500px link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_500px',
			'type' => 'text'
		)
	);

	$wp_customize->add_setting(
		'calafate_social_dribbble', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_dribbble', 
		array(
			'label' => esc_html__( 'Dribbble link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_dribbble',
			'type' => 'text'
		)
	);

	$wp_customize->add_setting(
		'calafate_social_facebook', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_facebook', 
		array(
			'label' => esc_html__( 'Facebook link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_facebook',
			'type' => 'text'
		)
	);



	$wp_customize->add_setting(
		'calafate_social_instagram', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_instagram', 
		array(
			'label' => esc_html__( 'Instagram link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_instagram',
			'type' => 'text'
		)
	);

	$wp_customize->add_setting(
		'calafate_social_linkedin', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_linkedin', 
		array(
			'label' => esc_html__( 'LinedIn link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_linkedin',
			'type' => 'text'
		)
	);

	$wp_customize->add_setting(
		'calafate_social_pinterest', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_pinterest', 
		array(
			'label' => esc_html__( 'Pinterest link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_pinterest',
			'type' => 'text'
		)
	);



	$wp_customize->add_setting(
		'calafate_social_soundcloud', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_soundcloud', 
		array(
			'label' => esc_html__( 'Soundcloud link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_soundcloud',
			'type' => 'text'
		)
	);



	$wp_customize->add_setting(
		'calafate_social_twitter', array(
			'default' => '',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_social'
		)
	);
	$wp_customize->add_control('calafate_social_twitter', 
		array(
			'label' => esc_html__( 'Twitter link', 'calafate' ),
			'section' => 'social',
			'settings' => 'calafate_social_twitter',
			'type' => 'text'
		)
	);

	$wp_customize->add_setting(
		'calafate_site_mobile_search', array(
			'default' => 'disabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_site_mobile_search',
		array(
			'label' => esc_html__( 'Mobile search icon', 'calafate' ),
			'section' => 'header',
			'settings' => 'calafate_site_mobile_search',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			)
		)
	);


	$wp_customize->add_setting(
		'calafate_site_sidebar', array(
			'default' => 'enabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_site_sidebar',
		array(
			'label' => esc_html__( 'Widgetised sidebar', 'calafate' ),
			'section' => 'blog',
			'settings' => 'calafate_site_sidebar',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			),
      'priority' => 1
		)
	);

	$wp_customize->add_setting(
		'calafate_blog_style', array(
			'default' => 'minimal',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_blog_style',
		array(
			'label' => esc_html__( 'Blog style', 'calafate' ),
			'section' => 'blog',
			'settings' => 'calafate_blog_style',
			'type' => 'select',
			'choices' => array(
				'minimal' => esc_html__( 'Minimal journal', 'calafate' ),
				'grid' => esc_html__( 'Creative grid', 'calafate' )
			),
      'priority' => 2
		)
	);


	$wp_customize->add_setting(
		'calafate_blog_cols', array(
			'default' => '3',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_blog_cols',
		array(
			'label' => esc_html__( 'Creative grid columns', 'calafate' ),
			'section' => 'blog',
			'settings' => 'calafate_blog_cols',
			'type' => 'select',
			'choices' => array(
				'3' => esc_html__( '3', 'calafate' ),
				'2' => esc_html__( '2', 'calafate' )
			),
      'priority' => 3
		)
	);

	$wp_customize->add_setting(
		'calafate_post_style', array(
			'default' => 'post-half',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_shop_select'
		)
	);
	$wp_customize->add_control('calafate_post_style',
		array(
			'label' => esc_html__( 'Single post style', 'calafate' ),
			'section' => 'blog',
			'settings' => 'calafate_post_style',
			'type' => 'select',
			'choices' => array(
				'post-half' => esc_html__( 'Half', 'calafate' ),
				'post-full' => esc_html__( 'Full', 'calafate' ),
				'post-full w-hero' => esc_html__( 'Complex (hero header available)', 'calafate' )
			),
      'priority' => 5
		)
	);

	$wp_customize->add_setting( 'calafate_blog_carousel', array(
      'default' => 'none',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_blog_carousel'
  ) );
  $wp_customize->add_control( new WP_Customize_Post_Select_Multiple( $wp_customize, 'calafate_blog_carousel', array(
      'label'   => esc_html__( 'Creative grid carousel posts', 'calafate' ),
      'description' => esc_html__( 'You should select at least three posts by pressing the Ctrl (Cmd) key.', 'calafate' ),
      'section' => 'blog',
      'settings'   => 'calafate_blog_carousel',
      'priority' => 4
  ) ) );


	$wp_customize->add_setting(
		'calafate_site_ajax', array(
			'default' => 'enabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_site_ajax',
		array(
			'label' => esc_html__( 'AJAX powered', 'calafate' ),
			'section' => 'general',
			'settings' => 'calafate_site_ajax',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			)
		)
	);


	$wp_customize->add_setting(
		'calafate_site_lazy', array(
			'default' => 'disabled',
			'type' => 'theme_mod',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'calafate_validate_select'
		)
	);
	$wp_customize->add_control('calafate_site_lazy',
		array(
			'label' => esc_html__( 'Lazy loading', 'calafate' ),
      'description' => esc_html__( 'This is an experimental feature. Please test and report any bugs that you may encounter.', 'calafate' ),
			'section' => 'general',
			'settings' => 'calafate_site_lazy',
			'type' => 'select',
			'choices' => array(
				'enabled' => esc_html__( 'Enabled', 'calafate' ),
				'disabled' => esc_html__( 'Disabled', 'calafate' )
			)
		)
	);

}

add_action( 'customize_register', 'calafate_customize_register' );

// Sanitize function

function calafate_validate_select( $select_box ) {
	if ( in_array( $select_box, array( 'enabled', 'disabled', 'always' ), true ) ) {
		return $select_box;
	}
}

function calafate_validate_shop_select( $input, $setting ) {
	return $input;
}

function calafate_sanitize_font( $font ) {
	return $font;
}

function calafate_sanitize_basic_number( $number ) {
	return absint( $number );
}

function calafate_validate_blog_carousel( $input ) {
	return $input;
}

function calafate_validate_social( $input ) {
	return esc_html( $input );
}