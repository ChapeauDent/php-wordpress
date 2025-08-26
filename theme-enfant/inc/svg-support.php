
<?php
// === Support SVG : upload, sécurité, affichage, métadonnées, notices ===

// Autoriser l'upload SVG
function custom_allow_svg_upload($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'custom_allow_svg_upload');

// Affichage correct SVG dans la médiathèque
function custom_fix_svg_display($response, $attachment, $meta) {
	if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
		$response['image'] = array('src' => $response['url'], 'width' => 300, 'height' => 300);
		$response['thumb'] = array('src' => $response['url'], 'width' => 150, 'height' => 150);
		$response['sizes'] = array('full' => array('url' => $response['url'], 'width' => 300, 'height' => 300, 'orientation' => 'landscape'));
	}
	return $response;
}
add_filter('wp_prepare_attachment_for_js', 'custom_fix_svg_display', 10, 3);

// Sécuriser les SVG uploadés
function custom_sanitize_svg($file) {
	if ($file['type'] !== 'image/svg+xml') return $file;
	$svg_content = file_get_contents($file['tmp_name']);
	if ($svg_content === false) {
		$file['error'] = __('Impossible de lire le fichier SVG.', 'custom-svg');
		return $file;
	}
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	if (!$dom->loadXML($svg_content)) {
		$file['error'] = __('Le fichier SVG n\'est pas valide.', 'custom-svg');
		return $file;
	}
	$svg_content = custom_clean_svg($svg_content);
	if ($svg_content === false) {
		$file['error'] = __('Le fichier SVG contient des éléments non autorisés.', 'custom-svg');
		return $file;
	}
	file_put_contents($file['tmp_name'], $svg_content);
	return $file;
}
add_filter('wp_handle_upload_prefilter', 'custom_sanitize_svg');

// Nettoyer le contenu SVG
function custom_clean_svg($svg_content) {
	$forbidden_elements = array('script','object','embed','iframe','link','meta','form','input','button','textarea');
	$forbidden_attributes = array('onload','onclick','onmouseover','onerror','javascript:','vbscript:','data:','base64');
	foreach ($forbidden_elements as $element) {
		if (stripos($svg_content, '<' . $element) !== false) return false;
	}
	foreach ($forbidden_attributes as $attribute) {
		if (stripos($svg_content, $attribute) !== false) return false;
	}
	return $svg_content;
}

// Ajouter les dimensions aux métadonnées SVG
function custom_svg_metadata($metadata, $file, $filesize) {
	if (strpos($file, '.svg') !== false) {
		$svg_content = file_get_contents($file);
		if ($svg_content !== false) {
			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			if ($dom->loadXML($svg_content)) {
				$svg = $dom->getElementsByTagName('svg')->item(0);
				if ($svg) {
					$width = $svg->getAttribute('width');
					$height = $svg->getAttribute('height');
					$viewBox = $svg->getAttribute('viewBox');
					if (empty($width) || empty($height)) {
						if (!empty($viewBox)) {
							$viewBoxArray = explode(' ', $viewBox);
							if (count($viewBoxArray) === 4) {
								$width = $viewBoxArray[2];
								$height = $viewBoxArray[3];
							}
						}
					}
					$width = (int) filter_var($width, FILTER_SANITIZE_NUMBER_INT);
					$height = (int) filter_var($height, FILTER_SANITIZE_NUMBER_INT);
					if (empty($width)) $width = 300;
					if (empty($height)) $height = 300;
					$metadata = array('width' => $width, 'height' => $height, 'filesize' => $filesize);
				}
			}
		}
	}
	return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'custom_svg_metadata', 10, 3);

// Affichage SVG dans l'éditeur
function custom_svg_editor_display() {
	echo '<style>
		.attachment-266x266, .thumbnail img { width: 100% !important; height: auto !important; }
		.media-icon img[src$=".svg"] { width: 100%; height: auto; }
		.wp-block-image img[src$=".svg"] { max-width: 100%; height: auto; }
	</style>';
}
add_action('admin_head', 'custom_svg_editor_display');

// Infos SVG dans la médiathèque
function custom_svg_media_info($form_fields, $post) {
	if ($post->post_mime_type === 'image/svg+xml') {
		$file_path = get_attached_file($post->ID);
		$file_size = size_format(filesize($file_path));
		$metadata = wp_get_attachment_metadata($post->ID);
		$width = isset($metadata['width']) ? $metadata['width'] : 'N/A';
		$height = isset($metadata['height']) ? $metadata['height'] : 'N/A';
		$form_fields['svg_info'] = array(
			'label' => __('Informations SVG', 'custom-svg'),
			'input' => 'html',
			'html' =>
				'<p><strong>' . __('Type:', 'custom-svg') . '</strong> SVG (Scalable Vector Graphics)</p>' .
				'<p><strong>' . __('Dimensions:', 'custom-svg') . '</strong> ' . $width . ' × ' . $height . ' pixels</p>' .
				'<p><strong>' . __('Taille:', 'custom-svg') . '</strong> ' . $file_size . '</p>' .
				'<p><em>' . __('Les fichiers SVG sont vectoriels et peuvent être redimensionnés sans perte de qualité.', 'custom-svg') . '</em></p>'
		);
	}
	return $form_fields;
}
add_filter('attachment_fields_to_edit', 'custom_svg_media_info', 10, 2);

// Notice SVG upload
function custom_svg_upload_notice() {
	$screen = get_current_screen();
	if ($screen && ($screen->id === 'upload' || $screen->id === 'media')) {
		echo '<div class="notice notice-info is-dismissible">
			<p><strong>' . __('Fichiers SVG:', 'custom-svg') . '</strong> '
			. __('Vous pouvez maintenant uploader des fichiers SVG dans la médiathèque. Pour des raisons de sécurité, certains éléments JavaScript sont automatiquement supprimés.', 'custom-svg') . '</p>
		</div>';
	}
}
add_action('admin_notices', 'custom_svg_upload_notice');
