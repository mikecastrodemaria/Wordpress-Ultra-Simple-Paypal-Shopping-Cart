<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Elementor_Simple_Form_Widget extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'simple-form-widget';
	}

	public function get_title()
	{
		return esc_html__('Simple form', 'wp-ultra-simple-paypal-shopping-cart');
	}

	public function get_icon()
	{
		return 'eicon-bullet-list';
	}

	public function get_categories()
	{
		return ['general'];
	}

	public function get_keywords()
	{
		return ['shopping', 'form', 'checkout', 'simple', 'validation', "steps"];
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
			'return_url',
			[
				'label' => esc_html__('Return URL', 'wp-ultra-simple-paypal-shopping-cart'),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);
		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$returnUrl = $settings['return_url'];
		if (empty($returnUrl)) {
			return;
		}
		$token = isset($_SESSION["csrf_token"]) ? $_SESSION["csrf_token"] : "";

		$postString = '
    <form action="' . esc_url(admin_url('admin-post.php')) . '" method="post">
    <input type="hidden" name="action" value="formRedirect" >
    <input type="hidden" name="csrf_token" value="' . $token . '">
    <input type="hidden" name="returnUrl" value="' . $returnUrl . '">
    <div class="form-container">
    <div class="two-entry-container">
        <div class="name-component" style="margin-right: 10px;">
            <label for="Fname">' . __("First Name", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <input type="text" id="Fname" name="given-name" autocomplete="on" style="flex:1" required>
        </div>
        <div class="name-component">
            <label for="Lname">' . __("Last Name", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <input type="text" id="Lname" name="family-name" autocomplete="on" style="flex:1" required> 
        </div>
    </div>
    ';

		$postString .= '

    <div class="two-entry-container">';

		if (!empty(get_option('wpus_form_include_email'))) {
			$postString .= '
        <div class="name-component" style="margin-right: 10px;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" autocomplete="on" style="flex:1" required>
        </div>';
		}

		if (!empty(get_option('wpus_form_include_phone'))) {
			$postString .= '
        <div class="name-component">
            <label for="phone">' . __("Phone Number", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <input type="text" id="phone" name="tel" autocomplete="on" style="flex:1" required>
        </div>
    ';
		}
		$postString .= '</div>';
		if (!empty(get_option('wpus_form_include_address'))) {
			$postString .= '
    <br>

    <label for="address">' . __("Address", "wp-ultra-simple-paypal-shopping-cart") . ':</label><br>

    <div class="address-components-container">
        <div class="labels-container">
            <div class="address-label">
                <label for="street">' . __("Street", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            </div>
            <div class="address-label">
                <label for="complement">' . __("Complement", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            </div>
            <div class="address-label">
                <label for="city">' . __("City", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            </div>
            <div class="address-label">
                <label for="zip">' . __("Zip Code", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            </div>
            <div class="address-label">
                <label for="country">' . __("Country", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            </div>
        </div>
        <div class="inputs-container">
            <div class="address-input">
                <input type="text" id="street" name="street-address" autocomplete="on" required>
            </div>
            <div class="address-input"> 
                <input type="text" id="complement" name="complement" autocomplete="on">
            </div>
            <div class="address-input">
                <input type="text" id="city" name="address-level2" autocomplete="on" required>
            </div>
            <div class="address-input">
                <input type="text" id="zip" name="postal-code" autocomplete="on" required>
            </div>
            <div class="address-input">
                <input type="text" id="country" name="country" autocomplete="on" required>
            </div>
        </div>
    </div>
    ';
		}

		if (!empty(get_option('wpus_form_include_message'))) {
			$postString .= '
    <br>
    <div class="message-container">
        <label for="message">' . __("Message", "wp-ultra-simple-paypal-shopping-cart") . ':</label><br>
        <textarea id="message" name="message" style="flex:1"></textarea><br>
    </div>
    <input type="submit" value="' . __("Submit", "wp-ultra-simple-paypal-shopping-cart") . '">
    </div>
    </form>';
		} else {
			$postString .= '
    <input type="submit" value="' . __("Submit", "wp-ultra-simple-paypal-shopping-cart") . '">
    </div>
    </form>';
		}
		echo $postString;
	}

	protected function content_template()
	{
	}
}