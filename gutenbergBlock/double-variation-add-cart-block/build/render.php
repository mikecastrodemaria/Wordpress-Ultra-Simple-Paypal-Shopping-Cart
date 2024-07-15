<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
// "[wp_cart:". $productName . ":price:" . $productPrice . ":var1";
// [wp_cart:Test Product:price:[Size|Small,1.10|Medium,2.10|Large,3.10]:end]
// [wp_cart:PRODUCT-NAME:price:PRODUCT-PRICE:var1[VARIATION-NAME|VARIATION1|VARIATION2|VARIATION3]:end] ?>
<?php $productName = isset($attributes["productName"]) ? $attributes["productName"] : "Product name";
$productPrice = isset($attributes["productPrice"]) ? $attributes["productPrice"] : "0";
$variation1Name = isset($attributes["variation1Name"]) ? $attributes["variation1Name"] : "Size";
$variation1Number = isset($attributes["variation2Number"]) ? $attributes["variation1Number"] : 3;
$variations1 = isset($attributes["variations1"]) ? $attributes["variations1"] : array("Small", "Medium", "Large");
$variation2Name = isset($attributes["variation2Name"]) ? $attributes["variation2Name"] : "Size";
$variation2Number = isset($attributes["variation2Number"]) ? $attributes["variation2Number"] : 3;
$variations2 = isset($attributes["variations2"]) ? $attributes["variations2"] : array("Small", "Medium", "Large");
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
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
    <?php echo print_wp_cart_action($content) ?>
</p>