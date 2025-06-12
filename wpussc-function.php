<?php

/**
Supersonique Studio WPUSSC Functions
Version: v1.4.1
 */
/**
	This program is free software; you can redistribute it
	under the terms of the GNU General Public License version 2,
	as published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
 */

/** Plugin prefix for WP Ultra Simple Shopping Cart */
$__UP_plug_prefix = "wpussc";

/**
 * Encodes special HTML characters to prevent XSS attacks
 * 
 * Part of jasonwoof utility functions for sanitizing and encoding data.
 * Converts &, <, and > characters to their HTML entity equivalents.
 * 
 * @param string $str The string to encode
 * @return string The HTML-encoded string
 */
function jasonwoof_enc_html($str)
{
	// Replace ampersand first to avoid double-encoding
	$str = str_replace('&', '&amp;', $str);
	// Replace less-than sign with HTML entity
	$str = str_replace('<', '&lt;', $str);
	// Replace greater-than sign with HTML entity
	$str = str_replace('>', '&gt;', $str);
	return $str;
}

/**
 * Encodes special characters for HTML attributes
 * 
 * Sanitizes strings that will be used as HTML attribute values by
 * encoding ampersands and quotes to prevent attribute injection.
 * 
 * @param string $str The string to encode for attribute use
 * @return string The attribute-safe encoded string
 */
function jasonwoof_enc_attr($str)
{
	// Replace ampersand with HTML entity
	$str = str_replace('&', '&amp;', $str);
	// Replace double quotes with HTML entity
	$str = str_replace('"', '&quot;', $str);
	return $str;
}

/**
 * Sanitizes and formats an integer string, defaulting to 1 if blank
 * 
 * Removes all non-digit characters, strips leading zeros, and returns
 * '1' as a fallback if the resulting string is empty.
 * 
 * @param string $str The string to format as integer
 * @return string The sanitized integer string (minimum value: '1')
 */
function jasonwoof_format_int_1($str)
{
	// Remove all non-digits using regex
	$str = preg_replace('|[^0-9]|', '', $str);
	// Remove leading zeros but preserve the last digit
	$str = preg_replace('|^0*([0-9])|', '\1', $str);
	// Default to 1 if no digits remain
	if ($str == '') {
		return '1';
	}
	return $str;
}

/**
 * Formats and processes price values for display
 * 
 * Handles both numeric and string price values. For numeric values,
 * applies currency formatting using WordPress options. Sets locale
 * for monetary formatting and formats to 2 decimal places.
 * 
 * @param mixed $val The price value to format (float, int, or string)
 * @return string|mixed The formatted price or original value if not numeric
 */
function do_price($val)
{
	// Check if value is numeric (float or integer)
	if (is_float($val) || is_int($val)) {
		// Get currency format from WordPress options
		$format = get_option('cart_payment_currency');
		echo $format;
		// Set locale for monetary formatting
		setlocale(LC_MONETARY, $format);
		// Format price to 2 decimal places with dot separator
		$price = number_format($val, 2, '.', '');
	} else {
		// Return original value if not numeric
		$price = $val;
	}

	return $price;
}

/**
 * Handler for always showing the shopping cart
 * 
 * WordPress shortcode handler that displays the shopping cart
 * regardless of its contents.
 * 
 * @param array $atts Shortcode attributes (unused)
 * @return string The shopping cart HTML output
 */
function always_show_cart_handler($atts)
{
	return print_wpus_shopping_cart();
}

/**
 * Conditional shopping cart display handler
 * 
 * Shows the shopping cart with PayPal integration if cart contains items,
 * otherwise displays empty cart content.
 * 
 * @return string The shopping cart HTML or empty cart message
 */
function show_wpus_shopping_cart_handler()
{
	// Ternary operator: show cart if not empty, otherwise show empty message
	$output = (cart_not_empty()) ? print_wpus_shopping_cart("paypal") : get_the_empty_cart_content();
	return $output;
}

/**
 * Validation-focused shopping cart display handler
 * 
 * Shows the shopping cart in validation mode if cart contains items,
 * otherwise displays empty cart content.
 * 
 * @return string The shopping cart HTML in validation mode or empty cart message
 */
function validate_wpus_shopping_cart_handler()
{
	// Ternary operator: show validation cart if not empty, otherwise show empty message
	$output = (cart_not_empty()) ? print_wpus_shopping_cart("validate") : get_the_empty_cart_content();
	return $output;
}

/**
 * Safe permalink retrieval with null check
 * 
 * @deprecated This function needs to be updated
 * @param mixed $post The post object or ID to get permalink for
 * @return string The permalink URL or empty string if post is invalid
 */
