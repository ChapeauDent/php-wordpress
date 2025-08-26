<?php
// Balises Open Graph et Twitter Card pour WordPress/WooCommerce
add_action('wp_head', function() {
    if (is_singular()) {
        global $post;
        $title = get_the_title($post);
        $desc = get_post_meta($post->ID, '_custom_meta_description', true);
        if (!$desc) {
            $desc = has_excerpt($post) ? get_the_excerpt($post) : '';
        }
        $url = get_permalink($post);
        $site_name = get_bloginfo('name');
        $image = '';
        if (has_post_thumbnail($post)) {
            $img_id = get_post_thumbnail_id($post);
            $img = wp_get_attachment_image_src($img_id, 'large');
            if ($img) $image = $img[0];
        }
        // Fallback image (logo)
        if (!$image && ($custom_logo_id = get_theme_mod('custom_logo'))) {
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($logo) $image = $logo[0];
        }
        // Open Graph
        echo '<meta property="og:type" content="article" />';
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />';
        echo '<meta property="og:description" content="' . esc_attr($desc) . '" />';
        echo '<meta property="og:url" content="' . esc_url($url) . '" />';
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />';
        if ($image) echo '<meta property="og:image" content="' . esc_url($image) . '" />';
        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image" />';
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />';
        echo '<meta name="twitter:description" content="' . esc_attr($desc) . '" />';
        if ($image) echo '<meta name="twitter:image" content="' . esc_url($image) . '" />';
    }
}, 5);
