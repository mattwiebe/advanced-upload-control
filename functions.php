<?php

/**
 * Of course most of this should go in a plugin and/or
 * Core patch. Theme helpful for demo purposes. :)
 *
 * @author Matt Wiebe <wiebe@automattic.com>
 */

add_action( 'wp_enqueue_scripts', 'mw_au_enqueue' );
function mw_au_enqueue() {
	wp_register_style( 'twentytwelve', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'advanced-upload', get_stylesheet_uri(), array( 'twentytwelve' ) );
}

// Loads our advanced uploader "plugin" and control class
include 'inc/advanced-uploader.php';


/**
 * Prints a logo on the frontend based on which functionality is active
 * @return void
 */
function mw_render_logo() {
	$url = false;
	$logo = get_theme_mod( 'mw_logo_setting', array( 'logo' => array( 'url' => false ) ) );
	if ( $logo['logo']['url'] )
		$url = $logo['logo']['url'];

	if ( $url )
		printf( '<img id="mw-logo" src="%s" alt="" />', esc_url( $url ) );
}