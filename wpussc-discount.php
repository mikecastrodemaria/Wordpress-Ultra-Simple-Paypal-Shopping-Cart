<?php

/**
 * Supersonique Studio WPUSSC Discount Codes
 * Version: v1.0.0
 *
 * This program is free software; you can redistribute it
 * under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/**
 * Creates the discount codes table when the plugin is activated.
 * 
 * This function creates a new table to store discount codes with all necessary fields
 * including code, type, amount, expiry date, usage limits, and status tracking.
 * If the table already exists, it will be dropped and recreated to avoid conflicts.
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object
 * @return void
 */
function wpussc_create_discount_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpussc_discount_codes";

    // Drop the table if it already exists to avoid conflicts during reinstallation
    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    $charset_collate = $wpdb->get_charset_collate();
    
    // Define the table structure with all necessary columns for discount management
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        code varchar(50) NOT NULL,
        type varchar(20) NOT NULL,
        amount decimal(10,2) NOT NULL,
        floor_price decimal(10,2) DEFAULT '0',
        expiry_date date DEFAULT NULL,
        usage_limit int(11) DEFAULT NULL,
        usage_count int(11) DEFAULT '0',
        status varchar(20) NOT NULL DEFAULT 'active',
        date_created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY code (code)
    ) $charset_collate;";
    
    // Use WordPress built-in function to safely create/update database tables
    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($sql);
}

// Register activation hook to create the discount table when plugin is activated
register_activation_hook(__FILE__, "wpussc_create_discount_table");

/**
 * Validates a discount code against various criteria.
 * 
 * This function checks if a discount code is valid by verifying:
 * - Code exists and is active
 * - Code has not expired
 * - Code has not reached its usage limit
 * - Minimum purchase amount requirement is met (if applicable)
 *
 * @since 1.0.0
 * @param string $code The discount code to validate
 * @param float|null $cart_total Optional. The current cart total to check against minimum purchase requirement
 * @return array Array containing validation result with 'valid' boolean and 'message' or 'discount' data
 */
function wpussc_validate_discount_code($code, $cart_total = null)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpussc_discount_codes";

    // Retrieve the discount code from database (only active codes)
    $discount = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE code = %s AND status = 'active'",
            $code
        )
    );

    // Check if the discount code exists and is active
    if (!$discount) {
        return [
            "valid" => false,
            "message" => __(
                "Invalid discount code",
                "wp-ultra-simple-paypal-shopping-cart"
            ),
        ];
    }

    // Check if the discount code has not expired
    if ($discount->expiry_date && strtotime($discount->expiry_date) < time()) {
        return [
            "valid" => false,
            "message" => __(
                "This discount code has expired",
                "wp-ultra-simple-paypal-shopping-cart"
            ),
        ];
    }

    // Check if the discount code has not reached its usage limit
    if (
        $discount->usage_limit &&
        $discount->usage_count >= $discount->usage_limit
    ) {
        return [
            "valid" => false,
            "message" => __(
                "This discount code has reached its usage limit",
                "wp-ultra-simple-paypal-shopping-cart"
            ),
        ];
    }

    // Check minimum purchase amount requirement for this discount code
    if (
        $cart_total !== null &&
        isset($discount->floor_price) &&
        $cart_total < floatval($discount->floor_price)
    ) {
        // Format the minimum price according to currency settings
        $formatted_price = print_payment_currency(
            $discount->floor_price,
            get_option("cart_currency_symbol"),
            ".",
            get_option("cart_currency_symbol_order")
        );
        return [
            "valid" => false,
            "message" => sprintf(
                __(
                    "This code requires a minimum purchase of %s",
                    "wp-ultra-simple-paypal-shopping-cart"
                ),
                $formatted_price
            ),
        ];
    }

    return ["valid" => true, "discount" => $discount];
}

/**
 * Applies a discount code to a cart total and calculates the new total.
 * 
 * This function validates the discount code first, then applies the appropriate
 * discount calculation based on the discount type (percentage or fixed amount).
 * Ensures that the final total never goes below $0.01.
 *
 * @since 1.0.0
 * @param float $total The original cart total before discount
 * @param string $code The discount code to apply
 * @return array Array containing the new total, validity status, discount amount, and any error messages
 */
function wpussc_apply_discount($total, $code)
{
    $validation_result = wpussc_validate_discount_code($code, $total);

    // Return early if the discount code is not valid
    if (!$validation_result["valid"]) {
        return [
            "total" => $total,
            "valid" => false,
            "message" => $validation_result["message"],
        ];
    }

    $discount = $validation_result["discount"];

    // Apply the discount based on its type (percentage or fixed amount)
    if ($discount->type == "percentage") {
        // Calculate the exact discount amount based on percentage
        $discounted_amount = round($total * ($discount->amount / 100), 2);
        return [
            "total" => $total - $discounted_amount,
            "valid" => true,
            "discount_amount" => $discounted_amount,
        ];
    } elseif ($discount->type == "fixed") {
        // Ensure the discount doesn't exceed the total (minimum $0.01 must remain)
        $discounted_amount = min($discount->amount, $total - 0.01);
        return [
            "total" => max(0.01, $total - $discounted_amount), // Double-check minimum of $0.01
            "valid" => true,
            "discount_amount" => $discounted_amount,
        ];
    }

    // Handle invalid discount types
    return [
        "total" => $total,
        "valid" => false,
        "message" => __(
            "Invalid discount type",
            "wp-ultra-simple-paypal-shopping-cart"
        ),
    ];
}

/**
 * Increments the usage counter for a discount code.
 * 
 * This function should be called after a successful purchase using a discount code
 * to track how many times the code has been used. This is important for codes
 * with usage limits.
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object
 * @param string $code The discount code whose usage count should be incremented
 * @return void
 */
