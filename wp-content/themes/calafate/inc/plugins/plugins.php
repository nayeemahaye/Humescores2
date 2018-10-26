<?php

require_once get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'calafate_register_required_plugins' );

function calafate_register_required_plugins() {

	$plugins = array(

		array(
			'name'               => esc_html__( 'Advanced Custom Fields Pro', 'calafate' ),
			'slug'               => 'advanced-custom-fields-pro', 
			'source'             => get_template_directory() . '/inc/plugins/advanced-custom-fields-pro.zip', 
			'required'           => true, 
			'version'            => '5.6.9', 
			'force_activation'   => true, 
			'force_deactivation' => false, 
			'external_url'       => '', 
			'is_callable'        => ''
		),

		array(
			'name'               => esc_html__( 'Calafate Portfolio', 'calafate' ),
			'slug'               => 'calafate-portfolio', 
			'source'             => get_template_directory() . '/inc/plugins/calafate-portfolio.zip', 
			'required'           => true, 
			'version'            => '', 
			'force_activation'   => true, 
			'force_deactivation' => false, 
			'external_url'       => '', 
			'is_callable'        => ''
		),

		array(
			'name'               => esc_html__( 'Calafate Shortcodes', 'calafate' ),
			'slug'               => 'calafate-shortcodes', 
			'source'             => get_template_directory() . '/inc/plugins/calafate-shortcodes.zip', 
			'required'           => true, 
			'version'            => '0.3', 
			'force_activation'   => true, 
			'force_deactivation' => false, 
			'external_url'       => '', 
			'is_callable'        => ''
		),

		array(
			'name'               => esc_html__( 'Envato Market', 'calafate' ),
			'slug'               => 'envato-market', 
			'source'             => get_template_directory() . '/inc/plugins/envato-market.zip', 
			'required'           => false, 
			'version'            => '', 
			'force_activation'   => false, 
			'force_deactivation' => false, 
			'external_url'       => '', 
			'is_callable'        => ''
		),

		array(
			'name'        => esc_html__( 'WordPress SEO by Yoast', 'calafate' ),
			'slug'        => 'wordpress-seo',
			'is_callable' => 'wpseo_init',
		),

		array(
			'name'        => esc_html__( 'W3 Total Cache', 'calafate' ),
			'slug'        => 'w3-total-cache',
			'is_callable' => 'w3_w3tc_release_version',
		),

		array(
			'name'        => esc_html__( 'Contact Form 7', 'calafate' ),
			'slug'        => 'contact-form-7',
			'is_callable' => 'wpcf7_install',
		),


    array(
      'name'      => esc_html__( 'oAuth Twitter Feed for Developers', 'calafate' ),
      'slug'      => 'oauth-twitter-feed-for-developers',
			'is_callable' => 'getTweets',
    )

	);

	$config = array(
		'id'           => 'calafate',                 
		'default_path' => '',                      
		'menu'         => 'tgmpa-install-plugins', 
		'has_notices'  => true,                  
		'dismissable'  => true,                    
		'dismiss_msg'  => '',                     
		'is_automatic' => false,                   
		'message'      => ''
	);

	tgmpa( $plugins, $config );
}
