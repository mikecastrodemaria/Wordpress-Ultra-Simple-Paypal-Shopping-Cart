  <?php
/**
 * WP Ultra Simple PayPal Shopping Cart Plugin
 * 
 * @package WP_Ultra_Simple_PayPal_Shopping_Cart
 * @author Mike Castro Demaria
 * @copyright 2024 SuperSonique Studio
 * @license GPL v2 or later
 * @version 5.0.2
 * 
 * Plugin Name: WP Ultra simple Paypal Cart
 * Version: 5.0.2
 * Plugin URI: https://supersonique-studio.com
 * Author: Mike Castro Demaria
 * Author URI: https://supersonique-studio.com
 * Description: WP Ultra simple Paypal Cart Plugin, ultra simply and easely add Shopping Cart in your WP using post or page ( you need to <a href="http://j.mp/paypal-create-account" target="_blank">create a PayPal account</a> and go to <a href="options-general.php?page=wp-ultra-simple-paypal-shopping-cart/wpussc-option.php">plugin configuration panel</a>.
 * Different features are available like PayPal sandbox test, price Variations, shipping Variations, unlimited extra variations label, interface text's personalization, CSS call for button, etc.
 * Text Domain: wp-ultra-simple-paypal-shopping-cart
 * Domain Path: /languages
 */

/**
 * This program is free software; you can redistribute it
 * under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// PayPal account creation link: http://j.mp/paypal-create-account => https://www.paypal.com/fr/mrb/pal=CH4PZVAK2GJAJ

/**
 * Initializes and starts a PHP session if one is not already active
 * 
 * This function ensures that sessions are available for storing cart data.
 * It checks if a session is already started before attempting to start a new one
 * to avoid "session already started" warnings.
 * 
 * @since 1.0.0
 * @return void
 */
function wuspsc_startsession()
{
    // Check if session is not already started
    if (session_id() == "" || !isset($_SESSION)) {
        // Start new session for cart data storage
        session_start();
    }
}
// Hook the session start function to WordPress init action with priority 1
add_action("init", "wuspsc_startsession", 1);

/**
 * Adds a "Settings" link to the plugin's entry in the WordPress admin plugins list
 * 
 * This function modifies the action links array for this plugin to include
 * a direct link to the plugin's settings page, making it easier for users
 * to access the configuration options.
 * 
 * @since 1.0.0
 * @param array $links Array of existing plugin action links
 * @return array Modified array with settings link prepended
 */
function wuspsc_settings_link($links)
{
    $plugin_id = "wp-ultra-simple-paypal-shopping-cart";
    
    // Create settings link with proper escaping and internationalization
    $settings_link =
        '<a href="options-general.php?page=' .
        $plugin_id .
        '%2Fwpussc-option.php">' .
        __("Settings", "wp-ultra-simple-paypal-shopping-cart") .
        "</a>";
        
    // Add settings link to the beginning of the links array
    array_unshift($links, $settings_link);
    return $links;
}

// Get the plugin basename and register the settings link filter
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", "wuspsc_settings_link");

/**
 * Plugin Constants Definition Section
 * 
 * These constants define essential plugin paths, URLs, and version information
 * used throughout the plugin. Each constant is only defined if it doesn't
 * already exist to prevent redefinition errors.
 */

// Plugin version constant - used for cache busting and compatibility checks
if (!defined("WUSPSC_VERSION")) {
    define("WUSPSC_VERSION", "5.0.2");
}

// Base URL for the plugin directory - used for including assets
if (!defined("WUSPSC_CART_URL")) {
    define("WUSPSC_CART_URL", plugins_url("", __FILE__));
}

// Physical path to the plugin directory - used for file includes
if (!defined("WUSPSC_PLUGIN_DIR")) {
    define("WUSPSC_PLUGIN_DIR", plugin_dir_path(__FILE__));
}

// Plugin basename - used for WordPress plugin identification
if (!defined("WUSPSC_PLUGIN_BASENAME")) {
    define("WUSPSC_PLUGIN_BASENAME", plugin_basename(__FILE__));
}

// Plugin directory name - extracted from basename
if (!defined("WUSPSC_PLUGIN_DIRNAME")) {
    define("WUSPSC_PLUGIN_DIRNAME", dirname(WUSPSC_PLUGIN_BASENAME));
}

// Plugin URL - used for linking to plugin resources
if (!defined("WUSPSC_PLUGIN_URL")) {
    define("WUSPSC_PLUGIN_URL", plugin_dir_url(__FILE__));
}

// Images directory URL - used for loading plugin images and icons
if (!defined("WUSPSC_PLUGIN_IMAGES_URL")) {
    define("WUSPSC_PLUGIN_IMAGES_URL", WUSPSC_PLUGIN_URL . "images/");
}

/**
 * Required Plugin Files
 * 
 * These files contain essential functions, options, widgets, and blocks
 * needed for the shopping cart functionality.
 */
require "up-function.php";        // Utility and helper functions
require "wpussc-function.php";    // Core shopping cart functions
require "wpussc-option.php";      // Plugin options and settings
require "wpussc-widget.php";      // WordPress widget functionality
// require "blockCreator/price-variation-add-cart-block.php"; // Gutenberg block (commented out)
require "wpussc-gblock.php";      // Gutenberg block integration
require "wpussc-discount.php";    // Discount code functionality

/**
 * PayPal Return Handling Section
 * 
 * This section handles customers returning from PayPal after payment.
 * It processes both successful payments and regular returns, updating
 * discount usage counters and clearing the cart as appropriate.
 */

// Handle merchant return link from PayPal
if (isset($_GET["merchant_return_link"])) {
    $merchant_return_link = esc_url_raw($_GET["merchant_return_link"]);
    if (!empty($merchant_return_link)) {
        // Update discount code usage counter before cart reset
        if (isset($_SESSION["wpussc_discount_code"])) {
            wpussc_increment_discount_usage(
                $_SESSION["wpussc_discount_code"]
            );
        }
        // Clear the cart and redirect to return page
        reset_wp_cart();
        header("Location: " . get_option("cart_return_from_paypal_url"));
    }
}

// Handle PayPal IPN with gross amount (successful payment indicator)
if (isset($_GET["mc_gross"])) {
    $mc_gross = esc_url_raw($_GET["mc_gross"]);
    if ($mc_gross > 0) {
        // Update discount code usage counter before cart reset
        if (isset($_SESSION["wpussc_discount_code"])) {
            wpussc_increment_discount_usage(
                $_SESSION["wpussc_discount_code"]
            );
        }
        // Clear the cart and redirect to return page
        reset_wp_cart();
        header("Location: " . get_option("cart_return_from_paypal_url"));
    }
}
//Clear the cart if the customer landed on the thank you page
if (get_option("wpus_shopping_cart_reset_after_redirection_to_return_page")) {
    if (
        get_option("cart_return_from_paypal_url") == get_permalink($post->ID)
    ) {
        reset_wp_cart();
    }
}

