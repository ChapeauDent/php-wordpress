<?php
// Hooks personnalisés WooCommerce

// Suppression du tri et des wrappers en bas de page WooCommerce
add_action( 'init', function () {
    // WooCommerce : supprimer le tri en bas
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );

    // Storefront : supprimer le wrapper du tri (sinon espace vide)
    remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
    remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );
}, 99 );
