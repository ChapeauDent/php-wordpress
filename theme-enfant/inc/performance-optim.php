<?php
// Optimisations performance : lazy loading et suppression CSS/JS inutiles

// Forcer lazy loading sur toutes les images du contenu
add_filter('the_content', function($content) {
    return preg_replace('/<img(.*?)>/', '<img loading="lazy"$1>', $content);
});

// Lazy loading sur les images du plan du site HTML généré (si besoin)
// (À ajouter dans la génération HTML si vous insérez des images)

// Désactiver les emojis WordPress
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Désactiver les styles Gutenberg sur le front si non utilisé
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // WooCommerce blocks
}, 20);


