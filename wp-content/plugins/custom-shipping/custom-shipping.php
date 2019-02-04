<?php
/**
 * Plugin Name: Custom-shipping
  * Description: Custom Shipping Method for WooCommerce
 * Version: 1.0.0
 * Text Domain: cuship
 */

if( ! defined(ABSPATH)){
    die();
}

/**
 * check if woocommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    add_action('woocommerce_shipping_init','custom_shipping_');
}

?>