function wpussc_increment_discount_usage($code)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpussc_discount_codes";

    // Increment the usage count by 1 for the specified discount code
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE $table_name SET usage_count = usage_count + 1 WHERE code = %s",
            $code
        )
    );
}

/**
 * Retrieves all discount codes from the database.
 * 
 * This function is primarily used in the admin interface to display
 * all discount codes in a manageable list format. Results are ordered
 * by creation date (newest first).
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object
 * @return array|object|null Array of discount code objects, or null if none found
 */
function wpussc_get_all_discount_codes()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpussc_discount_codes";

    // Return all discount codes ordered by creation date (newest first)
    return $wpdb->get_results(
        "SELECT * FROM $table_name ORDER BY date_created DESC"
    );
}

/**
 * Adds a new discount code to the database.
 * 
 * This function creates a new discount code with the specified parameters.
 * It first checks if the discount table exists and creates it if necessary,
 * then verifies that the code doesn't already exist before insertion.
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object
 * @param string $code The unique discount code string
 * @param string $type The type of discount ('percentage' or 'fixed')
 * @param float $amount The discount amount (percentage value or fixed amount)
 * @param string|null $expiry_date Optional. Expiry date in 'Y-m-d' format
 * @param int|null $usage_limit Optional. Maximum number of times the code can be used
 * @param float $floor_price Optional. Minimum purchase amount required. Default 0.01
 * @return int|false The number of rows inserted, or false on error
 */
function wpussc_add_discount_code(
    $code,
    $type,
    $amount,
    $expiry_date = null,
    $usage_limit = null,
    $floor_price = 0.01
) {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpussc_discount_codes";

    // Ensure the discount table exists before attempting to insert
    if (!wpussc_get_is_table_valid("wpussc_discount_codes")) {
        wpussc_create_discount_table();
    }

    // Check if the discount code already exists to prevent duplicates
    $exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE code = %s",
            $code
        )
    );
    if ($exists > 0) {
        return false; // Code already exists
    }

    // Insert the new discount code with all specified parameters
    $result = $wpdb->insert(
        $table_name,
        [
            "code" => $code,
            "type" => $type,
            "amount" => $amount,
            "floor_price" => $floor_price,
            "expiry_date" => $expiry_date,
            "usage_limit" => $usage_limit,
            "status" => "active",
        ],
        ["%s", "%s", "%f", "%f", "%s", "%d", "%s"] // Data format specifications
    );

    return $result;
}

/**
 * Deletes a discount code from the database.
 * 
 * This function removes a discount code by its ID. It first ensures
 * the discount table exists before attempting the deletion.
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object
 * @param int $id The ID of the discount code to delete
 * @return int|false The number of rows affected, or false on error
 */
function wpussc_delete_discount_code($id)
{
    // Ensure the discount table exists before attempting deletion
    if (!wpussc_get_is_table_valid("wpussc_discount_codes")) {
        wpussc_create_discount_table();
        return false; // Table didn't exist, so no deletion possible
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "wpussc_discount_codes";

    // Delete the discount code by its ID
    return $wpdb->query(
        $wpdb->prepare("DELETE FROM $table_name WHERE id = %d", $id)
    );
}

/**
 * Updates an existing discount code in the database.
 * 
 * This function allows modification of discount code properties by accepting
 * an array of data to update. It automatically determines the correct data
 * formats for each field type.
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object
 * @param int $id The ID of the discount code to update
 * @param array $data Associative array of field names and values to update
 * @return int|false The number of rows updated, or false on error
 */
function wpussc_update_discount_code($id, $data)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpussc_discount_codes";

    // Determine the correct data formats for each field being updated
    $formats = [];
    foreach ($data as $key => $value) {
        switch ($key) {
            case "amount":
            case "floor_price":
                $formats[] = "%f"; // Float format for decimal values
                break;
            case "usage_limit":
            case "usage_count":
                $formats[] = "%d"; // Integer format for numeric values
                break;
            default:
                $formats[] = "%s"; // String format for all other fields
                break;
        }
    }

    // Update the discount code with the provided data and appropriate formats
    return $wpdb->update($table_name, $data, ["id" => $id], $formats, ["%d"]);
}

/**
 * Initializes default options for the discount code system.
 * 
 * This function sets up default WordPress options for the discount functionality,
 * including labels, button text, and error messages. All text is properly
 * internationalized for translation support.
 *
 * @since 1.0.0
 * @return void
 */
function wpussc_init_discount_options()
{
    // Initialize default discount system options with translatable strings
    add_option("wpussc_discount_enabled", "1");
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
}

// Initialize the discount options when the plugin loads
wpussc_init_discount_options();

/**
 * Checks if a specified table exists in the WordPress database.
 * 
 * This function verifies table existence by querying the information_schema
 * database. It's used to ensure required tables are present before performing
 * database operations, preventing errors and allowing for automatic table creation.
 *
 * @since 1.0.0
 * @global wpdb $wpdb WordPress database abstraction object
 * @param string $table The table name to check (without WordPress prefix)
 * @return bool True if the table exists, false otherwise
 */
function wpussc_get_is_table_valid($table)
{
    global $wpdb;
    $table_name = $wpdb->prefix . $table;

    // Query the information_schema to check if the table exists in the current database
    $query = $wpdb->prepare(
        "SELECT COUNT(*)
        FROM information_schema.tables
        WHERE table_schema = %s
        AND table_name = %s",
        DB_NAME,
        $table_name
    );

    $result = $wpdb->get_var($query);

    // Return true if the table exists (even if empty), false otherwise
    return !empty($result);
}
