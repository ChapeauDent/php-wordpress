<?php
// === Meta Description personnalisée (compatible tout thème) ===
function custom_meta_description_add_box() {
	$screens = array('post', 'page', 'product', 'shop');
	foreach ($screens as $screen) {
		add_meta_box(
			'custom_meta_description',
			__('Meta Description (SEO)', 'custom-meta-description'),
			'custom_meta_description_box_callback',
			$screen,
			'normal',
			'high'
		);
	}
}
add_action('add_meta_boxes', 'custom_meta_description_add_box');

function custom_meta_description_box_callback($post) {
	$value = get_post_meta($post->ID, '_custom_meta_description', true);
	echo '<textarea style="width:100%" rows="3" name="custom_meta_description">' . esc_textarea($value) . '</textarea>';
	echo '<p style="font-size:smaller;">' . __('Définissez ici la meta description pour le SEO de cette page/article/produit.', 'custom-meta-description') . '</p>';
}

function custom_meta_description_save($post_id) {
	if (array_key_exists('custom_meta_description', $_POST)) {
		update_post_meta(
			$post_id,
			'_custom_meta_description',
			sanitize_text_field($_POST['custom_meta_description'])
		);
	}
}
add_action('save_post', 'custom_meta_description_save');
add_action('woocommerce_process_product_meta', function($post_id) {
	if (isset($_POST['custom_meta_description'])) {
		update_post_meta($post_id, '_custom_meta_description', sanitize_text_field($_POST['custom_meta_description']));
	}
});

function custom_meta_description_inject() {
	global $post;
	$desc = '';
	if (is_front_page() && isset($post->ID)) {
		$desc = get_post_meta($post->ID, '_custom_meta_description', true);
	} elseif (function_exists('is_shop') && is_shop()) {
		$shop_id = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : false;
		if ($shop_id) {
			$desc = get_post_meta($shop_id, '_custom_meta_description', true);
		}
	} elseif (is_category() || is_tag() || (function_exists('is_product_category') && is_product_category()) || (function_exists('is_product_tag') && is_product_tag())) {
		$term = get_queried_object();
		if ($term && !empty($term->description)) {
			$desc = strip_tags($term->description);
		}
	} elseif (is_singular() && isset($post->ID)) {
		$desc = get_post_meta($post->ID, '_custom_meta_description', true);
	}
	if ($desc) {
		echo '<meta name="description" content="' . esc_attr($desc) . '" />';
	}
}
add_action('wp_head', 'custom_meta_description_inject', 1);