// Ensure session is started for cart operations
if (session_id() == "" || !isset($_SESSION)) {
    session_start();
}

/**
 * Shopping Cart POST Data Processing Section
 * 
 * This section handles all POST requests related to cart operations:
 * - Adding items to cart
 * - Updating item quantities
 * - Removing items from cart
 * 
 * All user input is sanitized and validated before processing.
 */
if (!empty($_POST)) {
    
    /**
     * Add Item to Cart Processing
     * 
     * Processes requests to add new items to the shopping cart.
     * Handles both new items and quantity updates for existing items.
     */
    if (!empty($_POST["addcart"])) {
        // Set cart-in-use cookie for cache compatibility
        $domain_url = $_SERVER["SERVER_NAME"];
        $cookie_domain = str_replace("www", "", $domain_url);
        // Cookie expires in 6 hours - prevents cached pages when cart is active
        setcookie("cart_in_use", "true", time() + 21600, "/", $cookie_domain);

        // Initialize products array from session or create new
        $products = empty($products)
            ? $_SESSION["ultraSimpleCart"]
            : $products;
        $new = true;

        if (!is_array($products)) {
            $products = [];
        }

        // Check if product already exists in cart to update quantity
        foreach ($products as $key => $item) {
            if (
                $item["name"] !=
                stripslashes(sanitize_text_field($_POST["product"]))
            ) {
                continue;
            }
            // Add new quantity to existing item
            $item["quantity"] += intval($_POST["quantity"]);
            unset($products[$key]);
            array_push($products, $item);
            $new = false;
        }

        // Add new product if not found in existing cart
        if ($new == true) {
            // Handle price format - check for comma-separated values
            $price =
                strpos($_POST["price"], ",") !== false
                    ? floatval(explode(",", $_POST["price"])[1])
                    : floatval($_POST["price"]);
                    
            // Sanitize and prepare product data
            $item_number = !empty($_POST["item_number"])
                ? esc_attr(sanitize_text_field($_POST["item_number"]))
                : "";

            $quantity = !empty($_POST["quantity"])
                ? sanitize_text_field($_POST["quantity"])
                : "";
            $shipping = !empty($_POST["shipping"])
                ? sanitize_text_field($_POST["shipping"])
                : "";
            $cartLink = !empty($_POST["cartLink"])
                ? sanitize_text_field($_POST["cartLink"])
                : "";

            // Create product array with all necessary data
            $product = [
                "name" => stripslashes(
                    sanitize_text_field($_POST["product"])
                ),
                "price" => $price,
                "quantity" => jasonwoof_format_int_1($quantity),
                "shipping" => $shipping,
                "cartLink" => $cartLink,
                "item_number" => $item_number,
            ];
            array_push($products, $product);
        }

        // Sort products and update session
        sort($products);
        $_SESSION["ultraSimpleCart"] = $products;

        // Handle auto-redirect to checkout if enabled
        if (get_option("wpus_shopping_cart_auto_redirect_to_checkout_page")) {
            $checkout_url = get_option("cart_checkout_page_url");
            if (empty($checkout_url)) {
                echo "<br ><strong>" .
                    __(
                        "Shopping Cart Configuration Error! You must specify a value in the 'Checkout Page URL' field for the automatic redirection feature to work!",
                        "WUSPSC"
                    ) .
                    "</strong><br >";
            } else {
                $redirection_parameter = "Location: " . $checkout_url;
                header($redirection_parameter);
                exit();
            }
        }
    }

      if (isset($_POST["quantity"]) && empty($_POST["addcart"])) {
          $products = $_SESSION["ultraSimpleCart"];
          if (!empty($products)) {
              foreach ($products as $key => $item) {
                  //if((stripslashes($item['name']) == stripslashes($_POST['product'])) && $_POST['quantity'])

                  $quantity = !empty($_POST["quantity"])
                      ? intval(stripslashes($_POST["quantity"]))
                      : 0;

                  //if(!empty($_POST['cquantity']) ){ $cquantity = $_POST['cquantity']; }

                  $name = !empty($item["name"])
                      ? get_the_name(
                          sanitize_text_field(stripslashes($item["name"]))
                      )
                      : "";
                  $pproduct = !empty($_POST["product"])
                      ? stripslashes(sanitize_text_field($_POST["product"]))
                      : "";

                  if ($name === $pproduct && !empty($quantity)) {
                      $cquantity = intval($_POST["cquantity"]);
                      $item["quantity"] =
                          $cquantity != $quantity ? $quantity : $cquantity;
                      unset($products[$key]);
                      array_push($products, $item);
                  } elseif ($name === $pproduct && empty($quantity)) {
                      unset($products[$key]);
                  }
              }
              sort($products);
          }
          $_SESSION["ultraSimpleCart"] = $products;
      }

      if (
          (empty(
              isset($_POST["addcart"])
                  ? sanitize_text_field($_POST["addcart"])
                  : ""
          ) &&
              !empty(
                  isset($_POST["delcart"])
                      ? sanitize_text_field($_POST["delcart"])
                      : ""
              )) ||
          empty(isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0)
      ) {
          $products = $_SESSION["ultraSimpleCart"];
          if (!empty($products)) {
              foreach ($products as $key => $item) {
                  if (
                      $item["name"] ==
                      stripslashes(
                          isset($_POST["product"])
                              ? sanitize_text_field($_POST["product"])
                              : ""
                      )
                  ) {
                      unset($products[$key]);
                  }
              }

              $_SESSION["ultraSimpleCart"] = $products;
          }
      }

      if (!empty($_POST["updateqty"]) && intval($_POST["quantity"])) {
          $products = $_SESSION["ultraSimpleCart"];

          $idx = isset($_POST["idx"]) ? (int) $_POST["idx"] : 0;

          $quantity =
              !empty($_POST["quantity"]) &&
              !empty($_POST["cquantity"]) &&
              (int) $_POST["quantity"] !== (int) $_POST["cquantity"]
                  ? (int) stripslashes($_POST["quantity"])
                  : (int) stripslashes($_POST["cquantity"]);

          $name = !empty($products[$idx]["name"])
              ? get_the_name(stripslashes($products[$idx]["name"]))
              : "";
          $pproduct = !empty($_POST["product"])
              ? stripslashes(sanitize_text_field($_POST["product"]))
              : "";

          if ($name === $pproduct && $quantity > 0) {
              $products[$idx]["quantity"] = $quantity;
          } else {
              unset($products[$idx]);
          }

          $_SESSION["ultraSimpleCart"] = $products;
      }
  }

/**
 * Generates and displays the complete shopping cart interface
 * 
 * This is the main function that renders the shopping cart display including:
 * - Cart items with quantities and prices
 * - Discount code functionality
 * - Shipping calculations
 * - Tax calculations (VAT)
 * - Payment buttons (PayPal integration)
 * - Cart totals and subtotals
 * 
 * The function handles both single-step (direct PayPal) and two-step 
 * (validation then PayPal) checkout processes.
 * 
 * @since 1.0.0
 * @param string $step The checkout process step - "paypal" for direct checkout, "validate" for two-step
 * @param string $type The display context type - affects CSS classes applied to the cart
 * @return string Complete HTML markup for the shopping cart interface
 * 
 * @global object $plugin_dir_name WordPress plugin directory name
 * @global object $post Current WordPress post object
 */
