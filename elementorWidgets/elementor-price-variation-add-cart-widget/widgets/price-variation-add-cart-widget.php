<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Elementor_Price_Variation_Add_Cart_Widget extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'price-variation-add-cart-widget';
	}

	public function get_title()
	{
		return esc_html__('Price variation add cart', 'wp-ultra-simple-paypal-shopping-cart');
	}

	public function get_icon()
	{
		return 'eicon-product-add-to-cart';
	}

	public function get_categories()
	{
		return ['general'];
	}

	public function get_keywords()
	{
		return ['shopping', 'cart', 'checkout', 'price', 'add', 'variation'];
	}

	public function get_custom_help_url()
	{
		return 'https://developers.elementor.com/docs/widgets/';
	}

	protected function get_upsale_data()
	{
		return [];
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'product',
			[
				'label' => esc_html__('Product', 'wp-ultra-simple-paypal-shopping-cart'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'product_name',
			[
				'label' => esc_html__('Product Name', 'wp-ultra-simple-paypal-shopping-cart'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter your product name', 'wp-ultra-simple-paypal-shopping-cart'),
			]
		);
		$this->add_control(
			'variation_name',
			[
				'label' => esc_html__('Variation Name', 'wp-ultra-simple-paypal-shopping-cart'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter your variation name', 'wp-ultra-simple-paypal-shopping-cart'),
			]
		);
		$this->add_control(
			'variations',
			[
				'label' => esc_html__('Variations', 'wp-ultra-simple-paypal-shopping-cart'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'variation',
						'label' => esc_html__('Variation', 'wp-ultra-simple-paypal-shopping-cart'),
						'type' => \Elementor\Controls_Manager::TEXT,
						'placeholder' => esc_html__('Enter your variation', 'wp-ultra-simple-paypal-shopping-cart'),
					],
					[
						'name' => 'variation_price',
						'label' => esc_html__('Variation Price', 'wp-ultra-simple-paypal-shopping-cart'),
						'type' => \Elementor\Controls_Manager::TEXT,
						'input_type' => 'number',
						'placeholder' => esc_html__('Enter your variation price', 'wp-ultra-simple-paypal-shopping-cart'),
					],
				],
			]
		);


		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		if (empty($settings['product_name'])) {
			return;
		}
		$variationName = sanitize_text_field($settings['variation_name']);
		$variationNumber = count($settings['variations']);
		$variations = [];
		for ($i = 0; $i < $variationNumber; $i++) {
			array_push($variations, [sanitize_text_field($settings['variations'][$i]['variation']), $settings['variations'][$i]['variation_price']]);
		}
		$productName = sanitize_text_field($settings['product_name']);


		$varlist = "[" . $variationName;
		for ($i = 0; $i < $variationNumber; $i++) {
			$varlist .= "|" . $variations[$i][0] . "," . $variations[$i][1];
		}
		$varlist .= "]";
		$content = "[wp_cart:" . $productName . ":price:" . $varlist . ":end]";
		echo print_wp_cart_action($content);
	}

	protected function content_template()
	{
	}
}