
    <?php
    // Chargement des fonctionnalités du thème enfant

    add_action( 'wp_enqueue_scripts', function() {
        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    });

    // Inclusion des fonctionnalités séparées
    require_once get_stylesheet_directory() . '/inc/svg-support.php';
    require_once get_stylesheet_directory() . '/inc/meta-description.php';
    require_once get_stylesheet_directory() . '/inc/canonical.php';
    require_once get_stylesheet_directory() . '/inc/meta-robots.php';
    require_once get_stylesheet_directory() . '/inc/opengraph-twitter.php';
    require_once get_stylesheet_directory() . '/inc/sitemap-generator.php';
    require_once get_stylesheet_directory() . '/inc/performance-optim.php';
    require_once get_stylesheet_directory() . '/inc/woocommerce-optim.php';
    require_once get_stylesheet_directory() . '/inc/woocommerce-hooks.php';
    require_once get_stylesheet_directory() . '/inc/woocommerce-canonical.php';