<?php
// Supprimer la balise robots générée par WordPress (évite les doublons)
remove_action('wp_head', 'wp_robots', 1);


// === Meta Robots personnalisée (index/noindex, follow/nofollow) ===

// Ajout de la meta box
function custom_meta_robots_add_box() {
    $screens = array('post', 'page', 'product');
    foreach ($screens as $screen) {
        add_meta_box(
            'custom_meta_robots',
            __('Meta Robots (SEO)', 'custom-meta-robots'),
            'custom_meta_robots_box_callback',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'custom_meta_robots_add_box');

// Affichage du champ
function custom_meta_robots_box_callback($post) {
    $value = get_post_meta($post->ID, '_custom_meta_robots', true);
    $options = [
        '' => __('Défaut (index, follow)', 'custom-meta-robots'),
        'noindex, follow' => 'noindex, follow',
        'index, nofollow' => 'index, nofollow',
        'noindex, nofollow' => 'noindex, nofollow',
    ];
    echo '<select name="custom_meta_robots" style="width:100%">';
    foreach ($options as $k => $label) {
        echo '<option value="' . esc_attr($k) . '"' . selected($value, $k, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    echo '<p style="font-size:smaller;">' . __('Définissez ici la balise meta robots pour le SEO de cette page/article/produit.', 'custom-meta-robots') . '</p>';
}

// Sauvegarde du champ
function custom_meta_robots_save($post_id) {
    if (array_key_exists('custom_meta_robots', $_POST)) {
        update_post_meta(
            $post_id,
            '_custom_meta_robots',
            sanitize_text_field($_POST['custom_meta_robots'])
        );
    }
}
add_action('save_post', 'custom_meta_robots_save');
add_action('woocommerce_process_product_meta', function($post_id) {
    if (isset($_POST['custom_meta_robots'])) {
        update_post_meta($post_id, '_custom_meta_robots', sanitize_text_field($_POST['custom_meta_robots']));
    }
});

// Injection dans le head
function custom_meta_robots_inject() {
    if (is_singular()) {
        global $post;
        $robots = get_post_meta($post->ID, '_custom_meta_robots', true);
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '" />';
        }
    }
}
add_action('wp_head', 'custom_meta_robots_inject', 2);