function print_wpus_shopping_cart($step = "paypal", $type = "page")
{
    global $plugin_dir_name;
    global $post;
    $output = empty($output) ? "" : $output;

    // Check if empty cart should be displayed
    $emptyCartAllowDisplay = get_option("wpus_shopping_cart_empty_hide");
    
    // Display empty cart message if cart is empty
    if (!cart_not_empty()) {
        $output = get_the_empty_cart_content();
    }

    /**
     * Retrieve Plugin Configuration Settings
     * 
     * These settings control various aspects of cart behavior,
     * appearance, and payment processing.
     */
    $admin_email = get_bloginfo("admin_email");
    $wp_use_aff_platform = get_option("wp_use_aff_platform");
    $cart_payment_currency = get_option("cart_payment_currency");
    $cart_currency_symbol = get_option("cart_currency_symbol");
    $cart_paypal_email = get_option("cart_paypal_email");
    $cart_validate_url = get_option("cart_validate_url");
    $display_vat = get_option("display_vat");

    // Set default values with fallbacks
    $email = !empty($admin_email) ? $admin_email : "";
    $use_affiliate_platform = !empty($wp_use_aff_platform)
        ? $wp_use_aff_platform
        : "";
    $defaultCurrency = !empty($cart_payment_currency)
        ? $cart_payment_currency
        : "";
    $defaultSymbol = !empty($cart_currency_symbol)
        ? $cart_currency_symbol
        : "";
    $defaultEmail = !empty($cart_paypal_email) ? $cart_paypal_email : "";
    $cart_validation_url = !empty($cart_validate_url)
        ? $cart_validate_url
        : "";
    $display_vat = !empty($display_vat) ? $display_vat : "";

    // Initialize cart calculation variables
    $count = 1;
    $total_items = 0;
    $total = 0;
    $form = "";

    // Set currency defaults with internationalization fallbacks
    if (!empty($defaultCurrency)) {
        $paypal_currency = $defaultCurrency;
    } else {
        $paypal_currency = __("USD", "wp-ultra-simple-paypal-shopping-cart");
    }
    if (!empty($defaultSymbol)) {
        $paypal_symbol = $defaultSymbol;
    } else {
        $paypal_symbol = __('$', "wp-ultra-simple-paypal-shopping-cart");
    }

    if (!empty($defaultEmail)) {
        $email = $defaultEmail;
    }

    $decimal = ".";
    $urls = "";

    // Setup PayPal return URL
    $return = get_option("cart_return_from_paypal_url");

    if (!empty($return)) {
        $urls .=
            '<input type="hidden" name="return" value="' .
            esc_attr($return) .
            '" >';
    }

    // Setup PayPal notification URL for IPN (Instant Payment Notification)
    $notify = WUSPSC_CART_URL . "/paypal.php";

    if (!empty($notify)) {
        $urls .=
            '<input type="hidden" name="notify_url" value="' .
            esc_attr($notify) .
            '" >';
    }

    // Get cart title with fallback
    $wp_cart_title = get_option("wp_cart_title");
    $title = !empty($wp_cart_title)
        ? $wp_cart_title
        : __("Your Shopping Cart", "wp-ultra-simple-paypal-shopping-cart");

    // Apply CSS class based on display type
    if (!empty($type)) {
        $type_class = " " . $type;
    } else {
        $type_class = "";
    }

    // Start cart HTML container
    $output .=
        '<div class="shopping_cart' .
        esc_attr($type_class) .
        '" id="shopping_cart">';

    $wp_cart_update_quantity_text = get_option("wp_cart_update_quantiy_text");

    /**
     * Process Cart Items and Calculate Totals
     * 
     * This section iterates through all cart items to:
     * - Calculate subtotals and shipping costs
     * - Generate HTML for each cart item
     * - Prepare PayPal form data
     */
    if (
        $_SESSION["ultraSimpleCart"] &&
        is_array($_SESSION["ultraSimpleCart"])
    ) {
        $output .= '<table style="width: 100%;">';

        $item_total_shipping = 0;
        $item_total_shipping = empty($item_total_shipping)
            ? 0
            : $item_total_shipping;

        // Display item count if not hidden in settings
        if (get_option("wpus_shopping_cart_items_in_cart_hide") == "") {
            $itemsInCart = count($_SESSION["ultraSimpleCart"]);
            // Use proper singular/plural text based on item count
            $itemsInCartString = _n(
                get_option("singular_items_text"),
                get_option("plural_items_text"),
                $itemsInCart
            );

            $output .=
                '
        <tr id="item_in_cart">
        <th class="left" colspan="4">' .
                $itemsInCart .
                " " .
                $itemsInCartString .
                '</th>
        </tr>';
        }
        
        // Calculate totals by iterating through cart items
        foreach ($_SESSION["ultraSimpleCart"] as $item) {
            $total += (int) $item["quantity"] * get_the_price($item["price"]);

            $item_shipping = get_the_price($item["shipping"]);
            $wpus_shopping_cart_shipping_per_items = get_option(
                "wpus_shopping_cart_shipping_per_items"
            );

            // Calculate shipping: per item or per item type
            if (!empty($wpus_shopping_cart_shipping_per_items)) {
                $item_total_shipping += (int) $item_shipping;
            } else {
                $item_total_shipping +=
                    (int) $item_shipping * (int) $item["quantity"];
            }

            $total_items += (int) $item["quantity"];
        }

        // Calculate final shipping cost with base shipping
        if ($item_total_shipping == 0) {
            $baseShipping = get_option("cart_base_shipping_cost");
            $postage_cost = $item_total_shipping + $baseShipping;
          } else {
              //$postage_cost = 0;
              $postage_cost = $item_total_shipping;
          }

          $cart_free_shipping_threshold = get_option(
              "cart_free_shipping_threshold"
          );
          if (
              !empty($cart_free_shipping_threshold) &&
              $total > $cart_free_shipping_threshold
          ) {
              $postage_cost = 0;
          }

          $output .=
              '
		<tr class="cart_labels">
			<th class="header-name">' .
              esc_html(get_option("item_name_text")) .
              '</th>
			<th class="header-qty">' .
              esc_html(get_option("qualtity_text")) .
              '</th>
			<th class="header-price">' .
              esc_html(get_option("price_text")) .
              '</th>
			<th class="header-del">&nbsp;</th>
		</tr>';

          $total_vat = 0;
          $output_name = empty($output_name) ? "" : $output_name;

          foreach ($_SESSION["ultraSimpleCart"] as $item) {
              $price = get_the_price($item["price"]);

              $name = get_the_name($item["name"]);

              $wpus_display_link_in_cart = get_option(
                  "wpus_display_link_in_cart"
              );

              /* need improvement to the next version 4.3.8*/
              $wpus_display_thumbnail_in_cart = get_option(
                  "wpus_display_thumbnail_in_cart"
              );
              $wpus_thumbnail_in_cart_width = get_option(
                  "wpus_thumbnail_in_cart_width"
              );
              $wpus_thumbnail_in_cart_height = get_option(
                  "wpus_thumbnail_in_cart_height"
              );

              if (
                  empty($wpus_thumbnail_in_cart_width) ||
                  empty($wpus_thumbnail_in_cart_width)
              ) {
                  $wpus_thumbnail_in_cart_width = 32;
                  $wpus_thumbnail_in_cart_height = 32;
              }

              if (!empty($wpus_display_link_in_cart)) {
                  /* need improvement to the next version 4.3.8*/
                  if (empty($wpus_display_thumbnail_in_cart)) {
                      $product_thumbnail = get_the_post_thumbnail(
                          $post->ID,
                          [
                              $wpus_thumbnail_in_cart_width,
                              $wpus_thumbnail_in_cart_height,
                          ],
                          ["class" => "marginleft product-thumb"]
                      );
                  } else {
                      $product_thumbnail = "";
                  }

                  $cartProductDisplayLink =
                      '<a href="' .
                      esc_attr($item["cartLink"]) .
                      '">' .
                      $product_thumbnail .
                      esc_html($name) .
                      "</a>";
              } else {
                  $cartProductDisplayLink = $name;
              }

              $output_name =
                  "<input type=\"hidden\" name=\"product\" value=\"" .
                  esc_attr($name) .
                  "\" >";

              $pquantity = esc_attr($item["quantity"]);
              $pname = esc_attr($item["name"]);
              $idx = (int) $count - 1;
              $output .=
                  "
			<tr id=\"cartcontent\" class=\"cartcontent\">
        <td class=\"cartLink\">{$cartProductDisplayLink}</td>
        <td class=\"center\">
          <form method=\"post\"  action=\"\" name='pcquantity' class=\"update-quantity-form\">
          
        <div class=\"div-quantity\"> 
          {$output_name}
          <input type=\"hidden\" name=\"idx\" value=\"{$idx}\" >
          <input type=\"hidden\" name=\"cquantity\" value=\"{$pquantity}\" >
          <input type=\"hidden\" name=\"updateqty\" value=\"1\" >
          <input class=\"iquantity\" type=\"text\" name=\"quantity\" value=\"{$pquantity}\" size=\"1\"  onchange=\"this.form.submit();\" >
          <button type=\"submit\" class=\"pinfo reload-button\" title=\"Reload\">
            <ion-icon name=\"reload-circle\" class='reload-icon'></ion-icon>
          </button>
        </div>
          </form>
        </td>
				<td class=\"left\">" .
                  print_payment_currency(
                      $price * $item["quantity"],
                      $paypal_symbol,
                      $decimal,
                      get_option("cart_currency_symbol_order")
                  ) .
                  "</td>
				<td class=\"trash-cell\">
          <form method=\"post\" action=\"\" class='remove-item'>
              <input type=\"hidden\" name=\"product\" value=\"{$pname}\" >
              <input type=\"hidden\" name=\"delcart\" value=\"1\" >
              <button class=\"remove-button\" type=\"submit\" name=\"remove_item\" value=\"1\" title=\"" .
                  get_option("remove_text") .
                  "\">
                  <ion-icon name='trash' class='trash-button'></ion-icon>
              </button>
          </form>
        </td>
			</tr>
			";

              $item_number = !empty($item["item_number"])
                  ? esc_attr($item["item_number"])
                  : "item-{$count}";

              $form .=
                  "
				<input type=\"hidden\" name=\"item_name_{$count}\" value=\"" .
                  esc_attr($name) .
                  "\" >
				<input type=\"hidden\" name=\"amount_{$count}\" value='" .
                  esc_attr($price) .
                  "' >
				<input type=\"hidden\" name=\"quantity_{$count}\" value=\"" .
                  esc_attr($item["quantity"]) .
                  "\" >
				<input type='hidden' name='item_number' value='" .
                  $item_number .
                  "' >
			";

              $item_tax =
                  !empty($display_vat) && is_numeric($display_vat)
                      ? round(($price * $display_vat) / 100, 2)
                      : 0;
              if (!empty($item_tax)) {
                  $form .=
                      "<input type=\"hidden\" name=\"tax_{$count}\"  value=\"" .
                      esc_attr($item_tax) .
                      "\">";
                  $total_vat = $total_vat + $item_tax * $item["quantity"];
              }

              $count++;
          }

          if (!get_option("wpus_shopping_cart_use_profile_shipping")) {
              $postage_cost = number_format($postage_cost, 2);
              $form .=
                  "<input type=\"hidden\" name=\"shipping_1\" value='" .
                  esc_attr($postage_cost) .
                  "' >";
          }

          if (get_option("wpus_shopping_cart_collect_address")) {
              //force address collection
              $form .=
                  "<input type=\"hidden\" name=\"no_shipping\" value=\"2\" >";
          }
      }

      $count--;

      if ($count) {
          // Sous-total initial
          $output .=
              "
			<tr id=\"subrow\" class=\"subrow\">
				<td colspan=\"2\" class=\"subcell subcelllabel\">" .
              esc_html(get_option("subtotal_text")) .
              ": </td>
				<td colspan=\"2\" class=\"subcell left subcellamount\">" .
              print_payment_currency(
                  $total,
                  $paypal_symbol,
                  $decimal,
                  get_option("cart_currency_symbol_order")
              ) .
              "</td></tr>";

          // Discount code section
          if (get_option("wpussc_discount_enabled")) {
              $discount_applied = 0;
              $discount_code = "";

              // Si un code de réduction a été soumis
              if (
                  isset($_POST["wpussc_apply_discount"]) &&
                  !empty($_POST["wpussc_discount_code"])
              ) {
                  $discount_code = sanitize_text_field(
                      $_POST["wpussc_discount_code"]
                  );
                  $discount_result = wpussc_apply_discount(
                      $total,
                      $discount_code
                  );

                  if ($discount_result["valid"]) {
                      $discount_applied = $discount_result["discount_amount"];

                      // Stockage du code dans la session pour l'utiliser à la validation finale
                      $_SESSION["wpussc_discount_code"] = $discount_code;
                      $_SESSION["wpussc_discount_amount"] = $discount_applied;

                      // Total after discount
                      $total = $discount_result["total"];
                  } else {
                      // Afficher le message d'erreur spécifique
                      $error_message = !empty($discount_result["message"])
                          ? $discount_result["message"]
                          : esc_html(
                              get_option("wpussc_discount_invalid_code_message")
                          );

                      $output .=
                          "<tr><td colspan=\"4\" class=\"error-message\">" .
                          $error_message .
                          "</td></tr>";
                  }
              } elseif (
                  isset($_SESSION["wpussc_discount_code"]) &&
                  isset($_SESSION["wpussc_discount_amount"])
              ) {
                  // Récupération d'un code déjà appliqué
                  $discount_code = $_SESSION["wpussc_discount_code"];
                  $discount_applied = $_SESSION["wpussc_discount_amount"];

                  $total = $total - $discount_applied;
              }

              // Afficher le champ de saisie du code de réduction
              $output .=
                  "
          <tr id=\"discountcoderow\" class=\"discountcoderow\">
            <td colspan=\"4\">
              <form method=\"post\" action=\"\" class=\"discount-form\">
                <label for=\"wpussc_discount_code\">" .
                  esc_html(get_option("wpussc_discount_code_label")) .
                  ":</label>
                <input type=\"text\" name=\"wpussc_discount_code\" id=\"wpussc_discount_code\" value=\"" .
                  esc_attr($discount_code) .
                  "\" size=\"20\">
                <input type=\"submit\" name=\"wpussc_apply_discount\" value=\"" .
                  esc_attr(get_option("wpussc_discount_button_text")) .
                  "\" class=\"apply-discount-button\">
              </form>
            </td>
          </tr>";

              // Afficher la réduction appliquée si elle existe
              if ($discount_applied > 0) {
                  $output .=
                      "
        <tr id=\"discountrow\" class=\"discountrow\">
          <td colspan=\"2\" class=\"discountcell discountlabel\">" .
                      esc_html(get_option("wpussc_discount_label")) .
                      ": </td>
          <td colspan=\"2\" class=\"discountcell left discountamount\">-" .
                      print_payment_currency(
                          $discount_applied,
                          $paypal_symbol,
                          $decimal,
                          get_option("cart_currency_symbol_order")
                      ) .
                      "</td>
        </tr>";
              }
          }

          // Frais d'expédition
          if ($postage_cost != 0) {
              $output .=
                  "<tr id=\"shiprow\" class=\"shiprow\">
				<td colspan=\"2\" class=\"shipcell shiplabel\">" .
                  esc_html(get_option("shipping_text")) .
                  ": </td>
				<td colspan=\"2\" class=\"shipcell left shipamount\">" .
                  print_payment_currency(
                      $postage_cost,
                      $paypal_symbol,
                      $decimal,
                      get_option("cart_currency_symbol_order")
                  ) .
                  "</td></tr>";
          } elseif (
              $postage_cost == 0 &&
              get_option("display_free_shipping") == 1
          ) {
              $output .=
                  "<tr id=\"shiprow\" class=\"shiprow\">
				<td colspan=\"2\" class=\"shipcell shiplabel\">" .
                  esc_html(get_option("shipping_text")) .
                  ": </td>
				<td colspan=\"2\" class=\"shipcell left shipamount\">" .
                  __("Free", "wp-ultra-simple-paypal-shopping-cart") .
                  "</td></tr>";
          }

          if (!empty($display_vat) && is_numeric($display_vat)) {
              $vat = (($total - $postage_cost) * $display_vat) / 100;
              $vat_text = get_option("vat_text");

              $output .=
                  "
			<tr id=\"vatrow\" class=\"vatrow\">
				<td colspan=\"2\" class=\"vatcell vatlabel\">" .
                  esc_html($vat_text) .
                  " (" .
                  esc_html($display_vat) .
                  "%): </td>
				<td colspan=\"2\" class=\"vatcell left vatamount\">" .
                  print_payment_currency(
                      $total_vat,
                      $paypal_symbol,
                      $decimal,
                      get_option("cart_currency_symbol_order")
                  ) .
                  "</td></tr>";

              $total = $total + $total_vat;
          }

          // Calcul du total final (avec réduction si applicable)
          $final_total = $total + $postage_cost;

          $output .= sprintf(
              "
   		<tr id=\"totalrow\" class=\"totalrow\">
   			<td colspan=\"2\" class=\"totalcel totallabel\">%s: </td>
   			<td colspan=\"2\" class=\"totalcel left totalamount\">%s</td>
   		</tr>
   		<tr id=\"ppcheckout\" class=\"ppcheckout\">
   			<td colspan=\"4\">",
              get_option("total_text"),
              print_payment_currency(
                  $final_total,
                  $paypal_symbol,
                  $decimal,
                  get_option("cart_currency_symbol_order")
              )
          );

          // Ajouter le montant de la réduction pour PayPal
          if (
              isset($_SESSION["wpussc_discount_amount"]) &&
              $_SESSION["wpussc_discount_amount"] > 0
          ) {
              $form .=
                  "<input type='hidden' name='discount_amount_cart' value='" .
                  esc_attr($_SESSION["wpussc_discount_amount"]) .
                  "'>";
          }

          // 1 or 2 step caddy
          switch ($step) {
              // 2 steps caddy with valication firsl
              case "validate":
                  $output .=
                      '<form action="' .
                      esc_url($cart_validation_url) .
                      '" method="post">' .
                      $form;
                  if ($count) {
                      $output .=
                          '<input type="submit" class="step_sub button-primary" name="validate" value="' .
                          __(
                              "Proceed to Checkout &raquo;",
                              "wp-ultra-simple-paypal-shopping-cart"
                          ) .
                          '" >';
                  }
                  $output .= "</form>";
                  break;

              // 1 step with direct paypal submit
              case "paypal":
                  // base URL to play with PayPal
                  // https://www.sandbox.paypal.com/cgi-bin/webscr (paypal testing site)
                  // https://www.paypal.com/us/cgi-bin/webscr (paypal live site )
                  // just for information

                  //is the sandbox is activated
                  $is_sandbox =
                      get_option("is_sandbox") == "1" ? "sandbox." : "";

                  $language = __UP_detect_language();

                  // checkout button default
                  $checkout_style = get_option("checkout_style");
                  if (empty($checkout_style)) {
                      $checkout_style = "wp_checkout_button";
                  }

                  // default use no text on button
                  $displaybuttontext =
                      " name=" .
                      __("Checkout", "wp-ultra-simple-paypal-shopping-cart") .
                      "value=" .
                      __("Checkout", "wp-ultra-simple-paypal-shopping-cart");

                  $css_id_checkout_style = "paypalbutton";
                  $css_class_checkout_style = "paypalbutton";

                  // use custom button ot not
                  if (get_option("use_custom_button") == "1") {
                      // add custom style + default paypalbutton calls for jQuery call
                      $css_id_checkout_style = $checkout_style;
                      $css_class_checkout_style =
                          "paypalbutton " . $checkout_style;

                      // use text on button
                      $displaybuttontext =
                          ' name="' .
                          __(
                              "Checkout",
                              "wp-ultra-simple-paypal-shopping-cart"
                          ) .
                          '" value="' .
                          esc_attr(get_option("checkoutButtonName")) .
                          '"';
                  }
                  // qty display
                  $output .=
                      '<span class="pinfo" style="font-weight: bold; color: red;">' .
                      esc_html($wp_cart_update_quantity_text) .
                      "</span>";

                  // start the form to submit cart
                  $output .=
                      "<form action=\"https://www." .
                      $is_sandbox .
                      "paypal.com/cgi-bin/webscr\" method=\"post\">$form";

                  // all data sent to paypal
                  $output .=
                      $urls .
                      '<input type="hidden" name="business" value="' .
                      esc_attr($email) .
                      '"><input type="hidden" name="currency_code" value="' .
                      esc_attr($paypal_currency) .
                      '"><input type="hidden" name="cmd" value="_cart"><input type="hidden" name="upload" value="1"><input type="hidden" name="rm" value="2"><input type="hidden" name="mrb" value="DKBDRZGU62JYC"><input type="hidden" name="bn" value="UltraProdSAS_SI_ADHOC">';

                  if (!empty($vat)) {
                      $output .=
                          '<input type="hidden" name="tax_cart" value="' .
                          esc_attr($total_vat) .
                          '" >';
                  }

                  if ($use_affiliate_platform) {
                      $output .= wp_cart_add_custom_field();
                  }

                  // set the button
                  $output .=
                      '<input type="submit" id="' .
                      esc_attr($css_id_checkout_style) .
                      '" class="' .
                      esc_attr($css_class_checkout_style) .
                      '"' .
                      esc_attr($displaybuttontext) .
                      ' alt="' .
                      __(
                          "Make payments with PayPal - it's fast, free and secure!",
                          "WUSPSC"
                      ) .
                      '" >';

                  $output .= "</form>";

                  // on vide la session
                  $_SESSION["wpussc_discount_code"] = "";
                  $_SESSION["wpussc_discount_amount"] = 0;
                  // end the form to submit cart
                  break;
          }
      }

      $output .= "
   	</td></tr>
	</table></div>
	";

      return $output;
  }

