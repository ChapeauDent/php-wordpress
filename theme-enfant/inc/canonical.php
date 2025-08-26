<?php
// Gestion des balises canonical pour WordPress uniquement
// Les balises WooCommerce sont dans inc/woocommerce-canonical.php

// Canonical pour articles et archives WordPress
add_action( 'wp_head', function() {
    if ( is_single() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '" />';
    } elseif ( is_category() || is_tag() || is_author() || is_date() ) {
        $term_link = get_term_link( get_queried_object() );
        if ( !is_wp_error($term_link) ) {
            echo '<link rel="canonical" href="' . esc_url( $term_link ) . '" />';
        }
    } elseif ( is_home() && !is_paged() ) {
        echo '<link rel="canonical" href="' . esc_url( get_home_url() ) . '" />';
    }
});
