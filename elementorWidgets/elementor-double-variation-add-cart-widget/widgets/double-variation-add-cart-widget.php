<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Elementor_Double_Variation_Add_Cart_Widget extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'double-variation-add-cart-widget';
	}

	public function get_title()
	{
		return esc_html__('Double variation add cart', 'wp-ultra-simple-paypal-shopping-cart');
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
		return ['shopping', 'cart', 'checkout', 'double', 'add', 'variation'];
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
			'product_price',
			[
				'label' => esc_html__('Product Price', 'wp-ultra-simple-paypal-shopping-cart'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'placeholder' => esc_html__('Enter your product price', 'wp-ultra-simple-paypal-shopping-cart'),
			]
		);
		$this->add_control(
			'first_variation_name',
			[
				'label' => esc_html__('First variation Name', 'wp-ultra-simple-paypal-shopping-cart'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter your variation name', 'wp-ultra-simple-paypal-shopping-cart'),
			]
		);
		$this->add_control(
			'first_variations',
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
				],
			]
		);
		$this->add_control(
			'second_variation_name',
			[
				'label' => esc_html__('Second Variation Name', 'wp-ultra-simple-paypal-shopping-cart'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter your variation name', 'wp-ultra-simple-paypal-shopping-cart'),
			]
		);
		$this->add_control(
			'second_variations',
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
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		if (empty($settings['product_name']) || empty($settings['product_price'])) {
			return;
		}
		$variation1Name = sanitize_text_field($settings['first_variation_name']);
		$variation1Number = count($settings['first_variations']);
		$variations1 = [];

		$variation2Name = sanitize_text_field($settings['second_variation_name']);
		$variation2Number = count($settings['second_variations']);
		$variations2 = [];

		for ($i = 0; $i < $variation1Number; $i++) {
			array_push($variations1, sanitize_text_field($settings['first_variations'][$i]['variation']));
		}

		$variation2Name = sanitize_text_field($settings['second_variation_name']);
		$variation2Number = count($settings['second_variations']);
		$variations2 = [];

		for ($i = 0; $i < $variation2Number; $i++) {
			array_push($variations2, sanitize_text_field($settings['second_variations'][$i]['variation']));
		}
		$productName = sanitize_text_field($settings['product_name']);
		$productPrice = $settings['product_price'];


		$varlist1 = ":var1[" . $variation1Name;
		for ($i = 0; $i < $variation1Number; $i++) {
			$varlist1 .= "|" . $variations1[$i];
		}
		$varlist1 .= "]";

		$varlist2 = ":var2[" . $variation2Name;
		for ($i = 0; $i < $variation2Number; $i++) {
			$varlist2 .= "|" . $variations2[$i];
		}
		$varlist2 .= "]";

		$content = "[wp_cart:" . $productName . ":price:" . $productPrice . $varlist1 . $varlist2 . ":end]";
		echo print_wp_cart_action($content);
	}

	protected function content_template()
	{
	}
}