function no_notice_get_permalink($post)
{
	// Check if post parameter is empty or null
	if (empty($post)) {
		$permlink = '';
	} else {
		// Get permalink using WordPress function
		$permlink = get_permalink($post);
	}

	return $permlink;
}

/**
 * Processes content to replace shopping cart placeholder with actual cart
 * 
 * Searches for the HTML comment placeholder "<!--show-wp-shopping-cart-->"
 * in content and replaces it with the actual shopping cart HTML if the cart
 * is not empty. Also cleans up paragraph tags around HTML comments.
 * 
 * @param string $content The content to process
 * @return string The processed content with cart placeholder replaced
 */
function shopping_cart_show($content)
{
	// Check if content contains the shopping cart placeholder comment
	if (strpos($content, "<!--show-wp-shopping-cart-->") !== FALSE) {
		// Only process if cart has items
		if (cart_not_empty()) {
			// Remove paragraph tags wrapping HTML comments using regex
			$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
			// Define the placeholder text to search for
			$matchingText = '<!--show-wp-shopping-cart-->';
			// Get the actual shopping cart HTML
			$replacementText = print_wpus_shopping_cart();
			// Replace placeholder with actual cart
			$content = str_replace($matchingText, $replacementText, $content);
		}
	}
	return $content;
}

/**
 * Resets the shopping cart by clearing all products and discount codes
 * 
 * Completely empties the shopping cart session data and removes any
 * applied discount codes and amounts. Handles both empty and populated carts.
 * 
 * @return void
 */
function reset_wp_cart()
{
	// Get current cart products from session
	$products = $_SESSION['ultraSimpleCart'];

	// If cart is already empty, just clear session variables
	if (empty($products)) {
		unset($_SESSION['ultraSimpleCart']);
		// Also reset discount codes (French comment: Réinitialiser également les codes de réduction)
		unset($_SESSION['wpussc_discount_code']);
		unset($_SESSION['wpussc_discount_amount']);
		return;
	}
	
	// Remove each product from the cart array
	foreach ($products as $key => $item) {
		unset($products[$key]);
	}

	// Update session with empty cart
	$_SESSION['ultraSimpleCart'] = $products;
	// Also reset discount codes (French comment: Réinitialiser également les codes de réduction)
	unset($_SESSION['wpussc_discount_code']);
	unset($_SESSION['wpussc_discount_amount']);
}

/**
 * Converts a price string to float value
 * 
 * Simple price parsing function that converts string representation
 * of a price to a float value. Contains commented legacy code for
 * handling comma-separated price formats.
 * 
 * @param string $pricestr The price string to convert
 * @return float The price as a float value
 */
function get_the_price($pricestr)
{
	// Legacy code for handling comma-separated prices (commented out)
	// $pos = stripos($pricestr, ",");
	// $price = (int) $pricestr;
	// if ($pos !== false) {
	// 	$pricearray = explode(",", $pricestr);
	// 	$price = $pricearray[1];
	// }
	
	// Simply convert string to float
	return (float)$pricestr;
}

/**
 * Cleans and formats product names for better display
 * 
 * Processes product names containing variations in parentheses format.
 * Extracts the first variation option and replaces multiple variation
 * separators with a cleaner dash format for improved readability.
 * 
 * @param string $namestr The original product name string to clean
 * @return string The cleaned and formatted product name
 */
function get_the_name($namestr)
{
	// Clean the name of the item to have a better display
	// Check if name contains variations in parentheses using regex
	if (preg_match("/\(([^\)]*)\).*/", $namestr, $matched)) {
		// Split the matched content by comma to get variation options
		$namearray = explode(",", $matched[1]);
		// Replace the full matched content with just the first option
		$name = str_ireplace($matched[1], $namearray[0], $namestr);

		// Handle multiple variations separated by ")("
		$nameVariationArray = explode(")(", $name);

		// Process each variation segment (legacy code - currently unused)
		foreach ($nameVariationArray as $item) {
			$nameSmallArray = explode(",", $item);
			// Legacy code commented out:
			//$name = str_ireplace ( $matched[1] , $namearray[0], $nameSmallArray );
		}

		// Replace ")(" separators with cleaner " - " format
		$name = str_ireplace(")(", " - ", $name);
	} else {
		// If no variations found, use original name
		$name = $namestr;
	}

	return $name;
}

/**
 * Generates HTML content for empty shopping cart display
 * 
 * Creates a formatted empty cart message with optional custom text or image,
 * and includes a link back to the shop if configured. Respects the plugin
 * setting for whether to display empty cart content.
 * 
 * @return string|null The empty cart HTML content or null if display is disabled
 */
