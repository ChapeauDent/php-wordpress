<?php
// Canonical et SEO spécifiques WooCommerce

// Canonical sur catégories produits WooCommerce (sans paramètres de tri)
add_action( 'wp_head', function() {
    if ( function_exists('is_product_category') && is_product_category() ) {
        global $wp;
        $url = home_url( add_query_arg( array(), $wp->request ) );
        echo '<link rel="canonical" href="' . esc_url( $url ) . '" />';
    }
});

// Canonical sur la page boutique WooCommerce (shop)
add_action( 'wp_head', function() {
    if ( function_exists('is_shop') && is_shop() ) {
        $shop_id = wc_get_page_id('shop');
        if ( $shop_id && $shop_id > 0 ) {
            $url = get_permalink( $shop_id );
            echo '<link rel="canonical" href="' . esc_url( $url ) . '" />';
        }
    }
});

// Canonical pour les produits WooCommerce
add_action( 'wp_head', function() {
    if ( function_exists('is_product') && is_product() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '" />';
    }
});
