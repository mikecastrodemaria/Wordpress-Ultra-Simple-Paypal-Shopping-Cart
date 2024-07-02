<?php
/**
Ultra Prod WPUSSC Admin Options
Version: v1.5
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
// loading language files
load_plugin_textdomain('wp-ultra-simple-paypal-shopping-cart', false, WUSPSC_PLUGIN_DIRNAME . '/languages');

/* adding a named option/value in WP : http://codex.wordpress.org/Function_Reference/add_option */
//
add_option('wp_cart_title',					__("Your Shopping Cart", "wp-ultra-simple-paypal-shopping-cart"));
add_option('wp_cart_empty_text',			__("Your cart is empty", "wp-ultra-simple-paypal-shopping-cart"));
add_option('wpus_shopping_cart_empty_hide',				'1');

add_option('wp_cart_enable_debug',						'0');

add_option('wpus_shopping_cart_shipping_per_items',		'0');
add_option('wpus_display_link_in_cart',					'1');
add_option('wpus_display_thumbnail_in_cart',			'0');
add_option('wpus_thumbnail_in_cart_width',				'32');
add_option('wpus_thumbnail_in_cart_height',				'32');
add_option('wp_cart_visit_shop_text',		__('Visit The Shop', "wp-ultra-simple-paypal-shopping-cart"));
add_option('wp_cart_update_quantiy_text',	__('Hit enter or click on reload icon to submit the updated quantity please.', "wp-ultra-simple-paypal-shopping-cart"));
add_option('wpus_shopping_cart_items_in_cart_hide', '1');
add_option('plural_items_text',				__('products in your cart', "wp-ultra-simple-paypal-shopping-cart"));
add_option('singular_items_text',			__('product in your cart', "wp-ultra-simple-paypal-shopping-cart"));
add_option('add_cartstyle',					'wp_cart_button');
add_option('checkout_style',				'wp_checkout_button');
//add_option('use_custom_button',				'0');
add_option('display_product_name',			'0');
add_option('display_product_inline',		'0');
add_option('display_quantity',				'0');
add_option('subtotal_text',					__('Subtotal', "wp-ultra-simple-paypal-shopping-cart"));
add_option('shipping_text',					__('Shipping', "wp-ultra-simple-paypal-shopping-cart"));
add_option('total_text',					__('Total', "wp-ultra-simple-paypal-shopping-cart"));
add_option('item_name_text',				__('Item Name', "wp-ultra-simple-paypal-shopping-cart"));
add_option('qualtity_text',					__('Quantity', "wp-ultra-simple-paypal-shopping-cart"));
add_option('price_text',					__('Price', "wp-ultra-simple-paypal-shopping-cart"));
add_option('vat_text',						__('VAT', "wp-ultra-simple-paypal-shopping-cart"));
add_option('remove_text',					__("Remove", "wp-ultra-simple-paypal-shopping-cart"));

add_option('checkoutButtonName',			__("Remove", "wp-ultra-simple-paypal-shopping-cart"));

add_option('add_cartstyle',					'');
add_option('cart_currency_symbol_order',	'1');
// wpusc_cart_item_qty() default string
add_option('item_qty_string',				'%d item%s in your cart');
add_option('no_item_in_cart_string',		'Cart empty');
add_option('cart_return_from_paypal_url',	get_bloginfo('wpurl'));

function isKeyDefined($key) {
	return (isset($_POST['wpus_shopping_cart_collect_address']) ? sanitize_text_field($_POST['wpus_shopping_cart_collect_address']): "");
}