function get_the_empty_cart_content()
{
	// Initialize output variable (redundant check for empty state)
	$output = (empty($output)) ? '' : $output;

	// Get plugin configuration options from WordPress
	$wp_cart_visit_shop_text = get_option('wp_cart_visit_shop_text');
	$empty_cart_text = get_option('wp_cart_empty_text');
	$emptyCartAllowDisplay = get_option('wpus_shopping_cart_empty_hide');

	// Start building the empty cart container
	$output .= '<div id="empty-cart">';

	// Add custom empty cart text or image if configured
	if (!empty($empty_cart_text)) {
		// Check if the text contains "http" to determine if it's an image URL
		if (preg_match("/http/", $empty_cart_text)) {
			// Display as image with proper escaping for security
			$output .= '<img src="' . esc_url($empty_cart_text) . '" alt="' . esc_attr($empty_cart_text) . '" />';
		} else {
			// Display as text with proper HTML escaping
			$output .= '<span class="empty-cart-text">' . esc_html($empty_cart_text) . '</span>';
		}
	}

	// Get shop page URL from options
	$cart_products_page_url = get_option('cart_products_page_url');

	// Add "visit shop" link if URL is configured
	if (!empty($cart_products_page_url)) {
		$output .= '<a rel="nofollow" href="' . $cart_products_page_url . '">' . $wp_cart_visit_shop_text . '</a>';
	}

	// Close the empty cart container
	$output .= '</div>';

	// Return content only if empty cart display is enabled
	if (!$emptyCartAllowDisplay) {
		return $output;
	}
}

/**
 * Generates affiliate tracking custom field for PayPal forms
 * 
 * Creates a hidden input field for affiliate tracking if the WP Affiliate Platform
 * plugin is active. Checks for affiliate ID in session first, then falls back
 * to cookies. Used for commission tracking in PayPal transactions.
 * 
 * @return string|null Hidden input HTML for affiliate tracking or null if no affiliate data
 */
function wp_cart_add_custom_field()
{
	// Check if WP Affiliate Platform plugin is installed and active
	if (function_exists('wp_aff_platform_install')) {
		$output = '';
		// First check for affiliate ID in session data
		if (!empty($_SESSION['ap_id'])) {
			// Create hidden input with session affiliate ID
			$output = '<input type="hidden" name="custom" value="' . esc_attr($_SESSION['ap_id']) . '" id="wp_affiliate" />';
		} elseif (isset($_COOKIE['ap_id'])) {
			// Fallback to cookie affiliate ID if session is empty
			$output = '<input type="hidden" name="custom" value="' . esc_attr($_COOKIE['ap_id']) . '" id="wp_affiliate" />';
		}
		return $output;
	}
}

/**
 * Outputs JavaScript for dynamic form reading and product variation handling
 * 
 * Generates inline JavaScript that processes form elements to build product
 * names with variations. Specifically handles select dropdowns (except quantity
 * and amount) to concatenate selected values into a formatted product string.
 * 
 * @return void Directly echoes JavaScript code
 */
function wp_cart_add_read_form_javascript()
{
	echo '
	<script type="text/javascript">
	<!--
	//
	function ReadForm (obj1, tst)
	{
		// Read the user form
		var i,j,pos;
		val_total="";
		val_combo="";

		// Loop through all form elements
		for (i=0; i<obj1.length; i++)
		{
			// run entire form
			obj = obj1.elements[i];		   // a form element

			// Process only select dropdown elements
			if(obj.type == "select-one")
			{   // just selects
				// Skip quantity and amount fields
				if(obj.name == "quantity" ||
					obj.name == "amount") continue;
				pos = obj.selectedIndex;		// which option selected
				val = obj.options[pos].value;   // selected value
				// Build combination string with parentheses
				val_combo = val_combo + "(" + val + ")";
			}
		}
		// Now summarize everything we have processed above
		// Combine base product name with variations
		val_total = obj1.product_tmp.value + val_combo;
		// Set the final product name in the hidden field
		obj1.product.value = val_total;
	}
	//-->
	</script>';
}

/**
 * Generates quantity display string for shopping cart items
 * 
 * Creates a formatted string showing the number of items in the cart
 * with proper pluralization. Uses configurable text strings from
 * WordPress options for customization.
 * 
 * @return string Formatted string indicating cart item quantity
 */
