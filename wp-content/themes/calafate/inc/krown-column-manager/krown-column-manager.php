<?php
/*
Plugin Name: Krown Columns Manager
Plugin URI: http://krownthemes.com/kcm
Description: Dead simple columns manager for WordPress. Design by Van Garret.
Version: 1.0.0
Author: Ruben Bristian
Author URI: http://rubenbristian.com/
Copyright: Ruben Bristian
Text Domain: kcm
Domain Path: /lang
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('Krown_Columns_Manager') ) :

	class Krown_Columns_Manager {
		
		function __construct() {
			
			/* Do nothing here */
			
		}

		/**
		 * Init app
		 *
		 * @since 1.0.0
		*/

		function init() {
			
			// vars
			$this->settings = array(
				
				// basic
				'name'				=> esc_html__( 'Krown Columns Manager', 'calafate' ),
				'version'			=> '1.0.0',
							
				// urls
				'basename'			=> plugin_basename( __FILE__ ),
				'path'				=> plugin_dir_path( __FILE__ ),
				'dir'				=> plugin_dir_url( __FILE__ ),

			);

		 if ( ! defined( 'WPB_VC_VERSION' ) ) {
				add_action( 'current_screen', array( $this, 'add_app' ) );
   		}


		}

		/**
		 * Adds app to page
		 *
		 * @since 1.0.0
		*/

		function add_app() {

			$screen = get_current_screen();

			if ( is_admin() && $screen->base === 'post' && is_user_logged_in() && current_user_can( 'manage_options' ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_app' ) );
				add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
				add_action( 'edit_form_after_title',			array( $this, 'edit_form_after_title' ), 10, 0 );
			} 

		}

		/**
		 * Enqueues app script
		 *
		 * @since 1.0.0`
		*/

		function enqueue_app() {

			wp_enqueue_script( 'jquery-ui-sortable' );

			wp_enqueue_style( 'kcm-style',  get_template_directory_uri() . '/inc/krown-column-manager/css/app.css', array(), '1.0.0' );
			wp_enqueue_script( 'kcm-script', get_template_directory_uri() . '/inc/krown-column-manager/js/build/app.js', array( 'jquery' ), '1.0.0', true );

			wp_localize_script( 'kcm-script', 'KCMl10n', array(
				'popupConfirm' => esc_html__( 'Are you sure that you want to close this window and loose all changes?', 'calafate' ),
				'columnConfirm' => esc_html__( 'Are you sure that you want to remove this column?', 'calafate' ),
				'showKCM' => esc_html__( 'Show Columns Manager', 'calafate' ),
				'showWPE' => esc_html__( 'Show WordPress Editor', 'calafate' )
			) );

			wp_enqueue_script( 'flickity', get_template_directory_uri() . '/inc/krown-column-manager/vendor/flickity.pkgd.min.js' );
			wp_enqueue_style( 'flickity', get_template_directory_uri() . '/inc/krown-column-manager/vendor/flickity.css' );

		}

		/**
		 * Adds app switch button
		 *
		 * @since 1.0.0
		*/

		function edit_form_after_title() {
			echo '<button id="kcm-switch" class="kcm-button normal">' . esc_html__( 'Show Columns Manager', 'calafate' ) . '</button>';
		}

		/**
		 * Adds meta box (app holder)
		 *
		 * @since 1.0.0
		*/

		function add_meta_boxes() {
			$cpts_array = array( 'page', 'post', 'portfolio', 'product' );
			$cpts = apply_filters( 'calafate_kcm_cpts', $cpts_array );
			add_meta_box( 'krown-column-manager', 'Columns Manager', array( $this, 'add_app_meta_box_content' ), $cpts, 'normal', 'high' );
		}

		/**
		 * Meta box content
		 *
		 * @since 1.0.0
		*/

		function add_app_meta_box_content() {

			$output = '';

			// columns grid buttons & app

			$output .= '<button id="kcmp-add-column" class="kcmp-add-column kcm-button large">' . esc_html__( 'Add column', 'calafate' ) . '</button>';

			$output .= '<div id="krown-column-manager-app"></div>';

			$output .= '<button id="kcmp-add-column" class="kcmp-add-column kcm-button large">' . esc_html__( 'Add column', 'calafate' ) . '</button>';

			// popup content

			$output .= '<div id="kcmp" data-id="none"><div><div><div>';

				// top bar (header)

				$output .= '<div class="editor-header">';

					$output .= '<button id="kcmp-edit" class="editor-header--button"><span>✓</span>' . esc_html__( 'Save', 'calafate' ) . '</button>';
					$output .= '<button id="kcmp-close" class="editor-header--button"><span>✕</span>'. esc_html__( 'Cancel', 'calafate' ) . '</button>';
					$output .= '<input id="kcmp-custom-class" class="editor-header--input" type="text" placeholder="' . esc_html__( '.custom-css-class', 'calafate' ) .'" />';
					$output .= '<button id="kcmp-delete" class="editor-header--delete">' . esc_html__( 'Delete', 'calafate' ) . '</button>';

				$output .= '</div>';

				// column settings (width & responsive)

				$output .= '<div class="editor-settings">';

					// navigation icons

					$output .= '<div class="carousel-navigation">';
						$output .= '<div class="device-icon desktop selected" data-index="0">' . esc_html__( 'Desktop settings', 'calafate' ) . '</div>';
						$output .= '<div class="device-icon laptop" data-index="1">' . esc_html__( 'Laptop settings', 'calafate' ) . '</div>';
						$output .= '<div class="device-icon tablet" data-index="2">' . esc_html__( 'Tablet settings', 'calafate' ) . '</div>';
						$output .= '<div class="device-icon phone" data-index="3">' . esc_html__( 'Phone settings', 'calafate' ) . '</div>';
					$output .= '</div>';

					// desktop settings

					$output .= '<div class="carousel">';

						$output .= '<div class="carousel-cell editor-form">';

							$output .= '<div class="editor-form--row">';
								$output .= $this->add_width_selector( 'kcmp-width', esc_html__( 'Column Width', 'calafate' ), '', false );
								$output .= '<div class="editor-form--center"><input type="checkbox" id="kcmp-cc" /><label for="kcmp-cc">' . esc_html__( 'Center column', 'calafate' ) . '</label></div>';
							$output .= '</div>';

							$output .= '<div class="editor-form--row">';
								$output .= $this->add_padding_selector( 'kcmp-padding-top', esc_html__( 'Padding Top', 'calafate' ), 'top-' );
							$output .= '</div>';

							$output .= '<div class="editor-form--row">';
								$output .= $this->add_padding_selector( 'kcmp-padding-bottom', esc_html__( 'Padding Bottom', 'calafate' ), 'bottom-' );
							$output .= '</div>';

						$output .= '</div>';

						// laptop settings

						$output .= '<div class="carousel-cell editor-form">';
							$output .= '<div class="editor-form--row">';
								$output .= $this->add_width_selector( 'kcmp-width-po', esc_html__( 'Column Width', 'calafate' ), 'portable--', true );
							$output .= '</div>';
						$output .= '</div>';

						// tablet settings

						$output .= '<div class="carousel-cell editor-form">';
							$output .= '<div class="editor-form--row">';
								$output .= $this->add_width_selector( 'kcmp-width-la', esc_html__( 'Column Width', 'calafate' ), 'lap--', true );
							$output .= '</div>';
						$output .= '</div>';

						// phone settings

						$output .= '<div class="carousel-cell editor-form">';
							$output .= '<div class="editor-form--row">';
								$output .= $this->add_width_selector( 'kcmp-width-pa', esc_html__( 'Column Width', 'calafate' ), 'palm--', true );
							$output .= '</div>';
						$output .= '</div>';

					$output .= '</div>';

				$output .= '</div>';

				// column content (tinyMCE)

				$output .= '<div class="editor-content">';
					ob_start();
					wp_editor( '', 'kcm_mce' );
					$output .= ob_get_clean();
				$output .= '</div>';

			$output .= '</div></div></div></div>';

			echo $output;

		}

		/**
		 * Width select input 
		 *
		 * @since 1.0.0
		*/

		function add_width_selector( $id, $label, $prefix, $auto ) {
			return "<div id='{$id}'>
				<label>{$label}</label>
				<select>
					" . ( $auto ? "<option value='{$prefix}auto'>" . esc_html__( 'Auto', 'calafate' ) . "</option>" : "" ) . "
					<option value='{$prefix}one-twelfth'>" . esc_html__( '8.33 %', 'calafate' ) . "</option>
					<option value='{$prefix}two-twelfths'>" . esc_html__( '16.66 %', 'calafate' ) . "</option>
					<option value='{$prefix}three-twelfths'" . ( ! $auto ? ' selected' : '' ) . ">" . esc_html__( '25 %', 'calafate' ) . "</option>
					<option value='{$prefix}four-twelfths'>" . esc_html__( '33.33 %', 'calafate' ) . "</option>
					<option value='{$prefix}five-twelfths'>" . esc_html__( '41.66 %', 'calafate' ) . "</option>
					<option value='{$prefix}six-twelfths'>" . esc_html__( '50 %', 'calafate' ) . "</option>
					<option value='{$prefix}seven-twelfths'>" . esc_html__( '58.33 %', 'calafate' ) . "</option>
					<option value='{$prefix}eight-twelfths'>" . esc_html__( '66.66 %', 'calafate' ) . "</option>
					<option value='{$prefix}nine-twelfths'>" . esc_html__( '75 %', 'calafate' ) . "</option>
					<option value='{$prefix}ten-twelfths'>" . esc_html__( '83.33 %', 'calafate' ) . "</option>
					<option value='{$prefix}eleven-twelfth'>" . esc_html__( '91.66 %', 'calafate' ) . "</option>
					<option value='{$prefix}one-whole'>" . esc_html__( '100 %', 'calafate' ) . "</option>
					" . ( $auto ? "<option value='{$prefix}hide'>" . esc_html__( 'Hide', 'calafate' ) . "</option>" : "" ) . "
				</select>
			</div>";
		}

		/**
		 * Padding select input 
		 *
		 * @since 1.0.0
		*/

		function add_padding_selector( $id, $label, $prefix ) {
			return "<div id='{$id}'>
				<label>{$label}</label>
				<select>
					<option value='{$prefix}no-padding'>" . esc_html__( 'None', 'calafate' ) . "</option>
					<option value='{$prefix}small-padding'>" . esc_html__( 'Small', 'calafate' ) . "</option>
					<option value='{$prefix}medium-padding'>" . esc_html__( 'Medium', 'calafate' ) . "</option>
					<option value='{$prefix}large-padding'>" . esc_html__( 'Large', 'calafate' ) . "</option>
				</select>
			</div>";
		}

	}

	/**
	 * Begins execution of the plugin
	 *
	 * @since 1.0.0
	*/

	function run() {
		$plugin = new Krown_Columns_Manager();
		$plugin->init();
	}

	run();

endif;