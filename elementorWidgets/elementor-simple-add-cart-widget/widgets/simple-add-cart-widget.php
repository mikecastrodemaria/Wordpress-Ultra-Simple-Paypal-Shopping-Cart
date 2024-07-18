<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Elementor_Simple_Add_Cart_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'simple-add-cart-widget';
	}

	public function get_title() {
		return esc_html__( 'Simple add cart', 'wp-ultra-simple-paypal-shopping-cart' );
	}

	public function get_icon() {
		return 'eicon-product-add-to-cart';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'shopping', 'cart', 'checkout', 'simple', 'add' ];
	}

	public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

	protected function get_upsale_data() {
		return [];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'product',
			[
				'label' => esc_html__( 'Product', 'wp-ultra-simple-paypal-shopping-cart' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'product_name',
			[
				'label' => esc_html__( 'Product Name', 'wp-ultra-simple-paypal-shopping-cart' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your product name', 'wp-ultra-simple-paypal-shopping-cart' ),
			]
		);
		$this->add_control(
			'product_price',
			[
				'label' => esc_html__( 'Product Price', 'wp-ultra-simple-paypal-shopping-cart' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'placeholder' => esc_html__( 'Enter your product price', 'wp-ultra-simple-paypal-shopping-cart' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['product_name'] ) || empty( $settings['product_price'] ) ) {
			return;
		}
		$productName = sanitize_text_field($settings['product_name']);
		$productPrice = $settings['product_price'];

		$content = '[wp_cart:' . $productName . ':price:' . $productPrice . ':end]';
		echo print_wp_cart_action($content);
	}

	protected function content_template() {}

}