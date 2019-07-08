<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 *   Field Options
 *   %1$s -> title
 *   %2$s -> link
 *   %3$s -> class
 */


$post_id     = $post_link = $html_description = $html_shortcode = '';
$format_html = $context->getFormatHtml( 'block' );
if ( isset( $data['post_id'] ) ):
	$post_id = $data['post_id'];
	$model   = \lema\models\BundleModel::getInstance();
	$model   = $model->findOne( $post_id );

	$limit = $afterStr = '';
	if ( ! empty( $data['limit_text'] ) ) {
		$limit = $data['limit_text'];
		$limit = intval( $limit );
	}

	if ( ! empty( $data['afterString'] ) ) {
		$afterStr = $data['afterString'];
	}

	$course_description = lema()->helpers->general->limitWords( $model->post->post_excerpt, [
		'limit'    => $limit,
		'afterStr' => $afterStr
	] );
	echo sprintf( $format_html, $course_description );
endif;
?>