/**
 * Processes shortcode content to generate add-to-cart buttons with product variations
 * 
 * This function scans post/page content for [wp_cart:...] shortcodes and replaces them
 * with complete HTML forms containing:
 * - Product selection forms with variations (price, shipping, custom attributes)
 * - Quantity selectors (if enabled)
 * - Add to cart buttons with proper styling
 * - Hidden fields for cart processing
 * 
 * Shortcode format: [wp_cart:Product Name:price:10.00:shipping:2.00:end]
 * Supports variations: [wp_cart:Product:price:[Size|Small,5.00|Large,10.00]:end]
 * 
 * @since 1.0.0
 * @param string $content The post/page content to process
 * @return string Content with shortcodes replaced by cart forms
 * 
 * @global object $post Current WordPress post object for generating cart links
 */
function print_wp_cart_action($content)
{
    global $post;
    $css_class_addcart_style = "";
    $displaybuttontext = "";
    $addToCartButton = "";

    // Define default button text with internationalization
    $default_addcart_button_name = __(
        "Add to Cart",
        "wp-ultra-simple-paypal-shopping-cart"
    );
    $default_checkout_button_name = __(
        "Checkout",
        "wp-ultra-simple-paypal-shopping-cart"
    );

    // Determine layout spacing based on inline display setting
    if (
        get_option("display_product_inline") &&
        get_option("display_product_inline") == 1
    ) {
        $option_break = " ";  // Use space for inline layout
    } else {
        $option_break = "<br/>";  // Use line break for block layout
    }

    // Setup default button attributes
    $displaybuttontext = sprintf(
        ' name="%s" value="%s" alt="%s" ',
        $default_addcart_button_name,
        $default_addcart_button_name,
        $default_addcart_button_name
    );

    // Apply custom button styling if enabled
    if (get_option("use_custom_button") == "1") {
        $addcart_button_name = esc_attr(get_option("addToCartButtonName"));

        if (empty($addcart_button_name)) {
            $addcart_button_name = $default_addcart_button_name;
        }

        $checkout_button_name = esc_attr(get_option("checkoutButtonName"));

        if (empty($checkout_button_name)) {
            $checkout_button_name = $default_checkout_button_name;
        }

        $add_cartstyle = esc_attr(get_option("add_cartstyle"));
        if (empty($add_cartstyle)) {
            $add_cartstyle = "wp_cart_button";
        }

        $css_class_addcart_style = " " . $add_cartstyle;

        // Update button text with custom values
        $displaybuttontext = sprintf(
            ' name="%s" value="%s" alt="%s" ',
            $addcart_button_name,
            $addcart_button_name,
            $addcart_button_name
        );
    }

    // Generate the add to cart button HTML
    $addToCartButton .= sprintf(
        '<input type="submit" class="vsubmit submit" %s %s >',
        $css_class_addcart_style,
        $displaybuttontext
    );

    // Regular expression to find cart shortcodes in content
    $pattern = "#\[wp_cart:.+:price:.+:end]#";
    preg_match_all($pattern, $content, $matches);

    // Process each found shortcode
    foreach ($matches[0] as $match) {
        $replacement = "";
        $var_output = "";
        $pos = strpos($match, ":var1");

        /**
         * Process Product Variations
         * 
         * Handles custom product variations like size, color, etc.
         * Format: var1[Label|Option1|Option2|Option3]:
         */
        $isVariation = strpos($match, ":var");
        if ($isVariation > 0) {
            $match_tmp = $match;

            $pattern = "#var.*\[.*]:#";
            preg_match_all($pattern, $match_tmp, $matchesVar);

            $allVariationArray = explode(":", $matchesVar[0][0]);

            // Process each variation group
            for ($i = 0; $i < sizeof($allVariationArray) - 1; $i++) {
                preg_match(
                    "/(?P<vname>\w+)\[([^\)]*)\].*/",
                    $allVariationArray[$i],
                    $variationMatches
                );

                $allVariationLabelArray = explode("|", $variationMatches[2]);
                $variation_name = $allVariationLabelArray[0];

                // Generate variation label
                $var_output .=
                    '<label class="lv-label ' .
                    __UP_strtolower_utf8($variation_name) .
                    '">' .
                    esc_html($variation_name) .
                    " :</label>";
                $variationNameValue = $i + 1;

                // Generate variation dropdown
                $var_output .=
                    '<select class="sv-select variation' .
                    $variationNameValue .
                    '" name="variation' .
                    $variationNameValue .
                    '" onchange="ReadForm (this.form, false);">';
                    
                // Add options to dropdown (skip first element which is the label)
                for ($v = 1; $v < sizeof($allVariationLabelArray); $v++) {
                    $var_output .=
                        '<option value="' .
                        esc_attr($allVariationLabelArray[$v]) .
                        '">' .
                        esc_html($allVariationLabelArray[$v]) .
                        "</option>";
                }
                $var_output .= "</select>" . $option_break;
            }
        }

        /**
         * Parse Shortcode Components
         * 
         * Extract product name, price, shipping, and other parameters
         * from the shortcode string.
         */
        // Remove shortcode wrapper to get core data
        $pattern = "[wp_cart:";
        $m = str_replace($pattern, "", $match);
        $pattern = "price:";
        $m = str_replace($pattern, "", $m);
        $pattern = "shipping:";
        $m = str_replace($pattern, "", $m);
        $pattern = ":end]";
        $m = str_replace($pattern, "", $m);

        $pieces = explode(":", $m);

        // Generate product name display if enabled
        if (
            !empty(get_option("display_product_name")) &&
            get_option("display_product_name") == 1
        ) {
            $product_name = '<span class="product_name">';
            $product_name .= esc_html($pieces["0"]);
            if (get_option("display_product_inline") == 1) {
                $product_name .= " :";
            }
            $product_name .= "</span>";
        } else {
            $product_name = "";
        }

        $replacement .= $product_name;

        // Start form generation
        $replacement .=
            '<form method="post" class="wpus-cart-button-form ' .
            __UP_strtolower_utf8($pieces["0"]) .
            '" action="" onsubmit="return ReadForm(this, true);">';

        /**
         * Quantity Input Generation
         * 
         * Creates quantity selector if enabled in settings,
         * otherwise uses hidden field with default value of 1.
         */
        if (
            get_option("display_quantity") &&
            get_option("display_quantity") == 1
        ) {
            $replacement .=
                '<label class="lp-label quantity">' .
                get_option("qualtity_text") .
                ' :</label><input type="text" name="quantity" value="1" size="4" >' .
                $option_break;
        } else {
            $replacement .=
                '<input type="hidden" name="quantity" value="1" >';
        }

        // Add any variation dropdowns
        if (!empty($var_output)) {
            $replacement .= $var_output;
        }

        // Add product name as hidden field
        $replacement .=
            '<input type="hidden" name="product" value="' .
            esc_attr($pieces["0"]) .
            '" >';

        /**
         * Price Variation Processing
         * 
         * Handles both fixed prices and price variations.
         * Price variations format: [Label|Option1,Price1|Option2,Price2]
         */
        if (preg_match("/\[(?P<label>\w+)/", $pieces["1"])) {
            // Process price variations
            $priceVariation = str_replace("[", "", $pieces["1"]);
            $priceVariation = str_replace("]", "", $priceVariation);
            $priceVariationArray = explode("|", $priceVariation);
            $variation_name = $priceVariationArray[0];
            
            $replacement .=
                '<label class="lp-label ' .
                __UP_strtolower_utf8($variation_name) .
                '">' .
                esc_html($variation_name) .
                ' :</label><select class="sp-select price" name="price">';
                
            // Process each price option (skip first element which is label)
            for ($i = 1; $i < sizeof($priceVariationArray); $i++) {
                $priceDigitAndWordArray = explode(
                    ",",
                    $priceVariationArray[$i]
                );

                $replacement .=
                    '<option value="' .
                    esc_attr($priceDigitAndWordArray[0]) .
                    "," .
                    esc_attr(
                        $priceDigitAndWordArray[1] .
                            "." .
                            $priceDigitAndWordArray[2]
                    ) .
                    '">' .
                    esc_html($priceDigitAndWordArray[0]) .
                    "</option>";
            }

            $replacement .= "</select>" . $option_break;
        } elseif ($pieces["1"] != "") {
            // Fixed price - add as hidden field
            $replacement .=
                '<input type="hidden" name="price" value="' .
                esc_attr($pieces["1"]) .
                '" >';
        } else {
            echo _("Error: no price configured");
        }

        /**
         * Shipping Variation Processing
         * 
         * Similar to price variations but for shipping costs.
         * Only processes if shipping is specified in shortcode.
         */
        if (strpos($match, ":shipping") > 0) {
            if (preg_match("/\[(?P<label>\w+)/", $pieces["2"])) {
                // Process shipping variations
                $shippingVariation = str_replace("[", "", $pieces["2"]);
                $shippingVariation = str_replace("]", "", $shippingVariation);
                $shippingVariationArray = explode("|", $shippingVariation);
                $variation_name = $shippingVariationArray[0];

                $replacement .=
                    '<label class="vs-label ' .
                    __UP_strtolower_utf8($variation_name) .
                    '">' .
                    $variation_name .
                    ' :</label><select class="sv-select shipping" name="shipping">';
                    
                // Process shipping options
                for ($i = 1; $i < sizeof($shippingVariationArray); $i++) {
                    $shippingDigitAndWordArray = explode(
                        ",",
                        $shippingVariationArray[$i]
                    );
                    $replacement .=
                        '<option value="' .
                        esc_attr($shippingDigitAndWordArray[0]) .
                        "," .
                        esc_attr($shippingDigitAndWordArray[1]) .
                        '">' .
                        esc_html($shippingDigitAndWordArray[0]) .
                        "</option>";
                }

                $replacement .= "</select>" . $option_break;
            } elseif ($pieces["2"] != "") {
                // Fixed shipping cost
                $replacement .=
                    '<input type="hidden" name="shipping" value="' .
                    esc_attr($pieces["2"]) .
                    '" >';
            }
        }

        /**
         * Add Required Hidden Fields
         * 
         * These fields are necessary for proper cart processing
         * and maintaining product links.
         */
        $replacement .=
            '<input type="hidden" name="product_tmp" value="' .
            esc_attr($pieces["0"]) .
            '" >';
            
        // Add cart link for product page reference
        if (!empty($post->ID)) {
            $replacement .=
                '<input type="hidden" name="cartLink" value="' .
                get_permalink($post->ID) .
                '" >';
        }
        
        // Add cart action identifier and submit button
        $replacement .= '<input type="hidden" name="addcart" value="1" >';
        $replacement .= $addToCartButton;
        $replacement .= "</form>";
        
        // Replace the shortcode with generated form
        $content = str_replace($match, $replacement, $content);
    }

    return $content;
}

