<?php
/**
 * Supersonique Studio WPUSSC PayPal IPN Handler
 * Version: v2.0.0
 *
 * Secure PayPal IPN endpoint:
 * - Loads the WordPress environment (required by get_option()).
 * - Validates the IPN over HTTPS with certificate verification.
 * - Sanitizes all incoming data and escapes all database queries.
 * - Writes debug logs to the private uploads directory only.
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

// Load the WordPress environment so get_option() and $wpdb are available.
$wp_load = dirname(__DIR__) . '/wp-load.php';
if (!file_exists($wp_load)) {
    // Fallback: the plugin may live in a nested plugins subfolder.
    $wp_load = dirname(dirname(__DIR__)) . '/wp-load.php';
}
if (file_exists($wp_load)) {
    require_once $wp_load;
} else {
    status_header(500);
    exit('WordPress environment could not be loaded.');
}

if (!defined('ABSPATH')) {
    exit;
}

class paypal_ipn_handler
{
    /** @var string PayPal URL to validate the IPN against */
    private $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';

    /** @var string Holds the last error encountered */
    private $last_error = '';

    /** @var bool Whether to log IPN results */
    private $ipn_log = false;

    /** @var string Full path of the IPN log file (private uploads dir) */
    private $ipn_log_file = '';

    /** @var string Holds the IPN response from PayPal */
    private $ipn_response = '';

    /** @var array Contains the sanitized POST values for the IPN */
    private $ipn_data = array();

    /**
     * Builds the private log file path in the uploads directory.
     */
    public function __construct()
    {
        $uploads = wp_get_upload_dir();
        $log_dir = trailingslashit($uploads['basedir']) . 'wpussc-logs';

        if (!is_dir($log_dir)) {
            wp_mkdir_p($log_dir);
            // Block direct HTTP access to log files (Apache).
            @file_put_contents($log_dir . '/.htaccess', "Require all denied\n");
        }

        $this->ipn_log_file = $log_dir . '/ipn_debug.log';
    }

    /**
     * Enables sandbox mode (validation against the PayPal sandbox).
     */
    public function enable_sandbox()
    {
        $this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }

    /**
     * Writes a debug message to the private log file.
     */
    public function debug_log($message, $success = true, $end = false)
    {
        if (!$this->ipn_log) {
            return;
        }

        $time = current_time('mysql');
        $status = $success ? 'SUCCESS' : 'ERROR';
        $entry = sprintf("[%s] [%s] %s\n", $time, $status, $message);

        if ($end) {
            $entry .= "\n------------------------------------------------------------------\n";
        }

        @file_put_contents($this->ipn_log_file, $entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Validates the IPN by posting the data back to PayPal over HTTPS
     * with full certificate verification.
     *
     * @return bool True if PayPal reports VERIFIED.
     */
    public function validate_ipn()
    {
        if (empty($_POST)) {
            $this->debug_log('No POST data received.', false);
            return false;
        }

        $post_string = 'cmd=_notify-validate';
        foreach ($_POST as $field => $value) {
            $field = sanitize_key($field);
            $value = stripslashes((string) $value);
            $this->ipn_data[$field] = $value;
            $post_string .= '&' . $field . '=' . rawurlencode($value);
        }

        $this->debug_log('Post string: ' . $post_string);

        $response = wp_remote_post(
            $this->paypal_url,
            array(
                'timeout'    => 30,
                'user-agent' => 'WPUSC/' . WUSPSC_VERSION,
                'body'       => $post_string,
                'sslverify'  => true,
            )
        );

        if (is_wp_error($response)) {
            $this->debug_log('Connection to ' . $this->paypal_url . ' failed: ' . $response->get_error_message(), false);
            return false;
        }

        $this->ipn_response = wp_remote_retrieve_body($response);
        $this->debug_log('IPN response: ' . $this->ipn_response);

        if (strpos($this->ipn_response, 'VERIFIED') !== false) {
            $this->debug_log('IPN successfully verified.');
            return true;
        }

        $this->debug_log('IPN validation failed.', false);
        return false;
    }

    /**
     * Validates the payment details (currency) and registers affiliate
     * sales if the WP Affiliate Platform plugin is active.
     *
     * @return bool True on successful processing.
     */
    public function validate_and_dispatch_product()
    {
        global $wpdb;

        $cart_items = array();

        if (isset($this->ipn_data['txn_type']) && $this->ipn_data['txn_type'] === 'cart') {
            $this->debug_log('Transaction Type: Shopping Cart');
            $num_cart_items = isset($this->ipn_data['num_cart_items']) ? absint($this->ipn_data['num_cart_items']) : 0;
            $this->debug_log('Number of Cart Items: ' . $num_cart_items);

            for ($i = 1; $i <= $num_cart_items; $i++) {
                $cart_items[] = array(
                    'item_number' => isset($this->ipn_data['item_number' . $i]) ? sanitize_text_field($this->ipn_data['item_number' . $i]) : '',
                    'item_name'   => isset($this->ipn_data['item_name' . $i]) ? sanitize_text_field($this->ipn_data['item_name' . $i]) : '',
                    'quantity'    => isset($this->ipn_data['quantity' . $i]) ? absint($this->ipn_data['quantity' . $i]) : 0,
                    'mc_gross'    => isset($this->ipn_data['mc_gross_' . $i]) ? (float) $this->ipn_data['mc_gross_' . $i] : 0,
                    'mc_currency' => isset($this->ipn_data['mc_currency']) ? sanitize_text_field($this->ipn_data['mc_currency']) : '',
                );
            }
        } else {
            $this->debug_log('Transaction Type: Buy Now');
            $cart_items[] = array(
                'item_number' => isset($this->ipn_data['item_number']) ? sanitize_text_field($this->ipn_data['item_number']) : '',
                'item_name'   => isset($this->ipn_data['item_name']) ? sanitize_text_field($this->ipn_data['item_name']) : '',
                'quantity'    => isset($this->ipn_data['quantity']) ? absint($this->ipn_data['quantity']) : 0,
                'mc_gross'    => isset($this->ipn_data['mc_gross']) ? (float) $this->ipn_data['mc_gross'] : 0,
                'mc_currency' => isset($this->ipn_data['mc_currency']) ? sanitize_text_field($this->ipn_data['mc_currency']) : '',
            );
        }

        $payment_currency = get_option('cart_payment_currency');

        foreach ($cart_items as $cart_item) {
            $this->debug_log('Item Number: ' . $cart_item['item_number']);
            $this->debug_log('Item Name: ' . $cart_item['item_name']);
            $this->debug_log('Item Quantity: ' . $cart_item['quantity']);
            $this->debug_log('Item Total: ' . $cart_item['mc_gross']);
            $this->debug_log('Item Currency: ' . $cart_item['mc_currency']);

            if ($payment_currency !== $cart_item['mc_currency']) {
                $this->debug_log('Invalid Product Currency: ' . $cart_item['mc_currency'], false);
                return false;
            }
        }

        $this->debug_log('Updating Affiliate Database Table with Sales Data if Using the WP Affiliate Platform Plugin.');

        if (function_exists('wp_aff_platform_install')) {
            $this->debug_log('WP Affiliate Platform is installed, registering sale...');

            $referrer = isset($this->ipn_data['custom']) ? sanitize_text_field($this->ipn_data['custom']) : '';
            $sale_amount = isset($this->ipn_data['mc_gross']) ? (float) $this->ipn_data['mc_gross'] : 0;

            if (!empty($referrer)) {
                $affiliates_table_name = $wpdb->prefix . 'affiliates_tbl';
                $aff_sales_table = $wpdb->prefix . 'affiliates_sales_tbl';

                $affiliate = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM {$affiliates_table_name} WHERE refid = %s",
                        $referrer
                    ),
                    OBJECT
                );

                if ($affiliate) {
                    $commission_level = (float) $affiliate->commissionlevel;
                    $commission_amount = ($sale_amount * $commission_level) / 100;

                    $inserted = $wpdb->insert(
                        $aff_sales_table,
                        array(
                            'refid'       => $referrer,
                            'date'        => current_time('mysql'),
                            'time'        => current_time('mysql'),
                            'payment'     => '',
                            'sale_amount' => $sale_amount,
                            'commission'  => $commission_amount,
                        ),
                        array('%s', '%s', '%s', '%s', '%f', '%f')
                    );

                    if ($inserted) {
                        $this->debug_log('The sale has been registered in the WP Affiliates Platform Database for referrer: ' . $referrer);
                    } else {
                        $this->debug_log('Failed to register the affiliate sale.', false);
                    }
                } else {
                    $this->debug_log('No affiliate found for referrer: ' . $referrer, false);
                }
            }
        } else {
            $this->debug_log('Not Using the WP Affiliate Platform Plugin.');
        }

        return true;
    }
}

// Start of IPN handling (script execution).
$debug_enabled = !empty(get_option('wp_cart_enable_debug'));
$sandbox = !empty(get_option('is_sandbox'));

$ipn_handler_instance = new paypal_ipn_handler();
$ipn_handler_instance->ipn_log = $debug_enabled;

if ($sandbox) {
    $ipn_handler_instance->enable_sandbox();
}

$ipn_handler_instance->debug_log('Paypal Class Initiated by ' . (isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : 'unknown'));

if ($ipn_handler_instance->validate_ipn()) {
    $ipn_handler_instance->debug_log('Creating product Information to send.');
    if (!$ipn_handler_instance->validate_and_dispatch_product()) {
        $ipn_handler_instance->debug_log('IPN product validation failed.', false);
    }
}

$ipn_handler_instance->debug_log('Paypal class finished.', true, true);

status_header(200);
exit;
