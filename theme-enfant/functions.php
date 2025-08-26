
   <?php
    add_action( 'wp_enqueue_scripts', function() {
        $parent_style = 'parent-style';
        wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( $parent_style ),
            wp_get_theme()->get('Version')
        );
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