/**
 * Legacy function for generating add-to-cart buttons programmatically
 * 
 * @deprecated This function needs refactoring for compatibility with print_wp_cart_action()
 * @todo Clean up this function and create compatibility with print_wp_cart_action
 * 
 * Generates a complete add-to-cart form for a specific product when called
 * directly from PHP code. Supports price and shipping variations.
 * 
 * @since 1.0.0
 * @param string $name Product name to display and use for cart identification
 * @param string|array $price Product price - can be fixed price or variation array
 * @param string|array $shipping Shipping cost - can be fixed cost or variation array (default: 0)
 * @return string Complete HTML form for adding the product to cart
 * 
 * @global object $post Current WordPress post object for generating cart links
 */
function print_wp_cart_button_for_product($name, $price, $shipping = 0)
{
    global $post;
    $addToCartButton = "";
    
    // Define default internationalized text
    $default_add_to_cart_str = __(
        "Add to Cart",
        "wp-ultra-simple-paypal-shopping-cart"
    );

    // Setup default button attributes
    $displaybuttontext = sprintf(
        ' name="%s" value="%s" alt="%s" ',
        $default_add_to_cart_str,
        $default_add_to_cart_str,
        $default_add_to_cart_str
    );

    // Apply custom styling if enabled
    if (get_option("use_custom_button") == "1") {
        $addcart_button_name = get_option("addToCartButtonName");

        if (empty($addcart_button_name)) {
            $addcart_button_name = $default_add_to_cart_str;
        }

        $add_cartstyle = get_option("add_cartstyle");

        if (!$add_cartstyle) {
            $add_cartstyle = "wp_cart_button";
        }

        $css_class_addcart_style = " " . $add_cartstyle;

        // Update button text with custom values
        $displaybuttontext = sprintf(
            ' name="%s" value="%s" alt="%s" ',
            $addcart_button_name,
            $addcart_button_name,
            $addcart_button_name
        );
    }

    // Generate the submit button HTML
    $addToCartButton .= sprintf(
        '<input type="submit" class="vsubmit submit %s" %s >',
        $css_class_addcart_style,
        $displaybuttontext
    );

    // Start form generation with product-specific class
    $replacement =
        '<form method="post" class="wpus-cart-button-form ' .
        __UP_strtolower_utf8($name) .
        '" action="" onsubmit="return ReadForm(this, true);">';
        
    // Add variations if provided (currently undefined variable - needs fixing)
    if (!empty($var_output)) {
        $replacement .= $var_output;
    }

    // Add basic product information
    $replacement .=
        '<input type="hidden" name="product" value="' .
        esc_attr($name) .
        '" >';
    $replacement .= '<input type="hidden" name="quantity" value="1" >';

    /**
     * Price Processing Section
     * 
     * Handles both fixed prices and price variations.
     * Price variations should be in format: [Label|Option1,Price1|Option2,Price2]
     */
    if (preg_match("/\[(?P<label>\w+)/", $price)) {
        // Process price variations
        $priceVariation = str_replace("[", "", $price);
        $priceVariation = str_replace("]", "", $priceVariation);
        $priceVariationArray = explode("|", $priceVariation);
        $variation_name = $priceVariationArray[0];

        $replacement .=
            '<label class="vp-label ' .
            __UP_strtolower_utf8($variation_name) .
            '">' .
            esc_html($variation_name) .
            ' :</label><select class="sp-select price" name="price">';
            
        // Generate price options (skip first element which is the label)
        for ($i = 1; $i < sizeof($priceVariationArray); $i++) {
            $priceDigitAndWordArray = explode(",", $priceVariationArray[$i]);
            $replacement .=
                '<option value="' .
                esc_attr($priceDigitAndWordArray[0]) .
                "," .
                esc_attr($priceDigitAndWordArray[1]) .
                '">' .
                esc_html($priceDigitAndWordArray[0]) .
                "</option>";
        }
        $replacement .= "</select>";
    } elseif ($price != "") {
        // Fixed price - add as hidden field
        $replacement .=
            '<input type="hidden" name="price" value="' .
            esc_attr($price) .
            '" >';
    } else {
        echo _("Error: no price configured");
    }

    /**
     * Shipping Processing Section
     * 
     * Handles shipping costs if provided. Supports both fixed costs and variations.
     */
    if ($shipping != "") {
        if (preg_match("/\[(?P<label>\w+)/", $shipping)) {
            // Process shipping variations
            $shippingVariation = str_replace("[", "", $shipping);
            $shippingVariation = str_replace("]", "", $shippingVariation);
            $shippingVariationArray = explode("|", $shippingVariation);
            $variation_name = $shippingVariationArray[0];

            $replacement .=
                '<label class="vs-label ' .
                __UP_strtolower_utf8($variation_name) .
                '">' .
                esc_html($variation_name) .
                ' :</label><select class="sv-select shipping" name="shipping">';
                
            // Generate shipping options
            for ($i = 1; $i < sizeof($shippingVariationArray); $i++) {
                $shippingDigitAndWordArray = explode(
                    ",",
                    $shippingVariationArray[$i]
                );
                $replacement .=
                    '<option value="' .
                    esc_attr($shippingDigitAndWordArray[0]) .
                    "," .
                    esc_attr($shippingDigitAndWordArray[1]) .
                    '">' .
                    esc_html($shippingDigitAndWordArray[0]) .
                    "</option>";
            }
            $replacement .= "</select>";
        } elseif ($shipping > 0) {
            // Fixed shipping cost
            $replacement .=
                '<input type="hidden" name="shipping" value="' .
                esc_attr($shipping) .
                '" >';
        }
    }

    // Add required hidden fields for cart processing
    $replacement .=
        '<input type="hidden" name="product_tmp" value="' .
        esc_html($name) .
        '" >';
    $replacement .=
        '<input type="hidden" name="cartLink" value="' .
        get_permalink($post->ID) .
        '" >';
    $replacement .= '<input type="hidden" name="addcart" value="1" >';
    $replacement .= $addToCartButton;
    $replacement .= "</form>";

    return $replacement;
}

