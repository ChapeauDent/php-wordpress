<?php
// Générateur de sitemap XML et plan du site HTML avec bouton admin

add_action('admin_menu', function() {
    add_menu_page(
        'Générer Sitemap',
        'Sitemap & Plan',
        'manage_options',
        'custom-sitemap-generator',
        'custom_sitemap_generator_admin_page',
        'dashicons-schedule',
        80
    );
});

function custom_sitemap_generator_admin_page() {
    $msg = '';
    if (isset($_POST['generate_sitemap'])) {
        $msg .= custom_generate_sitemap_xml() ? '<div class="updated"><p>Sitemap XML généré !</p></div>' : '<div class="error"><p>Erreur lors de la génération du sitemap XML.</p></div>';
    }
    if (isset($_POST['generate_html'])) {
        $msg .= custom_generate_html_sitemap() ? '<div class="updated"><p>Plan du site HTML généré !</p></div>' : '<div class="error"><p>Erreur lors de la génération du plan du site HTML.</p></div>';
    }
    echo '<div class="wrap"><h1>Sitemap & Plan du site</h1>';
    echo $msg;
    echo '<form method="post">';
    echo '<p><button type="submit" name="generate_sitemap" class="button button-primary">Générer le sitemap XML</button></p>';
    echo '<p><button type="submit" name="generate_html" class="button">Générer le plan du site HTML</button></p>';
    echo '</form></div>';
}

function custom_generate_sitemap_xml() {
    $posts = get_posts(['post_type' => ['post','page','product'], 'post_status' => 'publish', 'numberposts' => -1]);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($posts as $post) {
        $xml .= "  <url><loc>" . esc_url(get_permalink($post)) . "</loc></url>\n";
    }
    $xml .= "</urlset>\n";
    $file = ABSPATH . 'sitemap.xml';
    return file_put_contents($file, $xml) !== false;
}

function custom_generate_html_sitemap() {
    $html = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Plan du site</title></head><body>';
    $html .= '<h1>Plan du site</h1>';
    $html .= '<ul>';
    // Lien vers la page plan-du-site.html elle-même
    $html .= '<li><a href="' . esc_url(home_url('/plan-du-site.html')) . '">Plan du site (cette page)</a></li>';
    $pages = get_pages(['sort_column' => 'menu_order']);
    foreach ($pages as $page) {
        $html .= '<li><a href="' . esc_url(get_permalink($page)) . '">' . esc_html($page->post_title) . '</a></li>';
    }
    $html .= '</ul>';
    $html .= '<h2>Articles</h2><ul>';
    $posts = get_posts(['post_type' => 'post', 'post_status' => 'publish', 'numberposts' => -1]);
    foreach ($posts as $post) {
        $html .= '<li><a href="' . esc_url(get_permalink($post)) . '">' . esc_html($post->post_title) . '</a></li>';
    }
    $html .= '</ul>';
    if (post_type_exists('product')) {
        $html .= '<h2>Produits</h2><ul>';
        $products = get_posts(['post_type' => 'product', 'post_status' => 'publish', 'numberposts' => -1]);
        foreach ($products as $prod) {
            $html .= '<li><a href="' . esc_url(get_permalink($prod)) . '">' . esc_html($prod->post_title) . '</a></li>';
        }
        $html .= '</ul>';
    }
    $html .= '</body></html>';
    $file = ABSPATH . 'plan-du-site.html';
    return file_put_contents($file, $html) !== false;
}
