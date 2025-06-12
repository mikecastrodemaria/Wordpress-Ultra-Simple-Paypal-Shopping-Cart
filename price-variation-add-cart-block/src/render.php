<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
// "[wp_cart:". $productName . ":price:" . $productPrice . ":var1";
// [wp_cart:Test Product:price:[Size|Small,1.10|Medium,2.10|Large,3.10]:end]
// [wp_cart:PRODUCT-NAME:price:PRODUCT-PRICE:var1[VARIATION-NAME|VARIATION1|VARIATION2|VARIATION3]:end] ?>
<?php $productName = isset($attributes["productName"]) ? $attributes["productName"] : "Product name";
$variationName = isset($attributes["variationName"]) ? $attributes["variationName"] : "Size";
$variationNumber = isset($attributes["variationNumber"]) ? $attributes["variationNumber"] : 3;
$variations = isset($attributes["variations"]) ? $attributes["variations"] : array(array("Small", 15), array("Medium", 25), array("Large", 35));
$varlist = "[" . $variationName;
for ($i = 0; $i < $variationNumber; $i++) {
    $varlist .= "|" . $variations[$i][0] . "," . $variations[$i][1];
}
$varlist .= "]";
$content = "[wp_cart:" . $productName . ":price:" . $varlist . ":end]";
// error_log(var_export($content, true), 3, "D:/Xampp/htdocs/wordpress/wp-content/plugins/wp-ultra-simple-paypal-shopping-cart/debug.log");
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
    <?php echo print_wp_cart_action($content) ?>
</p>