/**
 * Future Enhancement Function Template
 * 
 * @todo Future implementation for enhanced product button generation
 * 
 * This commented code shows the planned structure for a more robust
 * product button generation function that would handle:
 * - Multiple data types for price (int, float, array)
 * - Multiple data types for shipping (int, float, array) 
 * - Product variations as strings or arrays
 * - Automatic shortcode generation and processing
 * 
 * The function would validate input types and generate appropriate
 * shortcode content to be processed by print_wp_cart_action().
 */
/*
function print_wp_cart_button_for_product($name, $price, $shipping=0, $variation='' ) {

  // test if the price have variations
  if ($price != '') {

    if ( is_int($price) || is_float($price)) {
      $pricevalue = round($price,2);
    } elseif (is_array($price)) {

    }
  }

  // test if the shipping have variations
  if ($shipping != '') {

    if ( is_int($shipping) || is_float($shipping)) {
      $shippingvalue = round($shipping,2);
    } elseif (is_array($shipping)) {

    }
  }

  // test if there is variations of the same product
  if ($variation != '') {

    if ( is_string($variation) ) {

    } elseif (is_array($variation)) {

    }
  }

  $content = "[wp_cart:".$name.":price:".$pricevalue.":shipping:".$shippingvalue.$variationvalue":end]";
  print_wp_cart_action($content);
}
*/

/**
 * WordPress Hooks and Filters Registration
 * 
 * These hooks integrate the shopping cart functionality with WordPress:
 * - Content filters for processing shortcodes
 * - Shortcode registrations for various cart displays
 * - JavaScript includes for form processing
 */

// Register content filters to process cart shortcodes and display cart
add_filter("the_content", "print_wp_cart_action", 11);
add_filter("the_content", "shopping_cart_show");

// Register shortcodes for different cart display modes
add_shortcode("show_wp_shopping_cart", "show_wpus_shopping_cart_handler");
add_shortcode("show_wpus_shopping_cart", "show_wpus_shopping_cart_handler");
add_shortcode(
    "validate_wp_shopping_cart",
    "validate_wpus_shopping_cart_handler"
);
add_shortcode(
    "validate_wpus_shopping_cart",
    "validate_wpus_shopping_cart_handler"
);
add_shortcode(
    "always_show_wpus_shopping_cart",
    "us_always_show_cart_handler"
);

// Add JavaScript for form validation and processing
add_action("wp_head", "wp_cart_add_read_form_javascript");

