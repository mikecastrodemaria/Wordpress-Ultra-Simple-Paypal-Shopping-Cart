<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
// "[wp_cart:". $productName . ":price:" . $productPrice . ":var1";
// [wp_cart:Test Product:price:[Size|Small,1.10|Medium,2.10|Large,3.10]:end]
// [wp_cart:PRODUCT-NAME:price:PRODUCT-PRICE:var1[VARIATION-NAME|VARIATION1|VARIATION2|VARIATION3]:end] 
?>
<?php
$returnUrl = isset($attributes['returnUrl']) ? $attributes['returnUrl'] : '';
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
        <div class="label-container">
            <label for="street">' . __("Street", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <label for="complement">' . __("Complement", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <label for="city">' . __("City", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <label for="country">' . __("Country", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
        </div>
        <div class="input-container">
            <input type="text" id="street" name="street-address" autocomplete="on" style="flex:1" required>
            <input type="text" id="complement" name="complement" autocomplete="on" style="flex:1">
            <input type="text" id="city" name="address-level2" autocomplete="on" style="flex:1" required>
            <label for="zip">' . __("Zip Code", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <input type="text" id="zip" name="postal-code" autocomplete="on" required>
            <input type="text" id="country" name="country" autocomplete="on" style="flex:1" required>
    </div>





    <div class="address-components-container">
        <div class="two-entry-container">
            <div class="two-label-address-container">
                <label for="street">' . __("Street", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
                <br>
                <label for="complement">' . __("Complement", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            </div>
            <div class="two-input-address-container">
                <input type="text" id="street" name="street-address" autocomplete="on" style="flex:1" required>
                <br>
                <input type="text" id="complement" name="complement" autocomplete="on" style="flex:1">
            </div>
        </div>


            
            
        </div>
        <div class="two-entry-container">
            <div class="address-component" style="width:55%;margin-right:10px;justify-content: space-between">
                <label for="city">' . __("City", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
                <input type="text" id="city" name="address-level2" autocomplete="on" style="width: 72%" required>
            </div>
            <div class="address-component" style="flex:1;justify-content: space-between">
                <label for="zip">' . __("Zip Code", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
                <input type="text" id="zip" name="postal-code" autocomplete="on" required>
            </div>
            
        </div>
        <div class="address-component" style="justify-content: space-between">
            <label for="country">' . __("Country", "wp-ultra-simple-paypal-shopping-cart") . ':</label>
            <input type="text" id="country" name="country" autocomplete="on" style="width: 85%" required>
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
// Input for name, email, and phone number
echo ($postString);



?>
<p <?php echo get_block_wrapper_attributes(); ?>>
    <?php ?>
</p>