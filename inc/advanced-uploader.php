<?php

class MW_Advanced_Uploader {
	public static function init( $wp_customize ) {
		// load the control itself
		require_once dirname( __FILE__ ) . '/advanced-upload-control.php';
		// register needed scripts
		self::register_scripts();
		// enqueue our frontend script for postMessage listening
		add_action( 'customize_preview_init', array( __CLASS__, 'enqueue' ) );

		$section_id = 'mw_theme_options';
		$setting_id = 'mw_logo_setting[logo]';

		/**
		 * A section in which controls can be rendered
		 * You can make your own or use core sections as apprporiate:
		 * title_tagline, colors, header_image, background_image, nav, static_front_page
		 */
		$wp_customize->add_section( $section_id, array(
			// Required. The displayed title. Use Title Case.
			'title' => __( 'Theme Options', 'mwau' ),
			// Optional. Priority of sections in the Customizer. Defaults to 10.
			'priority' => 1,
			// capability that will be checked using current_user_can() to determine
			// whether to display the section. Defaults to edit_theme_options
			// Mostly pointless - makes more sense to set capability checks on settings
			'capability' => 'edit_theme_options',
			// Shows in a <del>tooltip</del><ins>at the top of the section in 3.7. props obenland!</ins>
			'description' => __( 'Upload your logo and such', 'mwau' )
		) );

		$wp_customize->add_setting( $setting_id, array(
			// How data will be stored.
			// Use theme_mod or option unless you need something really custom.
			// Defaults to theme_mod, which will almost always be what you want
			'type' => 'theme_mod',
			// How does the Customizer display setting changes?
			// Use postMessage to write a custom JS handler that
			// updates the theme preview instantly. Defaults to 'refresh'.
			'transport' => 'postMessage',
			// What capability must a user have to modify this setting?
			// Defaults to `edit_theme_options`
			'capability' => 'edit_theme_options',
			// A default value. Defaults to an empty string ''.
			'default' => array(
				'url' => false,
				'id' => 0
			)
		) );

		$wp_customize->add_control( new MW_Advanced_Upload_Control( $wp_customize, 'mw-awesome-uploader', array(
			// the section in which the control should be rendered
			'section' => $section_id,
			// the setting that the control should render
			// strangely you use the plural, not the singular here
			'settings' => $setting_id,
			// numerical priority. lower = higher up in the section. Default 10.
			'priority' => 1,
			// a label for this control
			'label' => __( 'Awesome Header' )
		) ) );
	}

	public static function register_scripts() {
		wp_register_style( 'advanced-upload-control', get_stylesheet_directory_uri() . '/css/advanced-upload-control.css' );
		wp_register_script( 'advanced-upload-control', get_stylesheet_directory_uri() . '/js/advanced-upload-control.js', array( 'media-views', 'customize-controls', 'underscore' ), '', true );
		wp_register_script( 'advanced-upload-frontend', get_stylesheet_directory_uri() . '/js/advanced-logo.js', array( 'media-views' ), '', true );
	}

	public static function enqueue() {
		wp_enqueue_script( 'advanced-upload-frontend' );
	}
}

add_action( 'customize_register', array( 'MW_Advanced_Uploader', 'init' ), 20 );