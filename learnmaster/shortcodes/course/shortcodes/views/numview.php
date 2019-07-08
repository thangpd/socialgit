<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\shortcodes\course\shortcodes\CourseBookmarkShortcode $context
 */

$post_id        = $data['post_id'];
$html_shortcode = '';


$layout = $data['layout'];

$format_html = $context->getFormatHtml( $layout );

if ( isset( $data['post_id'] ) ):

	//get num of bookmark
	$num_view = $model->getViewNumber( $post_id );

	//render html element bookmark
		$bookmark_class = 'fa fa-users';

	echo sprintf( $format_html, $post_id, $bookmark_class, $num_view );
endif;
?>