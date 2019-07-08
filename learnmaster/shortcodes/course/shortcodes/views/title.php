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
$post_id = $post_link = $post_title = $html_shortcode = '';
$format_html = $context->getFormatHtml('block');
if ( isset($data['post_id']) ):
	$post_id = $data['post_id'];
	
	//get data
	$post_title = get_the_title($post_id);
	
	//render html element title
	$html_title = $post_title;
	if ( isset($data['has_link']) && $data['has_link']) {
		$post_link = get_permalink($post_id);
        $post_link = apply_filters('lema_course_link', $post_link, $post_id);
		$html_title = sprintf($context->getFormatHtml('title'), $post_title, $post_link);
	}
	$html_shortcode .= $html_title;
	echo sprintf($format_html, $html_shortcode);
endif;
?>