function wpusc_cart_item_qty()
{
	// Get the current number of items in cart
	$itemInCart = cart_not_empty();
	// Get configurable text strings from WordPress options
	$itemQtyString = get_option('item_qty_string');
	$noItemInCartString = get_option('no_item_in_cart_string');

	// Build quantity string if cart has items
	if ($itemInCart > 0) {
		// Determine plural suffix based on quantity
		if ($itemInCart == 1) {
			$plural = "";
		} else {
			$plural = "s";
		}

		// Format string using sprintf with quantity and plural suffix
		$displayQtyString = sprintf($itemQtyString, $itemInCart, $plural);
	} else {
		// Use empty cart message when no items present
		$displayQtyString = $noItemInCartString;
	}

	return ($displayQtyString);
}

/**
 * Formats price with currency symbol according to specified order and locale
 * 
 * Takes a numeric price value and formats it with the provided currency symbol,
 * decimal separator, and positioning. Supports both prefix and suffix symbol
 * placement with proper number formatting.
 * 
 * @param float|string $price The price value to format
 * @param string $symbol The currency symbol (e.g., '$', '€', '£')
 * @param string $decimal The decimal separator character
 * @param string $defaultSymbolOrder Symbol position: "1" for prefix, "2" for suffix
 * @return string The formatted price with currency symbol
 */
function print_payment_currency($price, $symbol, $decimal, $defaultSymbolOrder)
{
	// Ensure price is converted to float for consistent formatting
	$price = floatval($price);

	// Format price based on symbol position preference
	switch ($defaultSymbolOrder) {

		case "1":
			// Prefix: symbol before price (e.g., $10.99)
			$priceSymbol = $symbol . number_format($price, 2, $decimal, ',');
			break;

		case "2":
			// Suffix: symbol after price (e.g., 10.99$)
			$priceSymbol = number_format($price, 2, $decimal, ',') . $symbol;
			break;

		default:
			// Default to prefix format if order is not specified
			$priceSymbol = $symbol . number_format($price, 2, $decimal, ',');
	}

	return $priceSymbol;
	// Legacy code commented out: return $symbol.number_format($price, 2, $decimal, ',');
}

/**
 * Displays widget control interface for PayPal Shopping Cart widget
 * 
 * Outputs a simple instruction message directing users to configure
 * the plugin settings from the WordPress Settings menu. Used in the
 * WordPress widget administration interface.
 * 
 * @return void Directly echoes HTML content
 */
function wp_paypal_shopping_cart_widget_control()
{
	// Display instructional message with internationalization support
	echo "<p>" . __("Set the Plugin Settings from the Settings menu", "wp-ultra-simple-paypal-shopping-cart") . "</p>";
}

/**
 * Initializes and registers the PayPal Shopping Cart widget
 * 
 * Sets up the WordPress widget with appropriate options and registers
 * both the widget display function and control interface. Configures
 * widget metadata including CSS class and description.
 * 
 * @return void Registers widget with WordPress
 */
function widget_wp_paypal_shopping_cart_init()
{
	// Define widget configuration options
	$widget_options = array(
		'classname' => 'widget_wp_paypal_shopping_cart',
		'description' => __("Display WP Ultra Simple Paypal Shopping Cart.", "wp-ultra-simple-paypal-shopping-cart")
	);

	// Register the main widget with WordPress
	wp_register_sidebar_widget('wp_paypal_shopping_cart_widgets', __("WP Ultra Simple Paypal Shopping Cart", "wp-ultra-simple-paypal-shopping-cart"), 'show_wp_paypal_shopping_cart_widget', $widget_options);

	// Register the widget control interface
	wp_register_widget_control('wp_paypal_shopping_cart_widgets', __("WP Ultra Simple Paypal Shopping Cart", "wp-ultra-simple-paypal-shopping-cart"), 'wp_paypal_shopping_cart_widget_control');
}

/**
 * Adds settings link to plugin action links in WordPress admin
 * 
 * Enhances the plugin list page by adding a "Settings" link that directs
 * users to the plugin's configuration page. Only adds the link for this
 * specific plugin file to avoid conflicts.
 * 
 * @param array $links Existing plugin action links
 * @param string $file The plugin file being processed
 * @return array Modified links array with settings link prepended
 */
function wp_ultra_simple_cart_add_settings_link($links, $file)
{
	// Check if this is the current plugin file
	if ($file == plugin_basename(__FILE__)) {
		// Create settings link with proper WordPress admin URL structure
		$settings_link = '<a href="options-general.php?page=' . dirname(plugin_basename(__FILE__)) . '/wp_ultra_simple_shopping_cart.php">' . __("Settings", "wp-ultra-simple-paypal-shopping-cart") . '</a>';
		// Add settings link to the beginning of the links array
		array_unshift($links, $settings_link);
	}
	return $links;
}
 
