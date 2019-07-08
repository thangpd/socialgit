<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\shortcodes\course\shortcodes\BundleBookmarkShortcode $context
 */

$post_id        = $data['post_id'];
$html_shortcode = '';

$layout = $data['layout'];

$format_html = $context->getFormatHtml( $layout );

if ( isset( $data['post_id'] ) ):

	//get num of bookmark
	$num_bookmark = $context->getBookmarkNumber( $post_id );

	//get data
	$bookmark_class = 'fa fa-heart-o';
	//render html element bookmark

	if ( $context->isBookmarked( $post_id ) ) {
		$bookmark_class = 'fa fa-heart';
	}

	$label_button = '';
	if ( isset( $data['label_button'] ) && ! empty( $data['label_button'] ) ) {
		$label_button = $data['label_button'];
	}
	echo sprintf( $format_html, $post_id, $bookmark_class, $label_button, $num_bookmark );
endif;
?>