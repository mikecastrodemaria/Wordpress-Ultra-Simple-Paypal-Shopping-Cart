<?php
/**
 * Description: Simple validation widget for Elementor.
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Text Domain: wp-ultra-simple-paypal-shopping-cart
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.21.0
 * Elementor Pro tested up to: 3.21.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


function register_simple_validation_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/simple-validation-widget.php' );

	$widgets_manager->register( new \Elementor_Simple_Validation_Widget() );

}
add_action( 'elementor/widgets/register', 'register_simple_validation_widget' );