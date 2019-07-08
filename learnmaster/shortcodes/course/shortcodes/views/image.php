<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

use lema\models\CourseModel;

$post_id = $post_link = $post_img = '';
$context->setHtmlOptionDefault();
$format_html = $context->getFormatHtml( 'block' );
$model       = CourseModel::getInstance();
$class_block = '';
if ( isset( $data['class'] ) ) {
	$class_block = $data['class'];
}
$cat      = $model->getCategory( 'term_id' );
$arr_best = lema()->helpers->getCourseBestSelling( $cat );
if ( isset( $model->course_best ) && $model->course_best ) {
	$arr_best[] = $data['post_id'];
} else {
	$arr_best[] = '';
}

if ( isset( $data['post_id'] ) && ! empty( $data['post_id'] ) ):
	//get data

	$post_id  = $data['post_id'];
	$model    = $model->findOne( $post_id );
	$post_img = get_the_post_thumbnail_url( $post_id );
	if ( ! $post_img ) {
		$post_img = LEMA_PATH_PLUGIN . '/assets/images/404.png';
	}
	$post_url = get_permalink( $post_id );
	$post_url = apply_filters( 'lema_course_link', $post_url, $post_id );

	//render html element image
	$html_image = '';
	if ( isset( $data['has_link_post'] ) && $data['has_link_post'] ) {
		$html_image = sprintf( $context->getFormatHtml( 'image-full' ), $post_img, $post_url );
	} else {
		$html_image = sprintf( $context->getFormatHtml( 'image' ), $post_img );
	}

	//render html element label
	$html_label = '';
	if ( ! empty( $data['has_label'] ) && ! empty( $data['text_label'] ) && $data['has_label'] && in_array( $data['post_id'], $arr_best ) ) {
		$class_label = $data['class_label_block'];
		$text_label  = $data['text_label'];
		$html_label  = sprintf( $context->getFormatHtml( 'label' ), $text_label, $class_label );
	}

	//render html element hours
	$html_hours = '';
	if ( ! empty( $data['has_hours'] ) && $data['has_hours'] ) {
		if ( $model instanceof CourseModel ) {
			$texthours = $model->getHours();
		} else {
			$texthours = '';
		}
		if ( ! empty( $texthours ) ) {
			$html_hours = sprintf( $context->getFormatHtml( 'hours' ), $texthours );
		}
	}

	//render html element button
	$html_button = '';
	if ( ! empty( $data['has_like_button'] ) || ! empty( $data['has_add_button'] ) ) {
		$html_add_button = $html_view_button = $html_note_devices = '';
		if ( ! empty( $data['has_add_button'] ) ) {
			$html_add_button .= lema_do_shortcode( '[lema_add_cart post_id="' . $post_id . '" class="lema-icon-cart" title=""]' );
		}
		$html_like_button = '';
		if ( ! empty( $data['has_like_button'] ) ) {
			$html_like_button .= lema_do_shortcode( '[lema_coursecard_bookmark post_id="' . $post_id . '" layout="layout-3"]' );
		}

		if ( ! empty( $data['has_note_devices'] ) ) {
			$text = '';
			if ( isset( $data['text_note_devices'] ) ) {
				$text = $data['text_note_devices'];
			}
			$html_note_devices = sprintf( $context->getFormatHtml( 'note_devices' ), $text );
		}
		/**
		 * format html_button
		 * @var %1$s $html_add_button
		 * @var %2$s $html_view_button
		 */
		$html_button = sprintf( $context->getFormatHtml( 'block_button' ), $html_add_button, $html_like_button, $html_note_devices );
	}
	/**
	 * block
	 * %1$s: class
	 * %2$s: image
	 * %3$s: label
	 * %4$s: hours
	 * %5$s: button
	 */

	echo sprintf( $format_html,
		$class_block,
		$html_image,
		$html_label,
		$html_hours,
		$html_button
	);
endif;
?>
