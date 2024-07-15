<?php
/**
 * Description: Price variation add cart widget for Elementor.
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


function register_price_variation_add_cart_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/price-variation-add-cart-widget.php' );

	$widgets_manager->register( new \Elementor_Price_Variation_Add_Cart_Widget() );

}
add_action( 'elementor/widgets/register', 'register_price_variation_add_cart_widget' );