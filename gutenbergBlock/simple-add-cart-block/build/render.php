<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>

<p <?php echo get_block_wrapper_attributes(); ?>><?php echo print_wp_cart_action("[wp_cart:" . $attributes["productName"] . ":price:" . $attributes["productPrice"] . ":end]") ?></p>