// Hook the settings link function to WordPress plugin action links filter
add_filter('plugin_action_links', 'wp_ultra_simple_cart_add_settings_link', 10, 2);

/**
 * Checks if shopping cart contains items and returns count
 * 
 * Examines the session-stored shopping cart to determine if it contains
 * any products. Returns the total number of items or 0 if cart is empty
 * or doesn't exist. Essential for conditional cart display logic.
 * 
 * @return int Number of items in the shopping cart (0 if empty)
 */
function cart_not_empty()
{
	$count = 0;
	// Check if cart session exists and is a valid array
	if (isset($_SESSION['ultraSimpleCart']) && is_array($_SESSION['ultraSimpleCart'])) {
		// Count each item in the cart (not quantity, just unique items)
		foreach ($_SESSION['ultraSimpleCart'] as $item)
			$count++;
		return $count;
	} else {
		// Return 0 if cart doesn't exist or is not an array
		return 0;
	}
}

/**
 * Enqueues front-end CSS and JavaScript resources for the shopping cart
 * 
 * Loads the main plugin stylesheet and Ionicons library for cart display.
 * Uses both ES module and nomodule versions of Ionicons to ensure
 * compatibility across different browsers. Called via WordPress wp_enqueue_scripts hook.
 * 
 * @return void Enqueues resources with WordPress
 */
function wuspsc_cart_css()
{
	// Enqueue main plugin stylesheet with version for cache busting
	wp_enqueue_style('wp_ultra_simple_shopping_cart_style', plugin_dir_url(__FILE__) . '/wp_ultra_simple_shopping_cart_style.css', array(), '5.0.2', 'all');
	
	// Enqueue Ionicons ES module version for modern browsers
	wp_enqueue_script(
		'ionicons',
		'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js',
		[],
		null,
		true
	);
	
	// Enqueue Ionicons fallback for older browsers (nomodule)
	wp_enqueue_script(
		'ionicons-nomodule',
		'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js',
		[],
		null,
		true
	);
}

/**
 * Enqueues admin-specific CSS and JavaScript resources for the shopping cart
 * 
 * Loads admin panel styling, smooth UI theme, and Ionicons for the WordPress
 * admin interface. Includes both the main admin stylesheet and jQuery UI
 * smooth theme for consistent styling. Called via admin_enqueue_scripts hook.
 * 
 * @return void Registers and enqueues admin resources with WordPress
 */
function wuspsc_admin_register_head_cart_css()
{
	// Register and enqueue main admin stylesheet
	wp_register_style('wp_ultra_simple_shopping_cart_admin_style', plugin_dir_url(__FILE__) . '/wp_ultra_simple_shopping_cart_admin_style.css', false, '5.0.2');
	wp_enqueue_style('wp_ultra_simple_shopping_cart_admin_style');

	// Register and enqueue jQuery UI smooth theme for consistent admin UI
	wp_register_style('wp_ultra_simple_shopping_cart_admin_themes_smoothness', plugin_dir_url(__FILE__) . '/css/smooth-theme.min.css', false, '1.0.0');
	wp_enqueue_style('wp_ultra_simple_shopping_cart_admin_themes_smoothness');

	// Enqueue Ionicons ES module version for modern browsers in admin
	wp_enqueue_script(
		'ionicons',
		'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js',
		[],
		null,
		true
	);
	
	// Enqueue Ionicons fallback for older browsers in admin (nomodule)
	wp_enqueue_script(
		'ionicons-nomodule',
		'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js',
		[],
		null,
		true
	);
}

// Add custom image size for product thumbnails if WordPress supports it
if (function_exists('add_image_size')) {
	// Register product thumbnail size: 64x64 pixels, cropped to exact dimensions
	add_image_size('wuspsc-product-thumb', 64, 64, true); //(cropped)
}

/**
 * WordPress Hooks Registration
 * 
 * Registers the plugin's CSS/JS enqueueing functions with appropriate WordPress hooks.
 * Uses wp_enqueue_scripts for front-end resources and admin_enqueue_scripts for admin panel.
 * 
 * @see https://codex.wordpress.org/Function_Reference/add_action
 */

// Hook front-end CSS/JS loading to wp_enqueue_scripts
add_action('wp_enqueue_scripts', 'wuspsc_cart_css');

// Hook admin CSS/JS loading to admin_enqueue_scripts
add_action('admin_enqueue_scripts', 'wuspsc_admin_register_head_cart_css');
