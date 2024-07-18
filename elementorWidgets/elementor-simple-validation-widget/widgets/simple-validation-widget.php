<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Elementor_Simple_Validation_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'simple-validation-widget';
	}

	public function get_title() {
		return esc_html__( 'Simple validation', 'wp-ultra-simple-paypal-shopping-cart' );
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'shopping', 'validation', 'checkout', 'simple' ];
	}

	public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

	protected function get_upsale_data() {
		return [];
	}

	protected function register_controls() {}

	protected function render() {
		$print = validate_wpus_shopping_cart_handler();
		echo !empty($print) ? $print : "empty cart";
	}

	protected function content_template() {}

}