<?php
// Optimisations et hooks WooCommerce
// Désactiver les scripts WooCommerce sur les pages où ils ne sont pas nécessaires
add_action('wp_enqueue_scripts', function() {
    if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');
        wp_dequeue_script('wc-add-to-cart');
        wp_dequeue_script('woocommerce');
        wp_dequeue_script('wc-cart-fragments');
    }
}, 99);