<?php

/**
 * Supersonique Studio WPUSSC Admin Options
 * Version: v1.5
 * 
 * This file handles the admin settings and configuration options 
 * for the WordPress Ultra Simple PayPal Shopping Cart plugin.
 */

/**
 * License Information
 * This program is free software; you can redistribute it
 * under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// Load plugin text domain for internationalization
load_plugin_textdomain(
    "wp-ultra-simple-paypal-shopping-cart",
    false,
    WUSPSC_PLUGIN_DIRNAME . "/languages"
);

/**
 * Initialize default plugin options
 * These add_option() calls set up the default configuration values for the plugin.
 * If an option already exists, add_option() will not overwrite it.
 */

// Cart display settings
add_option(
    "wp_cart_title",
    __("Your Shopping Cart", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "wp_cart_empty_text",
    __("Your cart is empty", "wp-ultra-simple-paypal-shopping-cart")
);

// Cart behavior options
add_option("wpus_shopping_cart_empty_hide", "1"); // Hide empty cart message
add_option("wp_cart_enable_debug", "0"); // Debug mode disabled by default
add_option("wpus_shopping_cart_shipping_per_items", "0"); // Shipping calculation method
add_option("wpus_display_link_in_cart", "1"); // Show product links in cart
add_option("wpus_display_thumbnail_in_cart", "0"); // Don't show thumbnails by default

// Thumbnail size settings
add_option("wpus_thumbnail_in_cart_width", "32");
add_option("wpus_thumbnail_in_cart_height", "32");

// Navigation and user interaction text
add_option(
    "wp_cart_visit_shop_text",
    __("Visit The Shop", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "wp_cart_update_quantiy_text",
    __(
        "Hit enter or click on reload Che to submit the updated quantity please.",
        "wp-ultra-simple-paypal-shopping-cart"
    )
);

// Item count display settings
add_option("wpus_shopping_cart_items_in_cart_hide", "1");
add_option(
    "plural_items_text",
    __("products in your cart", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "singular_items_text",
    __("product in your cart", "wp-ultra-simple-paypal-shopping-cart")
);

// Button styling options
add_option("add_cartstyle", "wp_cart_button"); // CSS class for add to cart button
add_option("checkout_style", "wp_checkout_button"); // CSS class for checkout button

// Product display options
add_option("display_product_name", "0"); // Hide product name by default
add_option("display_product_inline", "0"); // Display products in block format
add_option("display_quantity", "0"); // Hide quantity selector by default

// Cart table header texts (customizable labels)
add_option(
    "subtotal_text",
    __("Subtotal", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "shipping_text",
    __("Shipping", "wp-ultra-simple-paypal-shopping-cart")
);
add_option("total_text", __("Total", "wp-ultra-simple-paypal-shopping-cart"));
add_option(
    "item_name_text",
    __("Item Name", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "qualtity_text",
    __("Quantity", "wp-ultra-simple-paypal-shopping-cart")
);
add_option("price_text", __("Price", "wp-ultra-simple-paypal-shopping-cart"));
add_option("vat_text", __("VAT", "wp-ultra-simple-paypal-shopping-cart"));
add_option("remove_text", __("Remove", "wp-ultra-simple-paypal-shopping-cart"));

// Button text settings
add_option(
    "checkoutButtonName",
    __("Remove", "wp-ultra-simple-paypal-shopping-cart")
);

// Currency and formatting options
add_option("add_cartstyle", "");
add_option("cart_currency_symbol_order", "1"); // 1 = before price, 2 = after price

// Cart item quantity display strings
add_option("item_qty_string", "%d item%s in your cart");
add_option("no_item_in_cart_string", "Cart empty");

// PayPal return URL (where customers go after payment)
add_option("cart_return_from_paypal_url", get_bloginfo("wpurl"));

// Discount code functionality options
add_option("wpussc_discount_enabled", "1"); // Enable discount codes by default
add_option(
    "wpussc_discount_label",
    __("Discount", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "wpussc_discount_code_label",
    __("Discount code", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "wpussc_discount_button_text",
    __("Apply", "wp-ultra-simple-paypal-shopping-cart")
);
add_option(
    "wpussc_discount_invalid_code_message",
    __("Invalid discount code", "wp-ultra-simple-paypal-shopping-cart")
);

/**
 * Helper function to check if a POST key is defined and sanitize it
 * This function is used for PHP 8 compatibility to avoid undefined array key warnings
 * 
 * @param string $key The POST key to check
 * @return string Sanitized value or empty string if not set
 */
function isKeyDefined($key)
{
    return isset($_POST["wpus_shopping_cart_collect_address"])
        ? sanitize_text_field($_POST["wpus_shopping_cart_collect_address"])
        : "";
}

/**
 * Main function to display and handle the plugin options page
 * This function processes form submissions and renders the admin interface
 */
function show_wp_cart_options_page()
{
    // Handle form submission and update options
    if (isset($_POST["info_update"])) {
        // Verify nonce for security
        $retrieved_nonce = $_POST["_wpnonce"];

        if (!wp_verify_nonce($retrieved_nonce, "delete_my_action")) {
            die(__("Failed security check"));
        }

        // Process and update all form fields with PHP 8 compatibility

        // Address collection setting
        $wpus_shopping_cart_collect_address = "";
        if (isKeyDefined("wpus_shopping_cart_collect_address")) {
            $wpus_shopping_cart_collect_address = 'checked="checked"';
        }
        update_option(
            "wpus_shopping_cart_collect_address",
            $wpus_shopping_cart_collect_address
        );

        // PayPal profile shipping setting
        $wpus_shopping_cart_use_profile_shipping = "";
        if (isKeyDefined("wpus_shopping_cart_use_profile_shipping")) {
            $wpus_shopping_cart_use_profile_shipping = 'checked="checked"';
        }
        update_option(
            "wpus_shopping_cart_use_profile_shipping",
            $wpus_shopping_cart_use_profile_shipping
        );

        // Shipping per item calculation method
        $wpus_shopping_cart_shipping_per_items = 1;
        if (isset($_POST["wpus_shopping_cart_shipping_per_items"])) {
            $wpus_shopping_cart_shipping_per_items = intval(
                $_POST["wpus_shopping_cart_shipping_per_items"]
            );
        }
        update_option(
            "wpus_shopping_cart_shipping_per_items",
            $wpus_shopping_cart_shipping_per_items
        );

        // Free shipping display setting
        $display_free_shipping = 0;
        if (isset($_POST["display_free_shipping"])) {
            $display_free_shipping = intval($_POST["display_free_shipping"]);
        }
        update_option("display_free_shipping", $display_free_shipping);

        // Custom button usage setting
        $use_custom_button = "";
        if (isKeyDefined("use_custom_button")) {
            $use_custom_button = 'checked="checked"';
        }
        update_option("use_custom_button", $use_custom_button);

        // Display thumbnails in cart setting
        $wpus_display_thumbnail_in_cart = "";
        if (isKeyDefined("wpus_display_thumbnail_in_cart")) {
            $wpus_display_thumbnail_in_cart = 'checked="checked"';
        }
        update_option(
            "wpus_display_thumbnail_in_cart",
            $wpus_display_thumbnail_in_cart
        );

        // Cart update quantity text
        $wp_cart_update_quantiy_text = "";
        if (isKeyDefined("wp_cart_update_quantiy_text")) {
            $wp_cart_update_quantiy_text = (string) sanitize_text_field(
                $_POST["wp_cart_update_quantiy_text"]
            );
        }
        update_option(
            "wp_cart_update_quantiy_text",
            $wp_cart_update_quantiy_text
        );

        // Display quantity field setting
        $display_quantity = "";
        if (isKeyDefined("display_quantity")) {
            $display_quantity = (string) sanitize_text_field(
                $_POST["display_quantity"]
            );
        }
        update_option("display_quantity", $display_quantity);

        // Auto redirect to checkout page setting
        $wpus_shopping_cart_auto_redirect_to_checkout_page = "";
        if (isKeyDefined("wpus_shopping_cart_auto_redirect_to_checkout_page")) {
            $wpus_shopping_cart_auto_redirect_to_checkout_page =
                'checked="checked"';
        }
        update_option(
            "wpus_shopping_cart_auto_redirect_to_checkout_page",
            $wpus_shopping_cart_auto_redirect_to_checkout_page
        );

        // Reset cart after return from PayPal setting
        $wpus_shopping_cart_reset_after_redirection_to_return_page = "";
        if (
            isKeyDefined(
                "wpus_shopping_cart_reset_after_redirection_to_return_page"
            )
        ) {
            $wpus_shopping_cart_reset_after_redirection_to_return_page =
                'checked="checked"';
        }
        update_option(
            "wpus_shopping_cart_reset_after_redirection_to_return_page",
            $wpus_shopping_cart_reset_after_redirection_to_return_page
        );

        // Hide shopping cart image setting
        $wpus_shopping_cart_image_hide = "";
        if (isKeyDefined("wpus_shopping_cart_image_hide")) {
            $wpus_shopping_cart_image_hide = 'checked="checked"';
        }
        update_option(
            "wpus_shopping_cart_image_hide",
            $wpus_shopping_cart_image_hide
        );

        // Affiliate platform usage setting
        $wp_use_aff_platform = "";
        if (isKeyDefined("wp_use_aff_platform")) {
            $wp_use_aff_platform = 'checked="checked"';
        }
        update_option("wp_use_aff_platform", $wp_use_aff_platform);

        // Product name display setting
        $display_product_name = "";
        if (isKeyDefined("display_product_name")) {
            $display_product_name = (string) sanitize_text_field(
                $_POST["display_product_name"]
            );
        }
        update_option("display_product_name", $display_product_name);

        // Product inline display setting
        $display_product_inline = "";
        if (isKeyDefined("display_product_inline")) {
            $display_product_inline = (string) sanitize_text_field(
                $_POST["display_product_inline"]
            );
        }
        update_option("display_product_inline", $display_product_inline);

        // Update core payment and currency settings
        update_option(
            "cart_payment_currency",
            (string) sanitize_text_field($_POST["cart_payment_currency"])
        );
        update_option(
            "cart_currency_symbol",
            (string) sanitize_text_field($_POST["cart_currency_symbol"])
        );
        update_option(
            "cart_currency_symbol_order",
            (string) sanitize_text_field($_POST["cart_currency_symbol_order"])
        );
        update_option(
            "cart_base_shipping_cost",
            (string) sanitize_text_field($_POST["cart_base_shipping_cost"])
        );
        update_option(
            "cart_free_shipping_threshold",
            (string) sanitize_text_field($_POST["cart_free_shipping_threshold"])
        );

        // PayPal email and cart title
        update_option(
            "cart_paypal_email",
            (string) sanitize_text_field($_POST["cart_paypal_email"])
        );
        update_option(
            "wp_cart_title",
            (string) sanitize_text_field($_POST["wp_cart_title"])
        );

        // Handle discount code settings update (conditional)
        // Only update discount code settings if we explicitly added them to this form
        if (isset($_POST["info_update"]) && $_POST["info_update"] == "true") {
            // Don't update the discount settings from the main settings form
            // This prevents overwriting discount settings when submitting the main settings form
        } else {
            // If we're specifically saving discount settings
            if (isset($_POST["wpussc_discount_enabled"])) {
                update_option(
                    "wpussc_discount_enabled",
                    isset($_POST["wpussc_discount_enabled"]) ? "1" : ""
                );
            }
            if (isset($_POST["wpussc_discount_label"])) {
                update_option(
                    "wpussc_discount_label",
                    (string) sanitize_text_field(
                        $_POST["wpussc_discount_label"]
                    )
                );
            }
            if (isset($_POST["wpussc_discount_code_label"])) {
                update_option(
                    "wpussc_discount_code_label",
                    (string) sanitize_text_field(
                        $_POST["wpussc_discount_code_label"]
                    )
                );
            }
            if (isset($_POST["wpussc_discount_button_text"])) {
                update_option(
                    "wpussc_discount_button_text",
                    (string) sanitize_text_field(
                        $_POST["wpussc_discount_button_text"]
                    )
                );
            }
            if (isset($_POST["wpussc_discount_invalid_code_message"])) {
                update_option(
                    "wpussc_discount_invalid_code_message",
                    (string) sanitize_text_field(
                        $_POST["wpussc_discount_invalid_code_message"]
                    )
                );
            }
        }

        // VAT display setting
        update_option(
            "display_vat",
            (string) sanitize_text_field($_POST["display_vat"])
        );

        // Button styling and text options
        update_option(
            "add_cartstyle",
            (string) sanitize_text_field($_POST["add_cartstyle"])
        );
        update_option(
            "addToCartButtonName",
            (string) sanitize_text_field($_POST["addToCartButtonName"])
        );
        update_option(
            "checkout_style",
            (string) sanitize_text_field($_POST["checkout_style"])
        );
        update_option(
            "checkoutButtonName",
            (string) sanitize_text_field($_POST["checkoutButtonName"])
        );

        // Cart display and behavior settings
        update_option(
            "wp_cart_empty_text",
            (string) sanitize_text_field($_POST["wp_cart_empty_text"])
        );
        update_option(
            "wpus_shopping_cart_empty_hide",
            sanitize_text_field($_POST["wpus_shopping_cart_empty_hide"]) != ""
                ? 'checked="checked"'
                : ""
        );
        update_option(
            "wpus_display_link_in_cart",
            sanitize_text_field($_POST["wpus_display_link_in_cart"]) != ""
                ? 'checked="checked"'
                : ""
        );

        // Thumbnail size settings
        update_option(
            "wpus_thumbnail_in_cart_width",
            intval($_POST["wpus_thumbnail_in_cart_width"])
        );
        update_option(
            "wpus_thumbnail_in_cart_height",
            intval($_POST["wpus_thumbnail_in_cart_height"])
        );

        // URL configuration settings
        update_option(
            "cart_validate_url",
            (string) sanitize_text_field($_POST["cart_validate_url"])
        );
        update_option(
            "cart_return_from_paypal_url",
            (string) sanitize_text_field($_POST["cart_return_from_paypal_url"])
        );
        update_option(
            "cart_products_page_url",
            (string) sanitize_text_field($_POST["cart_products_page_url"])
        );

        // Navigation text settings
        update_option(
            "wp_cart_visit_shop_text",
            (string) sanitize_text_field($_POST["wp_cart_visit_shop_text"])
        );

        // Item quantity and display texts
        update_option(
            "plural_items_text",
            (string) sanitize_text_field($_POST["plural_items_text"])
        );
        update_option(
            "singular_items_text",
            (string) sanitize_text_field($_POST["singular_items_text"])
        );
        update_option(
            "wpus_shopping_cart_items_in_cart_hide",
            (string) sanitize_text_field(
                $_POST["wpus_shopping_cart_items_in_cart_hide"]
            )
        );

        // Cart table column headers and labels
        update_option(
            "subtotal_text",
            (string) sanitize_text_field($_POST["subtotal_text"])
        );
        update_option(
            "shipping_text",
            (string) sanitize_text_field($_POST["shipping_text"])
        );
        update_option(
            "total_text",
            (string) sanitize_text_field($_POST["total_text"])
        );
        update_option(
            "item_name_text",
            (string) sanitize_text_field($_POST["item_name_text"])
        );
        update_option(
            "qualtity_text",
            (string) sanitize_text_field($_POST["qualtity_text"])
        );
        update_option(
            "price_text",
            (string) sanitize_text_field($_POST["price_text"])
        );
        update_option(
            "vat_text",
            (string) sanitize_text_field($_POST["vat_text"])
        );
        
        // Note: These options are duplicated in the original code - keeping for compatibility
        update_option(
            "item_name_text",
            (string) sanitize_text_field($_POST["item_name_text"])
        );
        update_option(
            "qualtity_text",
            (string) sanitize_text_field($_POST["qualtity_text"])
        );
        update_option(
            "vat_text",
            (string) sanitize_text_field($_POST["vat_text"])
        );
        update_option(
            "remove_text",
            (string) sanitize_text_field($_POST["remove_text"])
        );

        // Cart item quantity display strings
        update_option(
            "item_qty_string",
            (string) sanitize_text_field($_POST["item_qty_string"])
        );
        update_option(
            "no_item_in_cart_string",
            (string) sanitize_text_field($_POST["no_item_in_cart_string"])
        );

        // PayPal environment settings
        update_option(
            "is_sandbox",
            (string) sanitize_text_field($_POST["is_sandbox"])
        );

        // Debug mode setting
        update_option(
            "wp_cart_enable_debug",
            (string) sanitize_text_field($_POST["wp_cart_enable_debug"])
        );

        // Checkout page URL
        update_option(
            "cart_checkout_page_url",
            (string) sanitize_text_field($_POST["cart_checkout_page_url"])
        );

        // Display success message after saving
        echo '<div id="message" class="updated fade">';
        echo "<p><strong>" .
            __("Options Updated!", "wp-ultra-simple-paypal-shopping-cart") .
            "</strong></p></div>";
    }

    // Initialize default values for form fields
    // Get payment currency (default to USD if not set)
    $defaultCurrency = get_option("cart_payment_currency");
    if (empty($defaultCurrency)) {
        $defaultCurrency = __("USD", "wp-ultra-simple-paypal-shopping-cart");
    }

    // Get currency symbol (default to $ if not set)
    $defaultSymbol = get_option("cart_currency_symbol");
    if (empty($defaultSymbol)) {
        $defaultSymbol = __("$", "wp-ultra-simple-paypal-shopping-cart");
    }

    // Currency symbol position settings
    $defaultSymbolOrder = get_option("cart_currency_symbol_order");
    if (empty($defaultSymbolOrder)) {
        $defaultSymbolOrder = "1"; // Default to before price
    }
    
    // Set radio button checked states based on symbol order
    if ($defaultSymbolOrder == "1") {
        $defaultSymbolOrderChecked1 = "checked"; // Before price
        $defaultSymbolOrderChecked2 = "";
    } elseif ($defaultSymbolOrder == "2") {
        $defaultSymbolOrderChecked1 = "";
        $defaultSymbolOrderChecked2 = "checked"; // After price
    } else {
        $defaultSymbolOrderChecked1 = "";
        $defaultSymbolOrderChecked2 = "";
    }

    // Shipping cost settings
    $baseShipping = get_option("cart_base_shipping_cost");
    if (empty($baseShipping)) {
        $baseShipping = 0;
    }

    $cart_free_shipping_threshold = get_option("cart_free_shipping_threshold");
    $display_vat = get_option("display_vat");

    // PayPal email (default to site admin email if not set)
    $defaultEmail = get_option("cart_paypal_email");
    if (empty($defaultEmail)) {
        $defaultEmail = get_bloginfo("admin_email");
    }

    // URL settings
    $return_url = get_option("cart_return_from_paypal_url");
    $cart_validate_url = get_option("cart_validate_url");

    // Cart title
    $title = get_option("wp_cart_title");

    // Item quantity display strings
    $itemQtyString = get_option("item_qty_string");
    if (empty($itemQtyString)) {
        $itemQtyString = __(
            "%d item%s in your cart",
            "wp-ultra-simple-paypal-shopping-cart"
        );
    }
    $noItemInCartString = get_option("no_item_in_cart_string");
    if (empty($noItemInCartString)) {
        $noItemInCartString = __(
            "Cart empty",
            "wp-ultra-simple-paypal-shopping-cart"
        );
    }

    // Free shipping display setting
    $option_display_free_shipping = get_option("display_free_shipping");
    $displayFreeShipping = !empty($display_free_shipping)
        ? 'checked="checked"'
        : "";

    // Shipping per item setting
    $option_wpus_shopping_cart_shipping_per_items = get_option(
        "wpus_shopping_cart_shipping_per_items"
    );
    $wpus_shopping_cart_shipping_per_items = !empty(
        $option_wpus_shopping_cart_shipping_per_items
    )
        ? 'checked="checked"'
        : "";

    // Custom button settings
    $use_custom_button = get_option("use_custom_button");
    $useCustomButton = get_option("use_custom_button")
        ? 'checked="checked"'
        : "";

    // Button styling options
    $add_cartstyle = get_option("add_cartstyle");
    if (empty($add_cartstyle)) {
        $add_cartstyle = "wp_cart_button"; // Default CSS class
    }

    $addcart_button_name = get_option("addToCartButtonName");
    if (empty($addcart_button_name)) {
        $addcart_button_name = __(
            "Add to Cart",
            "wp-ultra-simple-paypal-shopping-cart"
        );
    }

    $checkout_style = get_option("checkout_style");
    if (empty($checkout_style)) {
        $checkout_style = "wp_checkout_button"; // Default CSS class
    }

    $checkout_button_name = get_option("checkoutButtonName");
    if (empty($checkout_button_name)) {
        $checkout_button_name = __(
            "Checkout",
            "wp-ultra-simple-paypal-shopping-cart"
        );
    }

    // PayPal sandbox mode settings (for testing vs production)
    $defaultSandboxChecked = get_option("is_sandbox");
    $defaultSandboxChecked1 = $defaultSandboxChecked == "1" ? "checked" : ""; // Test mode
    $defaultSandboxChecked2 = $defaultSandboxChecked == "1" ? "" : "checked"; // Production mode

    // Debug mode settings
    $defaultDebugChecked = get_option("wp_cart_enable_debug");
    $defaultDebugChecked1 = $defaultDebugChecked == "1" ? "checked" : ""; // Debug ON
    $defaultDebugChecked2 = $defaultDebugChecked == "1" ? "" : "checked"; // Debug OFF

    // Cart display settings
    $emptyCartText = get_option("wp_cart_empty_text");
    $emptyCartAllowDisplay = get_option("wpus_shopping_cart_empty_hide");

    $cart_products_page_url = get_option("cart_products_page_url");
    $cart_checkout_page_url = get_option("cart_checkout_page_url");
    
    // Auto-redirect to checkout setting
    $wpus_shopping_cart_auto_redirect_to_checkout_page = get_option(
        "wpus_shopping_cart_auto_redirect_to_checkout_page"
    )
        ? 'checked="checked"'
        : "";

    // Text strings for various cart elements
    $wp_cart_visit_shop_text = get_option("wp_cart_visit_shop_text");
    $wp_cart_update_quantiy_text = get_option("wp_cart_update_quantiy_text");
    $plural_items_text = get_option("plural_items_text");
    $singular_items_text = get_option("singular_items_text");

    // Product display options
    $display_product_name = get_option("display_product_name")
        ? 'checked="checked"'
        : "";
    $display_product_inline = get_option("display_product_inline")
        ? 'checked="checked"'
        : "";
    $display_quantity = get_option("display_quantity")
        ? 'checked="checked"'
        : "";

    // Cart table header texts
    $subtotal_text = get_option("subtotal_text");
    $shipping_text = get_option("shipping_text");
    $total_text = get_option("total_text");
    $item_name_text = get_option("item_name_text");
    $qualtity_text = get_option("qualtity_text");
    $price_text = get_option("price_text");
    $vat_text = get_option("vat_text");
    $remove_text = get_option("remove_text");

    // Additional cart behavior settings
    $wpus_shopping_cart_reset_after_redirection_to_return_page = get_option(
        "wpus_shopping_cart_reset_after_redirection_to_return_page"
    )
        ? 'checked="checked"'
        : "";
    $wpus_shopping_cart_collect_address = get_option(
        "wpus_shopping_cart_collect_address"
    )
        ? 'checked="checked"'
        : "";
    $wpus_shopping_cart_use_profile_shipping = get_option(
        "wpus_shopping_cart_use_profile_shipping"
    )
        ? 'checked="checked"'
        : "";
    $wp_cart_image_hide = get_option("wpus_shopping_cart_image_hide")
        ? 'checked="checked"'
        : "";
    $wp_cart_empty_hide = get_option("wpus_shopping_cart_empty_hide")
        ? 'checked="checked"'
        : "";
    $wpus_display_link_in_cart = get_option("wpus_display_link_in_cart")
        ? 'checked="checked"'
        : "";

    // Thumbnail display settings
    $wpus_display_thumbnail_in_cart = get_option(
        "wpus_display_thumbnail_in_cart"
    )
        ? 'checked="checked"'
        : "";
    $wpus_thumbnail_in_cart_width = get_option("wpus_thumbnail_in_cart_width");
    $wpus_thumbnail_in_cart_height = get_option(
        "wpus_thumbnail_in_cart_height"
    );

    // Final display settings
    $wpus_shopping_cart_items_in_cart_hide = get_option(
        "wpus_shopping_cart_items_in_cart_hide"
    )
        ? 'checked="checked"'
        : "";
    $wp_use_aff_platform = get_option("wp_use_aff_platform")
        ? 'checked="checked"'
        : "";
    ?>

	<!-- 
	WordPress UI Scripts for Tabbed Interface
	This JavaScript initializes the jQuery UI tabs functionality for the admin interface
	-->
	<script type="text/javascript" charset="utf8">
		jQuery.noConflict();
		jQuery(function($) {
			$(document).ready(function() {
				// Initialize jQuery UI tabs for the admin options interface
				$("#tabs").tabs();
			});
		});
	</script>

	<!-- 
	Main Admin Interface with Tabbed Navigation
	The interface is organized into several tabs for better user experience:
	- Usage: Instructions and examples
	- Settings: Configuration options
	- Readme: Documentation
	- Discount Code: Coupon management
	- Support: Help and donation information
	-->
	<div id="tabs">
		<ul>
			<!-- Tab Navigation -->
			<li><a href="#tabs-1"><?php _e(
       "Usage",
       "wp-ultra-simple-paypal-shopping-cart"
   ); ?></a></li>
			<li><a href="#tabs-2"><?php _e(
       "Settings",
       "wp-ultra-simple-paypal-shopping-cart"
   ); ?></a></li>
			<li><a href="#tabs-3"><?php _e(
       "Readme",
       "wp-ultra-simple-paypal-shopping-cart"
   ); ?></a></li>
			<li><a href="#tabs-4"><?php _e(
       "Discount Code",
       "wp-ultra-simple-paypal-shopping-cart"
   ); ?></a></li>
			<li><a href="#tabs-5"><?php _e(
       "Do you like WUSPSC ?",
       "wp-ultra-simple-paypal-shopping-cart"
   ); ?></span></a></li>
			<li><a href="#tabs-6"><?php _e(
       "Support",
       "wp-ultra-simple-paypal-shopping-cart"
   ); ?></a></li>
		</ul>

		<!-- 
		TAB 1: USAGE INSTRUCTIONS 
		This tab contains detailed instructions on how to use the plugin
		-->
		<div id="tabs-1">
			<h2>
				<div id="icon-edit-pages" class="icon32"></div>
				<?php _e(
        "WP Ultra Simple Shopping Cart Usage",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?>
				v <?php echo WUSPSC_VERSION; ?>
			</h2>

			<p>
				<?php _e(
        "For information, updates and detailed documentation, please visit:",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?>
				<a href="https://www.supersonique-studio.com">supersonique-studio.com</a>
			</p>

			<p>
				<?php _e(
        "For support, please contact us:",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?>
				<a href="https://supersonique-studio.com/contact/">
					<?php _e("Supersonique Studio Support", "wp-ultra-simple-paypal-shopping-cart"); ?>
				</a>
			</p>

			<fieldset class="options">
				<!-- Step 1: PayPal Account Setup -->
				<h3>
					<?php _e(
         '1. <a href="https://www.paypal.com/fr/mrb/pal=CH4PZVAK2GJAJ" target="_blank">Create a PayPal account </a>(free for basic accounts)',
         "wp-ultra-simple-paypal-shopping-cart"
     ); ?>
				</h3>

				<!-- Step 2: Shortcode Examples and Usage -->
				<h3>
					<?php _e(
         "2. Add the shopping cart functionality to your products by using the following shortcodes:",
         "wp-ultra-simple-paypal-shopping-cart"
     ); ?>
				</h3>

				<ul>
					<!-- Basic Add to Cart Button -->
					<li>
						<h4>
							<?php _e(
           "<ion-icon name='caret-forward' class=\"orange\"></ion-icon> Basic 'Add to Cart' button - simply add this shortcode to any post or page:",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?><br>
						</h4>
						<strong>[wp_cart:<?php _e(
          "PRODUCT-NAME",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?>:price:<?php _e(
    "PRODUCT-PRICE",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>:end]</strong><br>
						<?php _e("Example:", "wp-ultra-simple-paypal-shopping-cart"); ?>
						<blockquote>
							[wp_cart:<?php _e(
           "Test Product",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>:price:15.00:end]
						</blockquote>
						<p>
							<?php _e(
           "<ion-icon name='alert-circle' class='notice-icon'></ion-icon> This shortcode is now usable as a widget in the Gutenberg editor, with the name \"Simple add cart block\".",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>
						</p>

						<?php _e(
          "To add the 'Add to Cart' button directly in your theme files, use this PHP function:",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?><br>
						<code>&lt;?php echo print_wp_cart_button_for_product('PRODUCT-NAME', PRODUCT-PRICE); ?&gt;</code>

						<?php _e(
          "To display the number of items in the cart:",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?><br>
						<code>&lt;?php echo wpusc_cart_item_qty(); ?&gt;</code>

					</li>

					<!-- Dropdown Variation Control -->
					<li>
						<h4>
							<?php _e(
           "<ion-icon name='caret-forward' class=\"orange\"></ion-icon> Dropdown variation control - adds a dropdown selection without changing the price:",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?><br>
						</h4>
						<strong>[wp_cart:<?php _e(
          "PRODUCT-NAME",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?>:price:<?php _e(
    "PRODUCT-PRICE",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>:var1[...]:end]</strong><br>
						<?php _e("Example:", "wp-ultra-simple-paypal-shopping-cart"); ?>
						<blockquote>
							[wp_cart:<?php _e(
           "Test Product",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>:price:15:var1[Size|Small|Medium|Large]:end]
						</blockquote>
						<p>
							<?php _e(
           "<ion-icon name='alert-circle' class='notice-icon'></ion-icon> This shortcode is now usable as a widget in the Gutenberg editor, with the name \"Simple variation add cart block\".",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>
						</p>
					</li>

					<!-- Price Variations -->
					<li>
						<h4>
							<?php _e(
           "<ion-icon name='caret-forward' class=\"orange\"></ion-icon> Price variations - allows customers to select different pricing options:",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?><br>
						</h4>
						<strong>[wp_cart:<?php _e(
          "PRODUCT-NAME",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?>:price:[<?php _e(
    "VARIATION-NAME",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>|<?php _e(
    "VARIATION-LABEL1",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>,<?php _e(
    "VARIATION-PRICE1",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>|<?php _e(
    "VARIATION-LABEL2",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>,<?php _e(
    "VARIATION-PRICE2",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>]:end]</strong><br>
						<?php _e("Example:", "wp-ultra-simple-paypal-shopping-cart"); ?>
						<blockquote>
							[wp_cart:<?php _e(
           "Test Product",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>:price:[<?php _e(
    "Size|Small,1.10|Medium,2.10|Large,3.10",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>]:end]
						</blockquote>
						<p>
							<?php _e(
           "<ion-icon name='alert-circle' class='notice-icon'></ion-icon>  This shortcode is now usable as a widget in the Gutenberg editor, with the name \"Price variation add cart block\".",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>
						</p>
					</li>

					<!-- Price and Shipping Variations -->
					<li>
						<h4>
							<?php _e(
           "<ion-icon name='caret-forward' class=\"orange\"></ion-icon> Price and shipping variations - for products with different shipping costs based on selection:",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?><br>
						</h4>
						<strong>[wp_cart:<?php _e(
          "PRODUCT-NAME",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?>:price:[...]:shipping:[...]:end]</strong><br>
						<?php _e("Example:", "wp-ultra-simple-paypal-shopping-cart"); ?>
						<blockquote>
							[wp_cart:<?php _e(
           "Test Product",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>:price:[Size|Small,1.10||Large,3.10]:shipping:3:end]
						</blockquote>
					</li>

					<!-- Variation Control with Shipping -->
					<li>
						<h4>
							<?php _e(
           "<ion-icon name='caret-forward' class=\"orange\"></ion-icon> Variation control with shipping - adds a dropdown selection with a set shipping cost:",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?><br>
						</h4>
						<strong>[wp_cart:...:shipping:...:var1[...]:end]</strong><br>
						<?php _e("Example:", "wp-ultra-simple-paypal-shopping-cart"); ?>
						<blockquote>
							[wp_cart:Test Product:price:15:shipping:2:var1[Size|Small|Medium|Large]:end]
						</blockquote>
					</li>

					<!-- Multiple Variations -->
					<li>
						<h4>
							<?php _e(
           "<ion-icon name='caret-forward' class=\"orange\"></ion-icon> Multiple variations - add unlimited dropdown options:",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?><br>
						</h4>
						<strong>[wp_cart:...:var1[...]:var2[...]:end]</strong><br>
						<?php _e("Example:", "wp-ultra-simple-paypal-shopping-cart"); ?>
						<blockquote>
							[wp_cart:Test Product:price:15:shipping:2:var1[Size|Small|Medium|Large]:var2[Color|Red|Green]:end]
						</blockquote>
					</li>
				</ul>

				<!-- Step 3a: Adding Shopping Cart Display -->
				<h3><?php _e(
        "3.a Adding the shopping cart to your site",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?></h4>
					<blockquote>
						<?php _e(
          "To display the checkout on any post or page, simply add the shortcode:",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?>
						<strong>[show_wp_shopping_cart]</strong><br>
						<?php _e(
          "Alternatively, use the sidebar widget to add the shopping cart to your sidebar.",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?>
					</blockquote>
					<p>
						<?php _e(
          "<ion-icon name='alert-circle' class='notice-icon'></ion-icon> This shortcode is now usable as a widget in the Gutenberg editor, with the name \"Simple cart block\".",
          "wp-ultra-simple-paypal-shopping-cart"
      ); ?>
					</p>

					<!-- Step 3b: 3-Step Checkout Process -->
					<h3><?php _e(
         "3.b Setting up a 3-step checkout process",
         "wp-ultra-simple-paypal-shopping-cart"
     ); ?></h4>
						<blockquote>
							<ol>
								<li><?php _e(
            "Create a page with this shortcode:",
            "wp-ultra-simple-paypal-shopping-cart"
        ); ?> [validate_wp_shopping_cart]</li>
								<li>
									<?php _e(
             "Create a page with a contact form (Contact Form 7 is recommended):",
             "wp-ultra-simple-paypal-shopping-cart"
         ); ?>
									<ol>
										<li><?php _e(
              "Edit your form settings",
              "wp-ultra-simple-paypal-shopping-cart"
          ); ?></li>
										<li><?php _e(
              "Navigate to the \"Additional Settings\" section",
              "wp-ultra-simple-paypal-shopping-cart"
          ); ?></li>
										<li><?php _e(
              "Add this code: on_sent_ok: \"location = 'http://example.com/mycart';\" (replace with your cart page URL)",
              "wp-ultra-simple-paypal-shopping-cart"
          ); ?></li>
										<li><?php _e(
              "Create your cart page with the shortcode:",
              "wp-ultra-simple-paypal-shopping-cart"
          ); ?> [show_wp_shopping_cart]</li>
									</ol>
								</li>
							</ol>
							<p>
								<?php _e(
            "This setup allows you to collect customer information before proceeding to PayPal checkout.",
            "wp-ultra-simple-paypal-shopping-cart"
        ); ?><br>
								<?php _e(
            "Customers will be redirected to the cart with the PayPal button after successfully submitting the form.",
            "wp-ultra-simple-paypal-shopping-cart"
        ); ?><br>
								<strong><?php _e(
            "Note: The [validate_wp_shopping_cart] shortcode is required on a separate page for the 3-step process to work.",
            "wp-ultra-simple-paypal-shopping-cart"
        ); ?></strong>
							</p>
						</blockquote>

						<h3>
							<?php _e(
           "4. Need help with your store? We're here to assist!",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>
						</h3>
						<blockquote>
							<em><strong>
									<a title="Live Supersonique Studio Support" href="https://supersonique-studio.com/contact/" target="_blank">
										<?php _e("Live Support", "wp-ultra-simple-paypal-shopping-cart"); ?>
									</a>
								</strong></em>
							<?php _e("available for all your", "wp-ultra-simple-paypal-shopping-cart"); ?>
							<em><strong>WordPress</strong></em> <?php _e(
           "and",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?> <strong><em>Prestashop</em></strong> <?php _e(
     "needs!",
     "wp-ultra-simple-paypal-shopping-cart"
 ); ?><br>
							<?php _e(
           "Professional support provided by the Supersonique Studio team.",
           "wp-ultra-simple-paypal-shopping-cart"
       ); ?>
						</blockquote>
			</fieldset>
		</div>

		<?php
  /**
   * Load Parsedown library for Markdown rendering
   * This section reads and displays the README.md file content
   */
  require_once plugin_dir_path(__FILE__) . "Parsedown.php";
  $readme_path = plugin_dir_path(__FILE__) . "README.md";
  if (file_exists($readme_path)) {
      $parsedown = new Parsedown();
      $readme_html = $parsedown->text(file_get_contents($readme_path));
  }
  ?>

		<!-- 
		TAB 3: README DOCUMENTATION
		This tab displays the plugin's README file content parsed from Markdown
		-->
		<div id="tabs-3">
			<h2>
				<div id="icon-edit-comments" class="icon32"></div>
				<?php _e(
        "WP Ultra Simple Shopping Cart Read ME",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?>
			</h2>
			<div class="content">
				<?php echo $readme_html; ?>
			</div>
		</div>

		<?php
  // Detect user language for proper donation button display
  $language = __UP_detect_language();

  // Prepare external link for marketing section
  $eceurl =
      '<a href="https://supersonique-studio.com" target="_blank">Supersonique-Studio.com</a>';

  /**
   * TAB 5: MARKETING AND DONATION SECTION
   * This section displays marketing information and donation options
   */
  echo '<div id="tabs-5">
<h2><div id="icon-users" class="icon32"></div>' .
      __(
          "Like sell on major marketplaces ?",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</h2>
<div id="supersonique">
<p>' .
      __(
          "Have a look on $eceurl if you like sell on major marketplaces worldwide. We offer fix prices, and a huge expertise to help you to put your products UP to market.",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
<a href="https://supersonique-studio.com"><img src="' .
      WUSPSC_PLUGIN_IMAGES_URL .
      'supersonique-H.png"></a>
</div>

<!-- Plugin Rating and Donation Request Section -->
<h2><div id="icon-users" class="icon32"></div>' .
      __("Do you like WUSPSC ?", "wp-ultra-simple-paypal-shopping-cart") .
      '</h2>
<div id="helpme">
<p><a href="http://wordpress.org/plugins/wp-ultra-simple-paypal-shopping-cart/" target="_blank">' .
      __(
          "Please, if you like WUSPSC, think to give it a good rating",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      "</a>" .
      __(
          " and please consider to donate 1$ only or more if you can, &#8364; or &pound; to help me to give time for user&#8217;s support, add new features and upgrades.",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '
	' .
      __(
          'After more than 100,000 downloads, <a href="https://www.supersonique-studio.com">only less that 30 users donate</a>.',
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '
	<!-- PayPal Donation Form -->
	<div id="donate">
		<form class="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="AXQNVXNYWUEZ4">
		<input type="image" src="' .
      WUSPSC_CART_URL .
      "/images/btn_donateCC_LG-" .
      $language .
      '.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
</p>
</div>

<!-- Alternative Support: Music Purchases -->
<p>' .
      __(
          "Or if you like down-tempo / ambiant / electronic music, you can buy a few tracks from one of my CD on Amazon.",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
<p>
<ul>
	<li><a href="http://www.amazon.com/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon US</a><li>
	<li><a href="http://www.amazon.co.uk/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon UK</a><li>
	<li><a href="http://www.amazon.de/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon DE</a><li>
	<li><a href="http://www.amazon.fr/s/ref=ntt_srch_drd_B001L5OJSM?ie=UTF8&search-type=ss&index=digital-music&field-keywords=Mike%20Castro%20de%20Maria" target="_blank">Amazon FR</a><li>
</ul>
<!-- Album Cover Images -->
<img src="' .
      WUSPSC_PLUGIN_IMAGES_URL .
      '41dK4t7R6OL._SL500_SS110_.jpg"><img src="' .
      WUSPSC_PLUGIN_IMAGES_URL .
      '41RTkTKGzRL._SL500_SS110_.jpg"><img src="' .
      WUSPSC_PLUGIN_IMAGES_URL .
      '51oggSX6F0L._SL500_SS110_.jpg"><img src="' .
      WUSPSC_PLUGIN_IMAGES_URL .
      '51xQJmJpwuL._SL500_SS110_.jpg">
</p>
<p>' .
      __(
          "Thanks a lot for your support !!!",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '<p>
</div>';

  /**
   * TAB 4: DISCOUNT CODE MANAGEMENT
   * This section handles CRUD operations for discount codes
   */
  echo '<div id="tabs-4">
<h2><div id="icon-edit-comments" class="icon32"></div>' .
      __("Discount Codes", "wp-ultra-simple-paypal-shopping-cart") .
      '</h2>
<p>' .
      __(
          "Create and manage discount codes for your customers.",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      "</p>";

  /**
   * Handle discount code CRUD operations
   * Process form submissions for creating, updating, and deleting discount codes
   */
  if (isset($_POST["wpussc_save_discount_settings"])) {
      // Save discount general settings
      update_option(
          "wpussc_discount_enabled",
          isset($_POST["wpussc_discount_enabled"]) ? 1 : 0
      );
      update_option(
          "wpussc_discount_label",
          sanitize_text_field($_POST["wpussc_discount_label"])
      );
      update_option(
          "wpussc_discount_code_label",
          sanitize_text_field($_POST["wpussc_discount_code_label"])
      );
      update_option(
          "wpussc_discount_button_text",
          sanitize_text_field($_POST["wpussc_discount_button_text"])
      );
      update_option(
          "wpussc_discount_invalid_code_message",
          sanitize_text_field($_POST["wpussc_discount_invalid_code_message"])
      );

      echo '<div class="updated"><p>' .
          __(
              "Discount settings saved successfully!",
              "wp-ultra-simple-paypal-shopping-cart"
          ) .
          "</p></div>";
  } elseif (isset($_POST["wpussc_add_discount"])) {
      // Add new discount code
      $code = isset($_POST["code"]) ? sanitize_text_field($_POST["code"]) : "";
      $type = isset($_POST["type"]) ? sanitize_text_field($_POST["type"]) : "";
      $amount = isset($_POST["amount"]) ? floatval($_POST["amount"]) : 0;
      $floor_price = isset($_POST["floor_price"])
          ? floatval($_POST["floor_price"])
          : 0;
      $expiry_date = !empty($_POST["expiry_date"])
          ? sanitize_text_field($_POST["expiry_date"])
          : null;
      $usage_limit = !empty($_POST["usage_limit"])
          ? intval($_POST["usage_limit"])
          : null;

      if (!empty($code) && !empty($type) && $amount > 0) {
          if (
              wpussc_add_discount_code(
                  $code,
                  $type,
                  $amount,
                  $expiry_date,
                  $usage_limit,
                  $floor_price
              )
          ) {
              echo '<div class="updated"><p>' .
                  __(
                      "Discount code added successfully!",
                      "wp-ultra-simple-paypal-shopping-cart"
                  ) .
                  "</p></div>";
          } else {
              echo '<div class="error"><p>' .
                  __(
                      "Error adding discount code. The code might already exist.",
                      "wp-ultra-simple-paypal-shopping-cart"
                  ) .
                  "</p></div>";
          }
      } else {
          echo '<div class="error"><p>' .
              __(
                  "Please fill in all required fields.",
                  "wp-ultra-simple-paypal-shopping-cart"
              ) .
              "</p></div>";
      }
  } elseif (
      isset($_POST["delete_discount"]) &&
      wp_verify_nonce($_REQUEST["_wpnonce"], "delete_discount")
  ) {
      // Delete discount code (with nonce verification for security)
      $id = intval($_POST["delete_discount"]);
      if (wpussc_delete_discount_code($id)) {
          echo '<div class="updated"><p>' .
              __(
                  "Discount code deleted successfully!",
                  "wp-ultra-simple-paypal-shopping-cart"
              ) .
              "</p></div>";
      } else {
          echo '<div class="error"><p>' .
              __(
                  "Error deleting discount code.",
                  "wp-ultra-simple-paypal-shopping-cart"
              ) .
              "</p></div>";
      }
  } elseif (isset($_POST["wpussc_edit_discount"])) {
      // Update existing discount code
      $id = isset($_POST["discount_id"]) ? intval($_POST["discount_id"]) : 0;
      $data = [
          "code" => sanitize_text_field($_POST["code"]),
          "type" => sanitize_text_field($_POST["type"]),
          "amount" => floatval($_POST["amount"]),
          "floor_price" => floatval($_POST["floor_price"]),
          "expiry_date" => !empty($_POST["expiry_date"])
              ? sanitize_text_field($_POST["expiry_date"])
              : null,
          "usage_limit" => !empty($_POST["usage_limit"])
              ? intval($_POST["usage_limit"])
              : null,
          "status" => sanitize_text_field($_POST["status"]),
      ];

      if (wpussc_update_discount_code($id, $data)) {
          echo '<div class="updated"><p>' .
              __(
                  "Discount code updated successfully!",
                  "wp-ultra-simple-paypal-shopping-cart"
              ) .
              "</p></div>";
      } else {
          echo '<div class="error"><p>' .
              __(
                  "Error updating discount code.",
                  "wp-ultra-simple-paypal-shopping-cart"
              ) .
              "</p></div>";
      }
  }

  // Formulaire pour ajouter un nouveau code de réduction
  echo '<div class="postbox">
	<h3 class="hndle"><span>' .
      __("Add New Discount Code", "wp-ultra-simple-paypal-shopping-cart") .
      '</span></h3>
	<div class="inside">
		<form method="post" action="">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">' .
      __("Code", "wp-ultra-simple-paypal-shopping-cart") .
      ' <span style="color:red">*</span></th>
					<td><input type="text" name="code" value="" size="20" required></td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Type", "wp-ultra-simple-paypal-shopping-cart") .
      ' <span style="color:red">*</span></th>
					<td>
						<select name="type" required>
							<option value="">' .
      __("Select Type", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
							<option value="percentage">' .
      __("Percentage Discount", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
							<option value="fixed">' .
      __("Fixed Amount Discount", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Amount", "wp-ultra-simple-paypal-shopping-cart") .
      ' <span style="color:red">*</span></th>
					<td>
						<input type="number" name="amount" value="" size="10" min="0" step="1" required>
						<p class="description">' .
      __(
          "For percentage type, enter a number between 1-100",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Minimum Purchase Amount", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="number" name="floor_price" min="0">
						<p class="description">' .
      __(
          "Minimum cart total required to use this discount code.",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Expiry Date", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="date" name="expiry_date" value="">
						<p class="description">' .
      __("Leave blank for no expiry", "wp-ultra-simple-paypal-shopping-cart") .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Usage Limit", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="number" name="usage_limit" value="" size="10" min="0">
						<p class="description">' .
      __(
          "Leave blank for unlimited usage",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
			</table>
			<p><input type="submit" name="wpussc_add_discount" value="' .
      __("Add Discount Code", "wp-ultra-simple-paypal-shopping-cart") .
      '" class="button-primary" /></p>
		</form>
	</div>
</div>';

  // Liste des codes de réduction existants
  echo '<div class="postbox" id="list-discounts">
	<h3 class="hndle"><span>' .
      __("Existing Discount Codes", "wp-ultra-simple-paypal-shopping-cart") .
      '</span></h3>
	<div class="inside">';
  $discount_codes = wpussc_get_all_discount_codes();
  if (!empty($discount_codes)) {
      echo '<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th>' .
          __("Code", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
				<th>' .
          __("Type", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
				<th>' .
          __("Amount", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
				<th>' .
          __("Min Purchase", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
				<th>' .
          __("Expiry Date", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
				<th>' .
          __("Usage", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
				<th>' .
          __("Status", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
				<th>' .
          __("Actions", "wp-ultra-simple-paypal-shopping-cart") .
          '</th>
			</tr>
		</thead>
		<tbody>';
      foreach ($discount_codes as $discount) {
          $type_display =
              $discount->type == "percentage"
                  ? __("Percentage", "wp-ultra-simple-paypal-shopping-cart")
                  : __("Fixed Amount", "wp-ultra-simple-paypal-shopping-cart");
          $amount_display =
              $discount->type == "percentage"
                  ? $discount->amount . "%"
                  : get_option("cart_currency_symbol") . $discount->amount;
          $expiry_display = !empty($discount->expiry_date)
              ? date_i18n(
                  get_option("date_format"),
                  strtotime($discount->expiry_date)
              )
              : __("Never", "wp-ultra-simple-paypal-shopping-cart");
          $usage_display = $discount->usage_limit
              ? $discount->usage_count . "/" . $discount->usage_limit
              : $discount->usage_count;
          $status_display =
              $discount->status == "active"
                  ? __("Active", "wp-ultra-simple-paypal-shopping-cart")
                  : __("Inactive", "wp-ultra-simple-paypal-shopping-cart");
          $floor_price_display = isset($discount->floor_price)
              ? get_option("cart_currency_symbol") .
                  number_format($discount->floor_price, 2)
              : get_option("cart_currency_symbol") . "0.01";
          $delete_url = wp_nonce_url(
              add_query_arg([
                  "page" => basename(dirname(__FILE__)) . "/wpussc-option.php",
                  "delete_discount" => $discount->id,
              ]),
              "delete_discount"
          );

          echo '<tr>
			<td>' .
              esc_html($discount->code) .
              '</td>
			<td>' .
              esc_html($type_display) .
              '</td>
			<td>' .
              esc_html($amount_display) .
              '</td>
			<td>' .
              esc_html($floor_price_display) .
              '</td>
			<td>' .
              esc_html($expiry_display) .
              '</td>
			<td>' .
              esc_html($usage_display) .
              '</td>
			<td>' .
              esc_html($status_display) .
              '</td>
			<td>
				<button type="button" class="button-link edit-discount" style="border:none;background:none;cursor:pointer;color:#0073aa;padding:0;margin-right:10px;"
					data-id="' .
              esc_attr($discount->id) .
              '"
					data-code="' .
              esc_attr($discount->code) .
              '"
					data-type="' .
              esc_attr($discount->type) .
              '"
					data-amount="' .
              esc_attr($discount->amount) .
              '"
					data-floor-price="' .
              esc_attr($discount->floor_price) .
              '"
					data-expiry-date="' .
              esc_attr($discount->expiry_date) .
              '"
					data-usage-limit="' .
              esc_attr($discount->usage_limit) .
              '"
					data-usage-count="' .
              esc_attr($discount->usage_count) .
              '"
					data-status="' .
              esc_attr($discount->status) .
              '">
					<span class="dashicons dashicons-edit" style="font-size:1.2em;vertical-align:middle;"></span>
					' .
              __("Edit", "wp-ultra-simple-paypal-shopping-cart") .
              '
				</button>
				<form method="post" action="" style="margin:0;display:inline;">
					<input type="hidden" name="delete_discount" value="' .
              esc_attr($discount->id) .
              '">
					' .
              wp_nonce_field("delete_discount", "_wpnonce", true, false) .
              '
					<button type="submit" class="button-link" style="border:none;background:none;cursor:pointer;color:#cc0000;padding:0;" 
							onclick="return confirm(\'' .
              esc_js(
                  __(
                      "Are you sure you want to delete this discount code?",
                      "wp-ultra-simple-paypal-shopping-cart"
                  )
              ) .
              '\')">
						<span class="dashicons dashicons-trash" style="font-size:1.2em;vertical-align:middle;"></span>
						' .
              __("Delete", "wp-ultra-simple-paypal-shopping-cart") .
              '
					</button>
				</form>
			</td>
		</tr>';
      }
      echo "</tbody></table>";
  } else {
      echo "<p>" .
          __(
              "No discount codes found.",
              "wp-ultra-simple-paypal-shopping-cart"
          ) .
          "</p>";
  }
  echo "</div></div>";

  // Formulaire pour modifier un code de réduction (caché par défaut, affiché via JS)
  echo '<div class="postbox" id="edit-discount-form" style="display:none;">
	<h3 class="hndle"><span>' .
      __("Edit Discount Code", "wp-ultra-simple-paypal-shopping-cart") .
      '</span></h3>
	<div class="inside">
		<form method="post" action="" id="wpussc_edit_discount_form">
			<input type="hidden" name="discount_id" id="edit_discount_id" value="">
			<table class="form-table">
				<tr valign="top">
					<th scope="row">' .
      __("Code", "wp-ultra-simple-paypal-shopping-cart") .
      ' <span style="color:red">*</span></th>
					<td><input type="text" name="code" id="edit_code" value="" size="20" required></td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Type", "wp-ultra-simple-paypal-shopping-cart") .
      ' <span style="color:red">*</span></th>
					<td>
						<select name="type" id="edit_type" required>
							<option value="">' .
      __("Select Type", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
							<option value="percentage">' .
      __("Percentage Discount", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
							<option value="fixed">' .
      __("Fixed Amount Discount", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Amount", "wp-ultra-simple-paypal-shopping-cart") .
      ' <span style="color:red">*</span></th>
					<td>
						<input type="number" name="amount" id="edit_amount" value="" size="10" min="0" step="1" required>
						<p class="description">' .
      __(
          "For percentage type, enter a number between 1-100",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Minimum Purchase Amount", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="number" name="floor_price" id="edit_floor_price" min="0">
						<p class="description">' .
      __(
          "Minimum cart total required to use this discount code.",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Expiry Date", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="date" name="expiry_date" id="edit_expiry_date" value="">
						<p class="description">' .
      __("Leave blank for no expiry", "wp-ultra-simple-paypal-shopping-cart") .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Usage Limit", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="number" name="usage_limit" id="edit_usage_limit" value="" size="10" min="0">
						<p class="description">' .
      __(
          "Leave blank for unlimited usage",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Current Usage Count", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="number" id="edit_usage_count" value="" size="10" min="0" disabled>
						<p class="description">' .
      __(
          "This is the current number of times this code has been used",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Status", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<select name="status" id="edit_status">
							<option value="active">' .
      __("Active", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
							<option value="inactive">' .
      __("Inactive", "wp-ultra-simple-paypal-shopping-cart") .
      '</option>
						</select>
					</td>
				</tr>
			</table>
			<p>
				<input type="submit" name="wpussc_edit_discount" value="' .
      __("Update Discount Code", "wp-ultra-simple-paypal-shopping-cart") .
      '" class="button-primary" />
				<button type="button" id="cancel-edit" class="button">' .
      __("Cancel", "wp-ultra-simple-paypal-shopping-cart") .
      '</button>
			</p>
		</form>
	</div>
</div>';

  // Options générales des codes de réduction
  echo '<div class="postbox">
	<h3 class="hndle"><span>' .
      __("Discount Code Settings", "wp-ultra-simple-paypal-shopping-cart") .
      '</span></h3>
	<div class="inside">
		<table class="form-table">
			<form method="post" action="" id="wpussc_discount_settings_form">
				<tr valign="top">
					<th scope="row">' .
      __("Enable Discount Codes", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="checkbox" name="wpussc_discount_enabled" value="1" ' .
      (get_option("wpussc_discount_enabled") ? 'checked="checked"' : "") .
      '>
						<span class="description">' .
      __(
          "Check to enable discount code functionality on the cart",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Discount Label", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="text" name="wpussc_discount_label" value="' .
      esc_attr(get_option("wpussc_discount_label")) .
      '" size="40">
						<p class="description">' .
      __(
          "The label that appears in the cart for the discount line",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Discount Code Field Label", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="text" name="wpussc_discount_code_label" value="' .
      esc_attr(get_option("wpussc_discount_code_label")) .
      '" size="40">
						<p class="description">' .
      __(
          "The label for the discount code input field",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Apply Button Text", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="text" name="wpussc_discount_button_text" value="' .
      esc_attr(get_option("wpussc_discount_button_text")) .
      '" size="40">
						<p class="description">' .
      __(
          "The text for the apply discount button",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">' .
      __("Invalid Code Message", "wp-ultra-simple-paypal-shopping-cart") .
      '</th>
					<td>
						<input type="text" name="wpussc_discount_invalid_code_message" value="' .
      esc_attr(get_option("wpussc_discount_invalid_code_message")) .
      '" size="40">
						<p class="description">' .
      __(
          "The message that appears when an invalid discount code is entered",
          "wp-ultra-simple-paypal-shopping-cart"
      ) .
      '</p>
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2">
						<input type="submit" name="wpussc_save_discount_settings" value="' .
      __("Save Changes", "wp-ultra-simple-paypal-shopping-cart") .
      '" class="button-primary" />
					</td>
				</tr>
			</form>
		</table>
	</div>
</div>';

  /**
   * JavaScript for Discount Code Management Interface
   * This script handles the edit/cancel functionality for discount codes
   */
  echo '<script type="text/javascript">
jQuery(document).ready(function($) {
	// Handle click on edit discount button
	$(".edit-discount").on("click", function() {
		// Extract data attributes from the clicked edit button
		var id = $(this).data("id");
		var code = $(this).data("code");
		var type = $(this).data("type");
		var amount = $(this).data("amount");
		var floorPrice = $(this).data("floor-price");
		var expiryDate = $(this).data("expiry-date");
		var usageLimit = $(this).data("usage-limit");
		var usageCount = $(this).data("usage-count");
		var status = $(this).data("status");
		
		// Populate the edit form with the data
		$("#edit_discount_id").val(id);
		$("#edit_code").val(code);
		$("#edit_type").val(type);
		$("#edit_amount").val(amount);
		$("#edit_floor_price").val(floorPrice);
		$("#edit_expiry_date").val(expiryDate);
		$("#edit_usage_limit").val(usageLimit);
		$("#edit_usage_count").val(usageCount);
		$("#edit_status").val(status);
		
		// Show the edit form and scroll to it smoothly
		$("#edit-discount-form").show();
		$("html, body").animate({
			scrollTop: $("#edit-discount-form").offset().top - 50
		}, 500);
	});
	
	// Handle click on Cancel button
	$("#cancel-edit").on("click", function(e) {
		e.preventDefault();
		// Hide the edit form and scroll back to the discount list
		$("#edit-discount-form").hide();
		$("html, body").animate({
			scrollTop: $("#list-discounts").offset().top - 50
		}, 500);
	});
});
</script>';

  echo "</div>";
  ?>




		<div id="tabs-6">
			<h2>
				<div id="icon-edit-comments" class="icon32"></div><?php _e(
        "WP Ultra Simple Shopping Cart Support",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?>
			</h2>
			<div class="content">
				<h4><?php _e(
        "Do you need support or new features?",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?></h4>
				<p><?php _e(
        "Just ask on",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?> <a target="_blank" href="https://supersonique-studio.com/contact/"><?php _e(
     "WUSPSC Forum",
     "wp-ultra-simple-paypal-shopping-cart"
 ); ?></a>.
				<p>

				<h4><?php _e(
        "Do you need quick and direct support?",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?></h4>
				<p><?php _e(
        "We can provide you \"Live Support\" for all your WordPress or Prestashop needs!",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?> <a target="_blank" href="https://supersonique-studio.com/contact/"><?php _e(
     "click on Supersonique Studio Live Support",
     "wp-ultra-simple-paypal-shopping-cart"
 ); ?></a>. <?php _e(
    "Support provided by Supersonique Studio Staff.",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>
				<p>

				<h4><?php _e(
        "Do you like the WP Ultra Simple Paypal Shopping Cart Plugin?",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?></h4>
				<p><?php _e(
        "Please",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?> <a target="_blank" href="https://wordpress.org/plugins/wp-ultra-simple-paypal-shopping-cart/"><?php _e(
     "give it a good rating",
     "wp-ultra-simple-paypal-shopping-cart"
 ); ?></a> <?php _e(
    "on Wordpress website",
    "wp-ultra-simple-paypal-shopping-cart"
); ?>.</p>
			</div>
		</div>

		<div id="tabs-2">
			<h2>
				<div id="icon-options-general" class="icon32"></div><?php _e(
        "WP Ultra Simple Shopping Cart Settings",
        "wp-ultra-simple-paypal-shopping-cart"
    ); ?> v <?php echo WUSPSC_VERSION; ?>
			</h2>
			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<input type="hidden" name="info_update" id="info_update" value="true">

			<?php
   #qtranslate warning message

   if (
       function_exists("qtrans_getLanguage") ||
       function_exists("qtranxf_getLanguage")
   ) {
       wp_nonce_field(__("delete_my_action"));

       $qtransup =
           '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"><p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' .
           __(
               "<strong>You are using qTranslate or qTranslate-X</strong> if you like to customise the followings strings, please fill the following fields with the qTranslate syntax.",
               "wp-ultra-simple-paypal-shopping-cart"
           ) .
           "<br>" .
           __("Eg", "wp-ultra-simple-paypal-shopping-cart") .
           " : [:en]Sub-total[:fr]Sous-total[:de]Zwischensumme[:es]Total parcial" .
           "</p></div></div>";

       $qtranstalex_msg =
           '<tr valign="top"><td colspan="2">' . $qtransup . "</td></tr>";
   } else {
       wp_nonce_field(__("delete_my_action"));
       $qtranstalex_msg = "";
   }

   echo '
<div class="inside">
<table class="form-table">
<!-- Paypal -->
<tr valign="top">
<th scope="row">' .
       __("Paypal Email Address", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="cart_paypal_email" value="' .
       $defaultEmail .
       '" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Paypal Sandbox (cart is in test)",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td>Test: <input type="radio" name="is_sandbox" value="1" ' .
       $defaultSandboxChecked1 .
       '/>&nbsp;Production: <input type="radio" name="is_sandbox" value="0" ' .
       $defaultSandboxChecked2 .
       "/><br> " .
       __(
           'You must open a free developer account to use sandbox for your tests before go live.<br> Go to <a href="https://developer.paypal.com/">https://developer.paypal.com/</a>, register and connect.',
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Paypal Debug output (create local debug log file)",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td>ON: <input type="radio" name="wp_cart_enable_debug" value="1" ' .
       $defaultDebugChecked1 .
       '/>&nbsp;OFF: <input type="radio" name="wp_cart_enable_debug" value="0" ' .
       $defaultDebugChecked2 .
       "/>" .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Use PayPal Profile Based Shipping",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_use_profile_shipping" value="1" ' .
       $wpus_shopping_cart_use_profile_shipping .
       "><br>" .
       __(
           "Check this if you want to use",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       ' <a href="https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/e_howto_html_ProfileAndTools#id08A9EF00IQY" target="_blank">' .
       __(
           "PayPal profile based shipping",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       "</a>. " .
       __(
           "Using this will ignore any other shipping options that you have specified in this plugin.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Must Collect Shipping Address on PayPal",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_collect_address" value="1" ' .
       $wpus_shopping_cart_collect_address .
       "><br>" .
       __(
           "If checked the customer will be forced to enter a shipping address on PayPal when checking out.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<!-- Settings -->

<tr valign="top">
<th scope="row">' .
       __("Base Shipping Cost", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="cart_base_shipping_cost" value="' .
       $baseShipping .
       '" size="5"> <br>' .
       __(
           "This is the base shipping cost that will be added to the total of individual products shipping cost. Put 0 if you do not want to charge shipping cost or use base shipping cost.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Free Shipping for Orders Over",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="text" name="cart_free_shipping_threshold" value="' .
       $cart_free_shipping_threshold .
       '" size="5"> <br>' .
       __(
           "When a customer orders more than this amount he/she will get free shipping. Leave empty if you do not want to use it.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Shipping fee per item", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_shipping_per_items" value="1" ' .
       $wpus_shopping_cart_shipping_per_items .
       "><br>" .
       __(
           "By default, shipping fee is multiply by the item's quantity added. If ticked only 1 shipping fee is added per items group.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Free Shipping", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="display_free_shipping" value="1" ' .
       $displayFreeShipping .
       "><br>" .
       __(
           " If ticked, display a shipping free message on cart.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Currency", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="cart_payment_currency" value="' .
       $defaultCurrency .
       '" maxlength="3" size="4"> (' .
       __("e.g.", "wp-ultra-simple-paypal-shopping-cart") .
       " USD, EUR, GBP, AUD)" .
       __(
           'Full list on <a target="_blank" href="https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes">PayPal website</a>',
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>
<tr valign="top">
<th scope="row">' .
       __("Currency Symbol", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="cart_currency_symbol" value="' .
       $defaultSymbol .
       '" size="2" style=""> (' .
       __("e.g.", "wp-ultra-simple-paypal-shopping-cart") .
       ' $, &#163;, &#8364;)
</td>
</tr>
<tr valign="top">
<th scope="row">' .
       __("Currency display", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td>Is the currency symbol is displayed befor or after the price ? <input type="radio" name="cart_currency_symbol_order" value="1" ' .
       $defaultSymbolOrderChecked1 .
       '/> Before or <input type="radio" name="cart_currency_symbol_order" value="2" ' .
       $defaultSymbolOrderChecked2 .
       '/> After
</td>
</tr>
<tr valign="top">
<th scope="row">' .
       __("Item global VAT", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="display_vat" value="' .
       $display_vat .
       '" size="5">%<br>' .
       __(
           "Add VAT rate. The VAT must be a percentage eg. 19.60. Leave empty to disable it.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Custom buttons", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="use_custom_button" value="1" ' .
       $useCustomButton .
       "><br>" .
       __(
           " If ticked, use following custom id & class on button.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>
<tr valign="top">
<th scope="row">' .
       __("Add to Cart button text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="addToCartButtonName" value="' .
       $addcart_button_name .
       '" size="100"><br>' .
       __(
           "To use a customized 'add to cart' button text, fill with a text or leave empty for using image as button background. Don't forget to add background-image to your theme's style.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>
<tr valign="top">
<th scope="row">' .
       __(
           "Cart button id & class name (without the dash or dot)",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="text" name="add_cartstyle" value="' .
       $add_cartstyle .
       '" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">' .
       __("Checkout button text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="checkoutButtonName" value="' .
       $checkout_button_name .
       '" size="100"><br>' .
       __(
           "To use a customized 'checkout' button text, fill with a text or leave empty for using image as button background. Don't forget to add background-image to your theme's style.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>
<tr valign="top">
<th scope="row">' .
       __(
           "Checkout button id & class name (without the dash or dot)",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="text" name="checkout_style" value="' .
       $checkout_style .
       '" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Display product name", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="display_product_name" value="1" ' .
       $display_product_name .
       ">" .
       __(
           " If ticked, display the product's name, otherwise hide it",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Display Product Options Inline",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="checkbox" name="display_product_inline" value="1" ' .
       $display_product_inline .
       ">" .
       __(
           " If ticked, display the product input without line break, otherwise it display each input to a new line.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Display quantity field", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="display_quantity" value="1" ' .
       $display_quantity .
       ">" .
       __(
           " If ticked, display the quantity field to choose quantity before add to cart, otherwise quantity is 1.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Products Page URL", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="cart_products_page_url" value="' .
       $cart_products_page_url .
       '" size="100"><br>' .
       __(
           "This is the URL of your products page if you have any. If used, the shopping cart widget will display a link to this page when cart is empty",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Display Products URL in cart",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="checkbox" name="wpus_display_link_in_cart" value="1" ' .
       $wpus_display_link_in_cart .
       ">" .
       __(
           "If ticked, the product's link will not be display in cart. Activate it if you are using a page or a post for each product.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Display thumbnail in cart", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="wpus_display_thumbnail_in_cart" value="1" ' .
       $wpus_display_thumbnail_in_cart .
       ">" .
       __(
           "If ticked, the product's thumbnail will not be display in cart. Activate it if you are using a page or a post for each product.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Thumbnail size", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" size="4" name="wpus_thumbnail_in_cart_width" value="' .
       $wpus_thumbnail_in_cart_width .
       '"> px / <input type="text" size="4" name="wpus_thumbnail_in_cart_height" value="' .
       $wpus_thumbnail_in_cart_height .
       '"> px ' .
       __(
           "Size in pixel of product's thumbnail display in cart. Displayed if product's thumbnail are activate and if you'r using a page or a post for each product.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __('Hide "Cart Empty" message', "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_empty_hide" value="1" ' .
       $wp_cart_empty_hide .
       "><br>" .
       __(
           "If ticked, the shopping cart empty message on page/post or widget will not be display.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Hide items count display message",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_items_in_cart_hide" value="1" ' .
       $wpus_shopping_cart_items_in_cart_hide .
       "><br>" .
       __(
           "If ticked, the items in cart count message on page/post or widget will not be display.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Hide Shopping Cart Image", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_image_hide" value="1" ' .
       $wp_cart_image_hide .
       "><br>" .
       __(
           "If ticked the shopping cart image will not be shown.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

' .
       $qtranstalex_msg .
       '

<tr valign="top">
	<th scope="row">' .
       __("Shopping Cart title", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
	<td><input type="text" name="wp_cart_title" value="' .
       $title .
       '" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           "Text/Image to Show When Cart Empty",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="text" name="wp_cart_empty_text" value="' .
       $emptyCartText .
       '" size="60"><br>' .
       __(
           "You can either enter plain text or the URL of an image that you want to show when the shopping cart is empty",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<tr valign="top">
<th scope="row">' .
       __(
           'Singular "product in your cart" text',
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="text" name="singular_items_text" value="' .
       $singular_items_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<th scope="row">' .
       __(
           'Plural "products in your cart" text',
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="text" name="plural_items_text" value="' .
       $plural_items_text .
       '" size="40"></td>
</tr>

<tr valign="top">
<th scope="row">' .
       __("Subtotal text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="subtotal_text" value="' .
       $subtotal_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Cart Text Customization Settings - These fields allow users to customize all text displayed in the shopping cart interface -->

<!-- Shipping label customization -->
<th scope="row">' .
       __("Shipping text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="shipping_text" value="' .
       $shipping_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Total amount label customization -->
<th scope="row">' .
       __("Total text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="total_text" value="' .
       $total_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Product/item name column header customization -->
<th scope="row">' .
       __("Item name text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="item_name_text" value="' .
       $item_name_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Quantity column header customization -->
<th scope="row">' .
       __("Quantity text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="qualtity_text" value="' .
       $qualtity_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Price column header customization -->
<th scope="row">' .
       __("Price text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="price_text" value="' .
       $price_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- VAT/Tax label customization -->
<th scope="row">' .
       __("VAT text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="vat_text" value="' .
       $vat_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Item count display text customization (e.g., "3 items in cart") -->
<th scope="row">' .
       __("Item count text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="item_qty_string" value="' .
       $itemQtyString .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Empty cart message customization -->
<th scope="row">' .
       __("No item in cart text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="no_item_in_cart_string" value="' .
       $noItemInCartString .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Remove item button text customization -->
<th scope="row">' .
       __("Remove text", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="remove_text" value="' .
       $remove_text .
       '" size="40"></td>
</tr>
<tr valign="top">
<!-- Link text for "continue shopping" or "visit shop" button -->
<th scope="row">' .
       __("Products page URL title", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="wp_cart_visit_shop_text" value="' .
       $wp_cart_visit_shop_text .
       '" size="100"></td>
</tr>

<!-- PayPal Integration and Checkout Flow Configuration -->

<!-- Return URL after successful payment - where customers are redirected after completing payment -->
<tr valign="top">
<th scope="row">' .
       __("Return URL", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="cart_return_from_paypal_url" value="' .
       $return_url .
       '" size="100"><br>' .
       __(
           "This is the URL the customer will be redirected to after a successful payment",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<!-- Auto-redirect feature for streamlined checkout process -->
<tr valign="top">
<th scope="row">' .
       __(
           "Automatic redirection to checkout page",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_auto_redirect_to_checkout_page" value="1" ' .
       $wpus_shopping_cart_auto_redirect_to_checkout_page .
       '>
' .
       __("Checkout Page URL", "wp-ultra-simple-paypal-shopping-cart") .
       ': <input type="text" name="cart_checkout_page_url" value="' .
       $cart_checkout_page_url .
       '" size="60">
<br>' .
       __(
           "If checked the visitor will be redirected to the Checkout page after a product is added to the cart. You must enter a URL in the Checkout Page URL field for this to work.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<!-- Cart reset option for better user experience after payment completion -->
<tr valign="top">
<th scope="row">' .
       __(
           "Reset Cart After Redirection to Return Page",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</th>
<td><input type="checkbox" name="wpus_shopping_cart_reset_after_redirection_to_return_page" value="1" ' .
       $wpus_shopping_cart_reset_after_redirection_to_return_page .
       '>
<br>' .
       __(
           "If checked the shopping cart will be reset when the customer lands on the return URL (Thank You) page.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</td>
</tr>

<!-- Advanced 3-Step Cart Configuration -->
<!-- This section configures an optional intermediate form step between cart and PayPal checkout -->
<tr valign="top">
<th scope="row">' .
       __("3 steps cart form URL", "wp-ultra-simple-paypal-shopping-cart") .
       '</th>
<td><input type="text" name="cart_validate_url" value="' .
       $cart_validate_url .
       '" size="100"><p>' .
       __(
           "Configure this URL if you like to have a form as step 2, before the final paypal cart (use [validate_wp_shopping_cart] shortcod on th first step cart page). Leave empty if you not need this.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '<br/>
	' .
       __(
           'You can install and use <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact form 7</a> for example and set your form with the following informations.',
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       ':
	<ol>
		<li>' .
       __("Go to edit your form", "wp-ultra-simple-paypal-shopping-cart") .
       ',</li>
		<li>' .
       __(
           'Scroll down and go to "Additional Settings" text area',
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       ',</li>
		<li>' .
       __(
           "Paste => on_sent_ok: \"location = 'http://example.com/mycart';\" (replace http://example.com/mycart by your own URL) ",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       ',</li>
		<li>' .
       __(
           "And create http://example.com/mycart page if not existing, plus past [show_wp_shopping_cart] shortcode",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</li>
	</ol>
	' .
       __(
           "This will permit to receive user's input before go on paypal final's validation.",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '<br/>
	' .
       __(
           "The customer will be redirected to cart with paypal button after successful form submit",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       '</p>
	</td>
</tr>

</table>

</div>
	<!-- Form submission button for saving all configuration changes -->
	<div class="submit">
		<input type="submit" class="button-primary" name="info_update" value="' .
       __("Update Options", "wp-ultra-simple-paypal-shopping-cart") .
       '">
	</div>
</form>
</div>
</div>';

   // Plugin promotion and rating request section
   echo "<p>" .
       __(
           "Like the WP Ultra Simple Paypal Shopping Cart Plugin?",
           "wp-ultra-simple-paypal-shopping-cart"
       ) .
       ' <a href="https://wordpress.org/plugins/wp-ultra-simple-paypal-shopping-cart/" target="_blank">' .
       __("Give it a good rating", "wp-ultra-simple-paypal-shopping-cart") .
       "</a></p>";
}

/**
 * Main options page wrapper function
 * 
 * This function serves as the main entry point for displaying the plugin's options page.
 * It provides the page structure with proper WordPress admin styling and calls the
 * main options display function.
 * 
 * @since 1.0.0
 * @return void Outputs HTML content directly to the browser
 */
function wp_cart_options()
{
    // Output the main page wrapper with WordPress admin styling
    echo '<div class="wrap"><h2>' .
        __(
            "WP Ultra simple Paypal Cart Options",
            "wp-ultra-simple-paypal-shopping-cart"
        ) .
        "</h2>";
    
    // Create the main content area using WordPress post-style layout
    echo '<div id="poststuff"><div id="post-body">';
    
    // Call the main function that renders all the options content
    show_wp_cart_options_page();
    
    // Close the content area divs
    echo "</div></div>";
    echo "</div>";
}

/**
 * Register the options page in WordPress admin menu
 * 
 * This function adds the plugin's options page to the WordPress admin menu system.
 * It creates a new submenu item under the Settings menu with proper permissions
 * and links to the main options function.
 * 
 * Uses WordPress add_options_page() to integrate with the standard admin interface.
 * Only users with 'manage_options' capability can access this page.
 * 
 * @since 1.0.0
 * @return void
 */
function wp_cart_options_page()
{
    add_options_page(
        // Page title (shown in browser title bar and page heading)
        __(
            "WP Ultra simple Paypal Cart",
            "wp-ultra-simple-paypal-shopping-cart"
        ),
        // Menu title (shown in admin sidebar menu)
        __("Ultra simple Cart", "wp-ultra-simple-paypal-shopping-cart"),
        // Required capability to access this page
        "manage_options",
        // Menu slug (unique identifier for this page)
        "wp-ultra-simple-paypal-shopping-cart/wpussc-option.php",
        // Callback function that renders the page content
        "wp_cart_options"
    );
}

/**
 * Hook the options page registration into WordPress admin menu system
 * 
 * This action hook ensures that the plugin's options page is properly registered
 * when the WordPress admin menu is being constructed. The 'admin_menu' action
 * is the standard way to add custom admin pages to WordPress.
 * 
 * @since 1.0.0
 */
add_action("admin_menu", "wp_cart_options_page");

?>
