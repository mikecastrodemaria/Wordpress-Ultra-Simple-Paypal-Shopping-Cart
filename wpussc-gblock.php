<?php
add_action('plugins_loaded', 'load_my_plugin_files');

function my_is_plugin_active($plugin_path) {
    $active_plugins = get_option('active_plugins');
    if (in_array($plugin_path, $active_plugins)) {
        return true;
    }

    if (is_multisite()) {
        $active_sitewide_plugins = get_site_option('active_sitewide_plugins');
        if (isset($active_sitewide_plugins[$plugin_path])) {
            return true;
        }
    }

    return false;
}

function load_my_plugin_files() {
    require "gutenbergBlock/simple-cart-block/simple-cart-block.php";
    require "gutenbergBlock/simple-add-cart-block/simple-add-cart-block.php";
    require "gutenbergBlock/simple-variation-add-cart-block/simple-variation-add-cart-block.php";
    require "gutenbergBlock/double-variation-add-cart-block/double-variation-add-cart-block.php";
    require "gutenbergBlock/price-variation-add-cart-block/price-variation-add-cart-block.php";
    require "gutenbergBlock/shipping-variation-add-cart-block/shipping-variation-add-cart-block.php";
    require "gutenbergBlock/simple-validation-block/simple-validation-block.php";
    require "gutenbergBlock/simple-form-block/simple-form-block.php";

    if (my_is_plugin_active('elementor/elementor.php')) {
        require "elementorWidgets/elementor-simple-cart-widget/elementor-simple-cart-widget.php";
        require "elementorWidgets/elementor-simple-add-cart-widget/elementor-simple-add-cart-widget.php";
        require "elementorWidgets/elementor-simple-variation-add-cart-widget/elementor-simple-variation-add-cart-widget.php";
        require "elementorWidgets/elementor-double-variation-add-cart-widget/elementor-double-variation-add-cart-widget.php";
        require "elementorWidgets/elementor-price-variation-add-cart-widget/elementor-price-variation-add-cart-widget.php";
        require "elementorWidgets/elementor-shipping-variation-add-cart-widget/elementor-shipping-variation-add-cart-widget.php";
        require "elementorWidgets/elementor-simple-validation-widget/elementor-simple-validation-widget.php";
        require "elementorWidgets/elementor-simple-form-widget/elementor-simple-form-widget.php";
    }
}