function show_wp_cart_options_page () {
	if(isset($_POST['info_update'])) 
	{

		$retrieved_nonce = $_POST['_wpnonce'];

		if (!wp_verify_nonce($retrieved_nonce, 'delete_my_action' ) )
		{
			die( __('Failed security check') );
		}
		
		// compatibility with php 8

		// wpus_shopping_cart_collect_address
		$wpus_shopping_cart_collect_address = '';
		if (isKeyDefined('wpus_shopping_cart_collect_address')) { 
			$wpus_shopping_cart_collect_address = 'checked="checked"';
		}
		update_option('wpus_shopping_cart_collect_address', $wpus_shopping_cart_collect_address);

		// wpus_shopping_cart_use_profile_shipping
		$wpus_shopping_cart_use_profile_shipping = '';
		if (isKeyDefined('wpus_shopping_cart_use_profile_shipping')) { 
			$wpus_shopping_cart_use_profile_shipping = 'checked="checked"';
		}
		update_option('wpus_shopping_cart_use_profile_shipping', $wpus_shopping_cart_use_profile_shipping);
		
		// wpus_shopping_cart_shipping_per_items
		$wpus_shopping_cart_shipping_per_items = 1;
		if (isset($_POST['wpus_shopping_cart_shipping_per_items'])) { 
			$wpus_shopping_cart_shipping_per_items = intval($_POST['wpus_shopping_cart_shipping_per_items']);
		}
		update_option('wpus_shopping_cart_shipping_per_items', $wpus_shopping_cart_shipping_per_items);
		
		// display_free_shipping
		$display_free_shipping = 0;
		if (isset($_POST['display_free_shipping'])) { 
			$display_free_shipping = intval($_POST['display_free_shipping']);
		}
		update_option('display_free_shipping', $display_free_shipping);

		// use_custom_button
		$use_custom_button = '';
		if (isKeyDefined('use_custom_button')) { 
			$use_custom_button = 'checked="checked"';
		}
		update_option('use_custom_button', $use_custom_button);

		// wpus_display_thumbnail_in_cart
		$wpus_display_thumbnail_in_cart = '';
		if (isKeyDefined('wpus_display_thumbnail_in_cart')) { 
			$wpus_display_thumbnail_in_cart = 'checked="checked"';
		}
		update_option('wpus_display_thumbnail_in_cart', $wpus_display_thumbnail_in_cart);

		// wp_cart_update_quantiy_text
		$wp_cart_update_quantiy_text = '';
		if (isKeyDefined('wp_cart_update_quantiy_text')) { 
			$wp_cart_update_quantiy_text = (string)sanitize_text_field($_POST['wp_cart_update_quantiy_text']);
		}
		update_option('wp_cart_update_quantiy_text', $wp_cart_update_quantiy_text);

		// display_quantity
		$display_quantity = '';
		if (isKeyDefined('display_quantity')) { 
			$display_quantity = (string)sanitize_text_field($_POST["display_quantity"]);
		}
		update_option('display_quantity', $display_quantity);

		// wpus_shopping_cart_auto_redirect_to_checkout_page
		$wpus_shopping_cart_auto_redirect_to_checkout_page = '';
		if (isKeyDefined('wpus_shopping_cart_auto_redirect_to_checkout_page')) { 
			$wpus_shopping_cart_auto_redirect_to_checkout_page = 'checked="checked"';
		}
		update_option('wpus_shopping_cart_auto_redirect_to_checkout_page', $wpus_shopping_cart_auto_redirect_to_checkout_page);
		
		// wpus_shopping_cart_reset_after_redirection_to_return_page
		$wpus_shopping_cart_reset_after_redirection_to_return_page = '';
		if (isKeyDefined('wpus_shopping_cart_reset_after_redirection_to_return_page')) { 
			$wpus_shopping_cart_reset_after_redirection_to_return_page = 'checked="checked"';
		}
		update_option('wpus_shopping_cart_reset_after_redirection_to_return_page', $wpus_shopping_cart_reset_after_redirection_to_return_page);

		// wpus_shopping_cart_image_hide
		$wpus_shopping_cart_image_hide = '';
		if (isKeyDefined('wpus_shopping_cart_image_hide')) { 
			$wpus_shopping_cart_image_hide = 'checked="checked"';
		}
		update_option('wpus_shopping_cart_image_hide', $wpus_shopping_cart_image_hide);

		// wp_use_aff_platform
		$wp_use_aff_platform = '';
		if (isKeyDefined('wp_use_aff_platform')) { 
			$wp_use_aff_platform = 'checked="checked"';
		}
		update_option('wp_use_aff_platform', $wp_use_aff_platform);

		// display_product_name
		$display_product_name = '';
		if (isKeyDefined('display_product_name')) { 
			$display_product_name = (string)sanitize_text_field($_POST['display_product_name']);
		}
		update_option('display_product_name', $display_product_name);

		// display_product_inline
		$display_product_inline = '';
		if (isKeyDefined('display_product_inline')) { 
			$display_product_inline = (string)sanitize_text_field($_POST['display_product_inline']);
		}
		update_option('display_product_inline', $display_product_inline);
		

		update_option('cart_payment_currency', (string) sanitize_text_field( $_POST["cart_payment_currency"]) );
		update_option('cart_currency_symbol', (string)sanitize_text_field($_POST["cart_currency_symbol"]));
		update_option('cart_currency_symbol_order', (string)sanitize_text_field($_POST["cart_currency_symbol_order"]));
		update_option('cart_base_shipping_cost', (string)sanitize_text_field($_POST["cart_base_shipping_cost"]));
		update_option('cart_free_shipping_threshold', (string)sanitize_text_field($_POST["cart_free_shipping_threshold"]));

		update_option('cart_paypal_email', (string)sanitize_text_field($_POST["cart_paypal_email"]));
		update_option('wp_cart_title', (string)sanitize_text_field($_POST["wp_cart_title"]));

		update_option('display_vat', (string)sanitize_text_field($_POST["display_vat"]));

		// custom button option
		update_option('add_cartstyle', (string)sanitize_text_field($_POST["add_cartstyle"]));
		update_option('addToCartButtonName', (string)sanitize_text_field($_POST["addToCartButtonName"]));
		update_option('checkout_style', (string)sanitize_text_field($_POST["checkout_style"]));
		update_option('checkoutButtonName', (string)sanitize_text_field($_POST["checkoutButtonName"]));

		update_option('wp_cart_empty_text', (string)sanitize_text_field($_POST["wp_cart_empty_text"]));
		update_option('wpus_shopping_cart_empty_hide', (sanitize_text_field($_POST['wpus_shopping_cart_empty_hide'])!='') ? 'checked="checked"':'' );
		update_option('wpus_display_link_in_cart', (sanitize_text_field($_POST['wpus_display_link_in_cart'])!='') ? 'checked="checked"':'' );
		
		update_option('wpus_thumbnail_in_cart_width', intval($_POST["wpus_thumbnail_in_cart_width"]));
		update_option('wpus_thumbnail_in_cart_height', intval($_POST["wpus_thumbnail_in_cart_height"]));

		update_option('cart_validate_url', (string)sanitize_text_field($_POST["cart_validate_url"]));
		update_option('cart_return_from_paypal_url', (string)sanitize_text_field($_POST["cart_return_from_paypal_url"]));
		update_option('cart_products_page_url', (string)sanitize_text_field($_POST["cart_products_page_url"]));

		// txt string
		update_option('wp_cart_visit_shop_text', (string)sanitize_text_field($_POST["wp_cart_visit_shop_text"]));
		

		update_option('plural_items_text', (string)sanitize_text_field($_POST["plural_items_text"]));
		update_option('singular_items_text', (string)sanitize_text_field($_POST["singular_items_text"]));
		update_option('wpus_shopping_cart_items_in_cart_hide', (string)sanitize_text_field($_POST["wpus_shopping_cart_items_in_cart_hide"]));

		

		update_option('subtotal_text', (string)sanitize_text_field($_POST["subtotal_text"]));
		update_option('shipping_text', (string)sanitize_text_field($_POST["shipping_text"]));
		update_option('total_text', (string)sanitize_text_field($_POST["total_text"]));
		update_option('item_name_text', (string)sanitize_text_field($_POST["item_name_text"]));
		update_option('qualtity_text', (string)sanitize_text_field($_POST["qualtity_text"]));
		update_option('price_text', (string)sanitize_text_field($_POST["price_text"]));
		update_option('vat_text', (string)sanitize_text_field($_POST["vat_text"]));
		update_option('item_name_text', (string)sanitize_text_field($_POST["item_name_text"]));
		update_option('qualtity_text', (string)sanitize_text_field($_POST["qualtity_text"]));
		update_option('vat_text', (string)sanitize_text_field($_POST["vat_text"]));
		update_option('remove_text', (string)sanitize_text_field($_POST["remove_text"]));

		// wpusc_cart_item_qty() string
		update_option('item_qty_string', (string)sanitize_text_field($_POST["item_qty_string"]));
		update_option('no_item_in_cart_string', (string)sanitize_text_field($_POST["no_item_in_cart_string"]));

		// sandbox option
		update_option('is_sandbox', (string)sanitize_text_field($_POST["is_sandbox"]));

		// debug
		update_option('wp_cart_enable_debug', (string)sanitize_text_field($_POST["wp_cart_enable_debug"]));

		update_option('cart_checkout_page_url', (string)sanitize_text_field($_POST["cart_checkout_page_url"]));

		echo '<div id="message" class="updated fade">';
		echo '<p><strong>'. __("Options Updated!", "wp-ultra-simple-paypal-shopping-cart").'</strong></p></div>';
	}

	$defaultCurrency = get_option('cart_payment_currency');
	if(empty($defaultCurrency)) $defaultCurrency = __("USD", "wp-ultra-simple-paypal-shopping-cart");

	$defaultSymbol = get_option('cart_currency_symbol');
	if(empty($defaultSymbol)) $defaultSymbol = __("$", "wp-ultra-simple-paypal-shopping-cart");

	// Symbol order
	$defaultSymbolOrder = get_option('cart_currency_symbol_order');
	if(empty($defaultSymbolOrder)) { $defaultSymbolOrder = "1"; }
	//
	if( $defaultSymbolOrder == "1"){
		$defaultSymbolOrderChecked1 = "checked";
		$defaultSymbolOrderChecked2 = "";
	} elseif( $defaultSymbolOrder == "2") {
		$defaultSymbolOrderChecked1 = "";
		$defaultSymbolOrderChecked2 = "checked";
	} else {
		$defaultSymbolOrderChecked1 = "";
		$defaultSymbolOrderChecked2 = "";
	}

	$baseShipping = get_option('cart_base_shipping_cost');
	if(empty($baseShipping)) $baseShipping = 0;

	$cart_free_shipping_threshold = get_option('cart_free_shipping_threshold');

	$display_vat = get_option('display_vat');

	$defaultEmail = get_option('cart_paypal_email');
	if(empty($defaultEmail)) $defaultEmail = get_bloginfo('admin_email');

	$return_url =  get_option('cart_return_from_paypal_url');
	$cart_validate_url =  get_option('cart_validate_url');

	$title = get_option('wp_cart_title');
	//-if(empty($title)) $title = __("Your Shopping Cart", "wp-ultra-simple-paypal-shopping-cart");

	$itemQtyString = get_option('item_qty_string');
	if(empty($itemQtyString)) $itemQtyString = __("%d item%s in your cart", "wp-ultra-simple-paypal-shopping-cart");
	$noItemInCartString = get_option('no_item_in_cart_string');
	if(empty($noItemInCartString)) $noItemInCartString = __("Cart empty", "wp-ultra-simple-paypal-shopping-cart");

	$option_display_free_shipping = get_option('display_free_shipping');
	$displayFreeShipping = (!empty( $display_free_shipping ))? 'checked="checked"': '';

	$option_wpus_shopping_cart_shipping_per_items = get_option('wpus_shopping_cart_shipping_per_items');
	$wpus_shopping_cart_shipping_per_items = (!empty( $option_wpus_shopping_cart_shipping_per_items ))? 'checked="checked"': '';

// use_custom_button

	$use_custom_button = get_option('use_custom_button');


	$useCustomButton = (get_option('use_custom_button'))? 'checked="checked"': '';

	$add_cartstyle = get_option('add_cartstyle');
	if(empty($add_cartstyle)) $add_cartstyle = "wp_cart_button";

	$addcart_button_name = get_option('addToCartButtonName');
	if(empty($addcart_button_name)) $addcart_button_name = __("Add to Cart", "wp-ultra-simple-paypal-shopping-cart");

	$checkout_style = get_option('checkout_style');
	if(empty($checkout_style)) $checkout_style = "wp_checkout_button";

	$checkout_button_name = get_option('checkoutButtonName');
	if(empty($checkout_button_name)) $checkout_button_name = __("Checkout", "wp-ultra-simple-paypal-shopping-cart");

// sandbox
	$defaultSandboxChecked = get_option('is_sandbox');
	$defaultSandboxChecked1 = ($defaultSandboxChecked == "1")? "checked": "";
	$defaultSandboxChecked2 = ($defaultSandboxChecked == "1")? "": "checked";

	// debug
	$defaultDebugChecked = get_option('wp_cart_enable_debug');
	$defaultDebugChecked1 = ($defaultDebugChecked == "1")? "checked": "";
	$defaultDebugChecked2 = ($defaultDebugChecked == "1")? "": "checked";

	$emptyCartText = get_option('wp_cart_empty_text');
	$emptyCartAllowDisplay = get_option('wpus_shopping_cart_empty_hide');

	$cart_products_page_url = get_option('cart_products_page_url');

	$cart_checkout_page_url = get_option('cart_checkout_page_url');
	$wpus_shopping_cart_auto_redirect_to_checkout_page = (get_option('wpus_shopping_cart_auto_redirect_to_checkout_page'))? 'checked="checked"': '';

// added txt string
   	$wp_cart_visit_shop_text = get_option('wp_cart_visit_shop_text');
	$wp_cart_update_quantiy_text = get_option('wp_cart_update_quantiy_text');

	$plural_items_text = get_option("plural_items_text");
	$singular_items_text = get_option("singular_items_text");

	$display_product_name = (get_option('display_product_name'))? 'checked="checked"': '';
	$display_product_inline = (get_option('display_product_inline'))? 'checked="checked"': '';
	$display_quantity = (get_option('display_quantity'))? 'checked="checked"': '';

	$subtotal_text = get_option('subtotal_text');
	$shipping_text = get_option('shipping_text');
	$total_text = get_option('total_text');
	$item_name_text = get_option('item_name_text');
	$qualtity_text = get_option('qualtity_text');
	$price_text = get_option('price_text');
	$vat_text = get_option('vat_text');
	$remove_text = get_option('remove_text');

	$wpus_shopping_cart_reset_after_redirection_to_return_page = (get_option('wpus_shopping_cart_reset_after_redirection_to_return_page'))? 'checked="checked"': '';
	$wpus_shopping_cart_collect_address = (get_option('wpus_shopping_cart_collect_address'))? 'checked="checked"': '';
	$wpus_shopping_cart_use_profile_shipping = (get_option('wpus_shopping_cart_use_profile_shipping'))? 'checked="checked"': '';
	$wp_cart_image_hide = (get_option('wpus_shopping_cart_image_hide'))? 'checked="checked"': '';
	$wp_cart_empty_hide = (get_option('wpus_shopping_cart_empty_hide'))? 'checked="checked"': '';
	$wpus_display_link_in_cart = (get_option('wpus_display_link_in_cart'))? 'checked="checked"': '';

	$wpus_display_thumbnail_in_cart = (get_option('wpus_display_thumbnail_in_cart'))? 'checked="checked"': '';
	$wpus_thumbnail_in_cart_width = get_option('wpus_thumbnail_in_cart_width');
	$wpus_thumbnail_in_cart_height = get_option('wpus_thumbnail_in_cart_height');

	$wpus_shopping_cart_items_in_cart_hide = (get_option('wpus_shopping_cart_items_in_cart_hide'))? 'checked="checked"': '';
	$wp_use_aff_platform = (get_option('wp_use_aff_platform'))? 'checked="checked"': '';

	?>

	<!-- WP UI -->
 	<script type="text/javascript" charset="utf8" >
 		jQuery.noConflict();
 		jQuery(function($) {
 			$(document).ready(function() {
 				$( "#tabs" ).tabs();
 			});
 		});
 	</script>

	<!-- WP tab display -->
	<div id="tabs">
	<ul>
		<li><a href="#tabs-3"><span class="showme"><?php _e("Do you like WUSPSC ?", "wp-ultra-simple-paypal-shopping-cart"); ?></span></a></li>
		<li><a href="#tabs-1"><?php _e("Usage", "wp-ultra-simple-paypal-shopping-cart"); ?></a></li>
		<li><a href="#tabs-2"><?php _e("Settings", "wp-ultra-simple-paypal-shopping-cart"); ?></a></li>
		<li><a href="#tabs-4"><?php _e("Discount Code", "wp-ultra-simple-paypal-shopping-cart"); ?></a></li>
		<li><a href="#tabs-6"><?php _e("Support", "wp-ultra-simple-paypal-shopping-cart"); ?></a></li>
		<li><a href="#tabs-5"><?php _e("Readme", "wp-ultra-simple-paypal-shopping-cart"); ?></a></li>
	</ul>

	<div id="tabs-1">
 	<h2><div id="icon-edit-pages" class="icon32"></div><?php _e("WP Ultra Simple Shopping Cart Usage", "wp-ultra-simple-paypal-shopping-cart"); ?> v <?php echo WUSPSC_VERSION; ?></h2>
 	<p><?php _e("For information, updates and detailed documentation, please visit:", "wp-ultra-simple-paypal-shopping-cart"); ?> <a href="https://www.ultra-prod.com/?p=86">ultra-prod.com</a></p>
	<p><?php _e("For support, please use our dedicated forum:", "wp-ultra-simple-paypal-shopping-cart"); ?> <a href="https://www.ultra-prod.com/developpement-support/"><?php _e("WPUSPSC Support Forum", "wp-ultra-simple-paypal-shopping-cart"); ?></a></p>

	<fieldset class="options">
		<p><h4><a href="https://www.paypal.com/fr/mrb/pal=CH4PZVAK2GJAJ"><?php _e("1. create a PayPal account (no cost for basic account)", "wp-ultra-simple-paypal-shopping-cart"); ?></a></h4>

		<p><h4><?php _e("2. Create post or page presenting the product or service and add caddy shortcode in the post. See example and possibilities following:", "wp-ultra-simple-paypal-shopping-cart"); ?></h4>
	<ul>
		<ol>
			<?php _e("To add the 'Add to Cart' button simply add the trigger text to a post or page, next to the product. Replace PRODUCT-NAME and PRODUCT-PRICE with the actual name and price.", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<strong>[wp_cart:<?php _e("PRODUCT-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:<?php _e("PRODUCT-PRICE", "wp-ultra-simple-paypal-shopping-cart"); ?>:end]</strong><br>
			<blockquote><?php _e("eg.", "wp-ultra-simple-paypal-shopping-cart"); ?> [wp_cart:<?php _e("Test Product", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:15.00:end]</blockquote>
		</ol>

		<ol>
			<?php _e("To add the 'Add to Cart' button on you theme's template files, use &lt;?php echo print_wp_cart_button_for_product('PRODUCT-NAME', PRODUCT-PRICE); ?&gt; . Replace PRODUCT-NAME and PRODUCT-PRICE with the actual name and price.", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<blockquote></blockquote>
		</ol>

		<ol>
			<?php _e("To display the numbers of items in cart use &lt;?php echo wpusc_cart_item_qty(); ?&gt; . The string display are set in the plugin's settings.", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<blockquote></blockquote>
		</ol>

		<ol>
			<?php _e("To use variation of the price use the following trigger text:", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<strong>[wp_cart:<?php _e("PRODUCT-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:[<?php _e("VARIATION-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION-LABEL1", "wp-ultra-simple-paypal-shopping-cart"); ?>,<?php _e("VARIATION-PRICE1", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION-LABEL2", "wp-ultra-simple-paypal-shopping-cart"); ?>,<?php _e("VARIATION-PRICE2", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</strong><br>
			<blockquote><?php _e("eg.", "wp-ultra-simple-paypal-shopping-cart"); ?> [wp_cart:<?php _e("Test Product", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:[<?php _e("Size|Small,1.10|Medium,2.10|Large,3.10", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</blockquote>
		</ol>

		<ol>
			<?php _e("To use variation of the price and shipping use the following trigger text:", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<strong>[wp_cart:<?php _e("PRODUCT-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:[<?php _e("VARIATION-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION-LABEL1", "wp-ultra-simple-paypal-shopping-cart"); ?>,<?php _e("VARIATION-PRICE1", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION-LABEL2", "wp-ultra-simple-paypal-shopping-cart"); ?>,<?php _e("VARIATION-PRICE2", "wp-ultra-simple-paypal-shopping-cart"); ?>]:shipping:[<?php _e("Shipping", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION-LABEL1", "wp-ultra-simple-paypal-shopping-cart"); ?>,<?php _e("VARIATION-PRICE1", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</strong><br>
			<blockquote><?php _e("eg.", "wp-ultra-simple-paypal-shopping-cart"); ?> [wp_cart:<?php _e("Test Product", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:[<?php _e("Size|Small,1.10|Medium,2.10|Large,3.10", "wp-ultra-simple-paypal-shopping-cart"); ?>]:shipping:[<?php _e("Shipping|normal,6.50|fast,10.00", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</blockquote>
		</ol>

		<ol>
			<?php _e("To use variation control use the following trigger text:", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<strong>[wp_cart:<?php _e("PRODUCT-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:<?php _e("PRODUCT-PRICE", "wp-ultra-simple-paypal-shopping-cart"); ?>:var1[<?php _e("VARIATION-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION1", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION2", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION3", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</strong><br>
			<blockquote><?php _e("eg.", "wp-ultra-simple-paypal-shopping-cart"); ?> [wp_cart:<?php _e("Test Product", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:15:var1[Size|Small|Medium|Large]:end]</blockquote>
		</ol>

		<ol>
			<?php _e("To use variation control with simple shipping use the following trigger text:", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<strong>[wp_cart:<?php _e("PRODUCT-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:<?php _e("PRODUCT-PRICE", "wp-ultra-simple-paypal-shopping-cart"); ?>:shipping:<?php _e("SHIPPING-COST", "wp-ultra-simple-paypal-shopping-cart"); ?>:var1[<?php _e("VARIATION-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION1", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION2", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION3", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</strong><br>
			<blockquote><?php _e("eg.", "wp-ultra-simple-paypal-shopping-cart"); ?> [wp_cart:<?php _e("Test Product", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:15:shipping:2:var1[<?php _e("Size|Small|Medium|Large", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</blockquote>
		</ol>

		<ol>
			<?php _e("To use multiple variation (unlimited variation) option use the following trigger text:", "wp-ultra-simple-paypal-shopping-cart"); ?><br>
			<strong>[wp_cart:<?php _e("PRODUCT-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:<?php _e("PRODUCT-PRICE", "wp-ultra-simple-paypal-shopping-cart"); ?>:var1[<?php _e("VARIATION-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION1", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION2", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION3", "wp-ultra-simple-paypal-shopping-cart"); ?>]:var2[<?php _e("VARIATION-NAME", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION1", "wp-ultra-simple-paypal-shopping-cart"); ?>|<?php _e("VARIATION2", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</strong><br>
			<blockquote><?php _e("eg.", "wp-ultra-simple-paypal-shopping-cart"); ?> [wp_cart:<?php _e("Test Product", "wp-ultra-simple-paypal-shopping-cart"); ?>:price:15:shipping:2:var1[<?php _e("Size|Small|Medium|Large", "wp-ultra-simple-paypal-shopping-cart"); ?>]:var2[<?php _e("Color|Red|Green", "wp-ultra-simple-paypal-shopping-cart"); ?>]:end]</blockquote>
		</ol>

	</ul>
	</p>
	<p><h4><?php _e("3.a To add the shopping cart to a post or page (eg. checkout page) simply add the shortcode", "wp-ultra-simple-paypal-shopping-cart"); ?></h4>
		<blockquote><blockquote>
			<?php _e("To display checkout to a post or page, simply add the shortcode", "wp-ultra-simple-paypal-shopping-cart"); ?> <strong>&#91;show_wp_shopping_cart&#93;</strong><br>
			<?php _e("Or use the sidebar widget to add the shopping cart to the sidebar.", "wp-ultra-simple-paypal-shopping-cart"); ?>
		</blockquote></blockquote>

		<h4><?php _e('3.b If you want (need) to use the 3 steps cart process do following :', "wp-ultra-simple-paypal-shopping-cart"); ?></h4>

		<blockquote><blockquote>
		<ol>
			<li><?php _e('Create a page with the shortcode', "wp-ultra-simple-paypal-shopping-cart"); ?> &#91;validate_wp_shopping_cart&#93;</li>
			<li><?php _e('Create a page with your form (<a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact form 7</a> is the current better choice) and do the following configuration to your form:', "wp-ultra-simple-paypal-shopping-cart"); ?>
				<ol>
					<li><?php _e('Go to edit your form', "wp-ultra-simple-paypal-shopping-cart"); ?>,</li>
					<li><?php _e('Scroll down and go to "Additional Settings" text area', "wp-ultra-simple-paypal-shopping-cart"); ?>,</li>
					<li><?php _e('Paste : on_sent_ok: "location = \'http://example.com/mycart\';" (replace http://example.com/mycart by your own URL) ', "wp-ultra-simple-paypal-shopping-cart"); ?>,</li>
					<li><?php _e("And create http://example.com/mycart page if not existing, plus past following shortcode inside it", "wp-ultra-simple-paypal-shopping-cart"); ?> &#91;show_wp_shopping_cart&#93;</li>
				</ol>
			</li>
		</ol>
		<br/>
		<?php _e("This will permit to receive user's input before go on paypal final's validation.", "wp-ultra-simple-paypal-shopping-cart"); ?><br/>
		<?php _e("The customer will be redirected to cart with paypal button after successful form submit", "wp-ultra-simple-paypal-shopping-cart"); ?><br/>
		<strong><?php _e('You must use [validate_wp_shopping_cart] shortcode on another page if you want to use the 3 steps process.', "wp-ultra-simple-paypal-shopping-cart"); ?></strong><br/>

		</blockquote></blockquote>
	</p>
	<p>
		<h4><?php _e('4. Good selling ! If you need help, whether it’s a plugin that causing issues, a theme, or just needed help with styling your site or', "wp-ultra-simple-paypal-shopping-cart"); ?> <strong>WPUSPSC</strong> <?php _e('…We can help!', "wp-ultra-simple-paypal-shopping-cart"); ?></h4>

		<blockquote><blockquote>
			<em><strong><a title="Live Ultra Prod Support" href="https://www.ultra-prod.com/live-ultra-prod-staff-support/" target="_blank"><?php _e("Live Support", "wp-ultra-simple-paypal-shopping-cart"); ?></a></strong></em> <?php _e("for all your needs on ", "wp-ultra-simple-paypal-shopping-cart"); ?> <em><strong>WordPress</strong></em> <?php _e("or", "wp-ultra-simple-paypal-shopping-cart"); ?> <strong><em>Prestashop</em></strong>! <?php _e("Support provided by Ultra Prod Staff.", "wp-ultra-simple-paypal-shopping-cart"); ?>
		</blockquote></blockquote>

	</p>
	</fieldset>

	</div>

<?php

$language = __UP_detect_language();

$eceurl = '<a href="http://www.e-ce.biz" target="_blank">www.e-ce.biz</a>';

echo '<div id="tabs-3">
<h2><div id="icon-users" class="icon32"></div>'.( __("Like sell on major marketplaces ?", "wp-ultra-simple-paypal-shopping-cart") ).'</h2>
<div id="ecebiz">
<p>'.( __("Have a look on $eceurl if you like sell on major marketplaces worldwide. We offer fix prices, and a huge expertise to help you to put your products UP to market.", "wp-ultra-simple-paypal-shopping-cart") ).'</p>
<a href="http://www.e-ce.biz"><img src="'.WUSPSC_PLUGIN_IMAGES_URL.'ecebiz.jpg"></a>
</div>
<h2><div id="icon-users" class="icon32"></div>'.( __("Do you like WUSPSC ?", "wp-ultra-simple-paypal-shopping-cart") ).'</h2>
<div id="helpme">
<p><a href="http://wordpress.org/plugins/wp-ultra-simple-paypal-shopping-cart/" target="_blank">'. __("Please, if you like WUSPSC, think to give it a good rating", "wp-ultra-simple-paypal-shopping-cart").'</a>'. __(" and please consider to donate 1$ only or more if you can, &#8364; or &pound; to help me to give time for user&#8217;s support, add new features and upgrades.", "wp-ultra-simple-paypal-shopping-cart").'
	'.( __('After more than 100,000 downloads, <a href="https://www.ultra-prod.com/products-modules-plugin/wp-ultra-simple-paypal-shopping-cart/">only less that 30 users donate</a>.', "wp-ultra-simple-paypal-shopping-cart") ).'
	<div id="donate">
		<form class="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="AXQNVXNYWUEZ4">
		<input type="image" src="'.WUSPSC_CART_URL.'/images/btn_donateCC_LG-'.$language.'.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
</p>
</div>
<p>'. __("Or if you like down-tempo / ambiant / electronic music, you can buy a few tracks from one of my CD on Amazon.", "wp-ultra-simple-paypal-shopping-cart").'</p>
<p>
<ul>
	<li><a href="http://www.amazon.com/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon US</a><li>
	<li><a href="http://www.amazon.co.uk/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon UK</a><li>
	<li><a href="http://www.amazon.de/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon DE</a><li>
	<li><a href="http://www.amazon.fr/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon FR</a><li>
</ul>
<img src="'.WUSPSC_PLUGIN_IMAGES_URL.'41dK4t7R6OL._SL500_SS110_.jpg"><img src="'.WUSPSC_PLUGIN_IMAGES_URL.'41RTkTKGzRL._SL500_SS110_.jpg"><img src="'.WUSPSC_PLUGIN_IMAGES_URL.'51oggSX6F0L._SL500_SS110_.jpg"><img src="'.WUSPSC_PLUGIN_IMAGES_URL.'51xQJmJpwuL._SL500_SS110_.jpg">
</p>
<p>'. __("Thanks a lot for your support !!!", "wp-ultra-simple-paypal-shopping-cart").'<p>
</div>';

echo '<div id="tabs-4">
<h2><div id="icon-edit-comments" class="icon32"></div>'. __("Coupon Code", "wp-ultra-simple-paypal-shopping-cart").'</h2>
<p>'. __("Do you need discount Code feature?", "wp-ultra-simple-paypal-shopping-cart").'<p>
<p>'. __("If the answer is yes, please ask it on ", "wp-ultra-simple-paypal-shopping-cart").'<a target="_blank" href="https://www.ultra-prod.com/developpement-support/">'. __("this Forum thread", "wp-ultra-simple-paypal-shopping-cart").'</a><p>
</div>';

?>

	<div id="tabs-5">
		<h2><div id="icon-edit-comments" class="icon32"></div><?php _e("WP Ultra Simple Shopping Cart Read ME", "wp-ultra-simple-paypal-shopping-cart"); ?></h2>
		<div class="content">
			<pre>
			<?php echo file_get_contents('readme.txt', FILE_USE_INCLUDE_PATH); ?>
			</pre>
		</div>
	</div>

	<div id="tabs-6">
		<h2><div id="icon-edit-comments" class="icon32"></div><?php _e("WP Ultra Simple Shopping Cart Support", "wp-ultra-simple-paypal-shopping-cart"); ?></h2>
		<div class="content">
			<h4><?php _e("Do you need support or new features?", "wp-ultra-simple-paypal-shopping-cart") ?></h4>
			<p><?php _e("Just ask on", "wp-ultra-simple-paypal-shopping-cart") ?> <a target="_blank" href="https://www.ultra-prod.com/developpement-support/"><?php _e("WUSPSC Forum", "wp-ultra-simple-paypal-shopping-cart") ?></a>.<p>

			<h4><?php _e("Do you need quick and direct support?", "wp-ultra-simple-paypal-shopping-cart") ?></h4>
			<p><?php _e("We can provide you \"Live Support\" for all your WordPress or Prestashop needs!", "wp-ultra-simple-paypal-shopping-cart") ?> <a target="_blank" href="https://www.ultra-prod.com/live-ultra-prod-staff-support/"><?php _e("click on UP Live Support", "wp-ultra-simple-paypal-shopping-cart") ?></a>. <?php _e("Support provided by Ultra Prod Staff.", "wp-ultra-simple-paypal-shopping-cart") ?><p>

			<h4><?php _e("Do you like the WP Ultra Simple Paypal Shopping Cart Plugin?", "wp-ultra-simple-paypal-shopping-cart") ?></h4>
			<p><?php _e("Please", "wp-ultra-simple-paypal-shopping-cart") ?> <a target="_blank" href="https://wordpress.org/plugins/wp-ultra-simple-paypal-shopping-cart/"><?php _e("give it a good rating", "wp-ultra-simple-paypal-shopping-cart") ?></a> <?php _e("on Wordpress website", "wp-ultra-simple-paypal-shopping-cart") ?>.</p>
		</div>
	</div>

	<div id="tabs-2">
	<h2><div id="icon-options-general" class="icon32"></div><?php _e("WP Ultra Simple Shopping Cart Settings", "wp-ultra-simple-paypal-shopping-cart"); ?> v <?php echo WUSPSC_VERSION; ?></h2>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="info_update" id="info_update" value="true">

<?php
#qtranslate warning message



if ( function_exists('qtrans_getLanguage') || function_exists('qtranxf_getLanguage') ) 
{ 
	wp_nonce_field(__('delete_my_action'));

	$qtransup = '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"><p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>'.__("<strong>You are using qTranslate or qTranslate-X</strong> if you like to customise the followings strings, please fill the following fields with the qTranslate syntax.", "wp-ultra-simple-paypal-shopping-cart")."<br>".__("Eg", "wp-ultra-simple-paypal-shopping-cart")." : [:en]Sub-total[:fr]Sous-total[:de]Zwischensumme[:es]Total parcial".'</p></div></div>';

	$qtranstalex_msg = '<tr valign="top"><td colspan="2">'.$qtransup.'</td></tr>'; 
} 
	else 
{
	wp_nonce_field(__('delete_my_action'));
	$qtranstalex_msg = "";
}

echo '
<div class="inside">
<table class="form-table">
<!-- Paypal -->
<tr valign="top">
<th scope="row">'. __("Paypal Email Address", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_paypal_email" value="'.$defaultEmail.'" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">'. __("Paypal Sandbox (cart is in test)", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td>Test: <input type="radio" name="is_sandbox" value="1" '.$defaultSandboxChecked1.'/>&nbsp;Production: <input type="radio" name="is_sandbox" value="0" '.$defaultSandboxChecked2.'/><br> '. __('You must open a free developer account to use sandbox for your tests before go live.<br> Go to <a href="https://developer.paypal.com/">https://developer.paypal.com/</a>, register and connect.', "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Paypal Debug output (create local debug log file)", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td>ON: <input type="radio" name="wp_cart_enable_debug" value="1" '.$defaultDebugChecked1.'/>&nbsp;OFF: <input type="radio" name="wp_cart_enable_debug" value="0" '.$defaultDebugChecked2.'/>'.'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Use PayPal Profile Based Shipping", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_use_profile_shipping" value="1" '.$wpus_shopping_cart_use_profile_shipping.'><br>'. __("Check this if you want to use", "wp-ultra-simple-paypal-shopping-cart").' <a href="https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_html_ProfileAndTools#id08A9EF00IQY" target="_blank">'. __("PayPal profile based shipping", "wp-ultra-simple-paypal-shopping-cart").'</a>. '. __("Using this will ignore any other shipping options that you have specified in this plugin.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Must Collect Shipping Address on PayPal", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_collect_address" value="1" '.$wpus_shopping_cart_collect_address.'><br>'. __("If checked the customer will be forced to enter a shipping address on PayPal when checking out.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<!-- Settings -->

<tr valign="top">
<th scope="row">'. __("Base Shipping Cost", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_base_shipping_cost" value="'.$baseShipping.'" size="5"> <br>'. __("This is the base shipping cost that will be added to the total of individual products shipping cost. Put 0 if you do not want to charge shipping cost or use base shipping cost.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Free Shipping for Orders Over", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_free_shipping_threshold" value="'.$cart_free_shipping_threshold.'" size="5"> <br>'. __("When a customer orders more than this amount he/she will get free shipping. Leave empty if you do not want to use it.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Shipping fee per item", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_shipping_per_items" value="1" '.$wpus_shopping_cart_shipping_per_items.'><br>'. __("By default, shipping fee is multiply by the item's quantity added. If ticked only 1 shipping fee is added per items group.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Free Shipping", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="display_free_shipping" value="1" '.$displayFreeShipping.'><br>'. __(" If ticked, display a shipping free message on cart.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Currency", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_payment_currency" value="'.$defaultCurrency.'" maxlength="3" size="4"> ('. __("e.g.", "wp-ultra-simple-paypal-shopping-cart").' USD, EUR, GBP, AUD)'. __('Full list on <a target="_blank" href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes">PayPal website</a>', "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>
<tr valign="top">
<th scope="row">'. __("Currency Symbol", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_currency_symbol" value="'.$defaultSymbol.'" size="2" style=""> ('. __("e.g.", "wp-ultra-simple-paypal-shopping-cart").' $, &#163;, &#8364;)
</td>
</tr>
<tr valign="top">
<th scope="row">'. __("Currency display", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td>Is the currency symbol is displayed befor or after the price ? <input type="radio" name="cart_currency_symbol_order" value="1" '.$defaultSymbolOrderChecked1.'/> Before or <input type="radio" name="cart_currency_symbol_order" value="2" '.$defaultSymbolOrderChecked2.'/> After
</td>
</tr>
<tr valign="top">
<th scope="row">'. __("Item global VAT", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="display_vat" value="'.$display_vat.'" size="5">%<br>'. __("Add VAT rate. The VAT must be a percentage eg. 19.60. Leave empty to disable it.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Custom buttons", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="use_custom_button" value="1" '.$useCustomButton.'><br>'. __(" If ticked, use following custom id & class on button.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>
<tr valign="top">
<th scope="row">'. __("Add to Cart button text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="addToCartButtonName" value="'.$addcart_button_name.'" size="100"><br>'. __("To use a customized 'add to cart' button text, fill with a text or leave empty for using image as button background. Don't forget to add background-image to your theme's style.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>
<tr valign="top">
<th scope="row">'. __("Cart button id & class name (without the dash or dot)", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="add_cartstyle" value="'.$add_cartstyle.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Checkout button text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="checkoutButtonName" value="'.$checkout_button_name.'" size="100"><br>'. __("To use a customized 'checkout' button text, fill with a text or leave empty for using image as button background. Don't forget to add background-image to your theme's style.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>
<tr valign="top">
<th scope="row">'. __("Checkout button id & class name (without the dash or dot)", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="checkout_style" value="'.$checkout_style.'" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">'. __("Display product name", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="display_product_name" value="1" '.$display_product_name.'>'. __(" If ticked, display the product's name, otherwise hide it", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Display Product Options Inline", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="display_product_inline" value="1" '.$display_product_inline.'>'. __(" If ticked, display the product input without line break, otherwise it display each input to a new line.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Display quantity field", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="display_quantity" value="1" '.$display_quantity.'>'. __(" If ticked, display the quantity field to choose quantity before add to cart, otherwise quantity is 1.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Products Page URL", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_products_page_url" value="'.$cart_products_page_url.'" size="100"><br>'. __("This is the URL of your products page if you have any. If used, the shopping cart widget will display a link to this page when cart is empty", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __('Display Products URL in cart', "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_display_link_in_cart" value="1" '.$wpus_display_link_in_cart.'>'. __("If ticked, the product's link will not be display in cart. Activate it if you are using a page or a post for each product.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __('Display thumbnail in cart', "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_display_thumbnail_in_cart" value="1" '.$wpus_display_thumbnail_in_cart.'>'. __("If ticked, the product's thumbnail will not be display in cart. Activate it if you are using a page or a post for each product.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __('Thumbnail size', "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" size="4" name="wpus_thumbnail_in_cart_width" value="'.$wpus_thumbnail_in_cart_width.'"> px / <input type="text" size="4" name="wpus_thumbnail_in_cart_height" value="'.$wpus_thumbnail_in_cart_height.'"> px '. __("Size in pixel of product's thumbnail display in cart. Displayed if product's thumbnail are activate and if you'r using a page or a post for each product.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __('Hide "Cart Empty" message', "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_empty_hide" value="1" '.$wp_cart_empty_hide.'><br>'. __("If ticked, the shopping cart empty message on page/post or widget will not be display.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __('Hide items count display message', "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_items_in_cart_hide" value="1" '.$wpus_shopping_cart_items_in_cart_hide.'><br>'. __("If ticked, the items in cart count message on page/post or widget will not be display.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Hide Shopping Cart Image", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_image_hide" value="1" '.$wp_cart_image_hide.'><br>'. __("If ticked the shopping cart image will not be shown.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

'.$qtranstalex_msg.'

<tr valign="top">
<th scope="row">'. __("Shopping Cart title", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="wp_cart_title" value="'.$title.'" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">'. __("Text/Image to Show When Cart Empty", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="wp_cart_empty_text" value="'.$emptyCartText.'" size="60"><br>'. __("You can either enter plain text or the URL of an image that you want to show when the shopping cart is empty", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __('Singular "product in your cart" text', "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="singular_items_text" value="'.$singular_items_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __('Plural "products in your cart" text', "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="plural_items_text" value="'.$plural_items_text.'" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">'. __("Subtotal text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="subtotal_text" value="'.$subtotal_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Shipping text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="shipping_text" value="'.$shipping_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Total text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="total_text" value="'.$total_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Item name text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="item_name_text" value="'.$item_name_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Quantity text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="qualtity_text" value="'.$qualtity_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Price text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="price_text" value="'.$price_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("VAT text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="vat_text" value="'.$vat_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Item count text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="item_qty_string" value="'.$itemQtyString.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("No item in cart text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="no_item_in_cart_string" value="'.$noItemInCartString.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Remove text", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="remove_text" value="'.$remove_text.'" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">'. __("Products page URL title", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="wp_cart_visit_shop_text" value="'.$wp_cart_visit_shop_text.'" size="100"></td>
</tr>

<tr valign="top">
<th scope="row">'. __("Return URL", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_return_from_paypal_url" value="'.$return_url.'" size="100"><br>'. __("This is the URL the customer will be redirected to after a successful payment", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Automatic redirection to checkout page", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_auto_redirect_to_checkout_page" value="1" '.$wpus_shopping_cart_auto_redirect_to_checkout_page.'>
 '. __("Checkout Page URL", "wp-ultra-simple-paypal-shopping-cart").': <input type="text" name="cart_checkout_page_url" value="'.$cart_checkout_page_url.'" size="60">
<br>'. __("If checked the visitor will be redirected to the Checkout page after a product is added to the cart. You must enter a URL in the Checkout Page URL field for this to work.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("Reset Cart After Redirection to Return Page", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="checkbox" name="wpus_shopping_cart_reset_after_redirection_to_return_page" value="1" '.$wpus_shopping_cart_reset_after_redirection_to_return_page.'>
<br>'. __("If checked the shopping cart will be reset when the customer lands on the return URL (Thank You) page.", "wp-ultra-simple-paypal-shopping-cart").'</td>
</tr>

<tr valign="top">
<th scope="row">'. __("3 steps cart form URL", "wp-ultra-simple-paypal-shopping-cart").'</th>
<td><input type="text" name="cart_validate_url" value="'.$cart_validate_url.'" size="100"><p>'. __("Configure this URL if you like to have a form as step 2, before the final paypal cart (use [validate_wp_shopping_cart] shortcod on th first step cart page). Leave empty if you not need this.", "wp-ultra-simple-paypal-shopping-cart").'<br/>
	'. __('You can install and use <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact form 7</a> for example and set your form with the following informations.', "wp-ultra-simple-paypal-shopping-cart").':
	<ol>
		<li>'. __('Go to edit your form', "wp-ultra-simple-paypal-shopping-cart").',</li>
		<li>'. __('Scroll down and go to "Additional Settings" text area', "wp-ultra-simple-paypal-shopping-cart").',</li>
		<li>'. __("Paste => on_sent_ok: \"location = 'http://example.com/mycart';\" (replace http://example.com/mycart by your own URL) ", "wp-ultra-simple-paypal-shopping-cart").',</li>
		<li>'. __("And create http://example.com/mycart page if not existing, plus past [show_wp_shopping_cart] shortcode", "wp-ultra-simple-paypal-shopping-cart").'</li>
	</ol>
	'. __("This will permit to receive user's input before go on paypal final's validation.", "wp-ultra-simple-paypal-shopping-cart").'<br/>
	'. __("The customer will be redirected to cart with paypal button after successful form submit", "wp-ultra-simple-paypal-shopping-cart").'</p>
	</td>
</tr>

</table>

</div>
	<div class="submit">
		<input type="submit" class="button-primary" name="info_update" value="'. __("Update Options &raquo;", "wp-ultra-simple-paypal-shopping-cart").'">
	</div>
 </form>
 </div>
</div>';

  echo  __("Like the WP Ultra Simple Paypal Shopping Cart Plugin?", "wp-ultra-simple-paypal-shopping-cart").' <a href="https://wordpress.org/plugins/wp-ultra-simple-paypal-shopping-cart/" target="_blank">'. __("Give it a good rating", "wp-ultra-simple-paypal-shopping-cart").'</a>';
}

function wp_cart_options() {
	 echo '<div class="wrap"><h2>'. __("WP Ultra simple Paypal Cart Options", "wp-ultra-simple-paypal-shopping-cart").'</h2>';
	 echo '<div id="poststuff"><div id="post-body">';
	 show_wp_cart_options_page();
	 echo '</div></div>';
	 echo '</div>';
}

// Display The Options Page
function wp_cart_options_page () {
	 add_options_page( __("WP Ultra simple Paypal Cart", "wp-ultra-simple-paypal-shopping-cart"), __("Ultra simple Cart", "wp-ultra-simple-paypal-shopping-cart"), 'manage_options', __FILE__, 'wp_cart_options');
}

// Insert the options page to the admin menu
add_action('admin_menu','wp_cart_options_page');

?>
