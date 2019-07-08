<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
$post_id = '';
$format_html = $context->getFormatHtml('block');
if ( isset($data['post_id']) ):
	$post_id = $data['post_id'];
	
	//get data
	$post_categories = $context->getCategory($post_id);
	//render html element item cat
	$limit = -1 ;
	$html_cat_item = '';
	if (!empty($data['limit'])) {
		$limit = intval($data['limit']);
	}
	$count = 0;
	if (!empty($post_categories)) {
		foreach ($post_categories as $catObject) {
			$cat_name 		= $catObject->name;
			$cat_link 		= get_category_link($catObject->term_id);
			$html_cat_item .= sprintf($context->getFormatHtml('category_item'), $cat_name, $cat_link);

			if ($limit > 0 && $count >= $limit-1) {
				break;
			}
			$count++;
		}	
	}
	


	echo sprintf($format_html, $html_cat_item);
endif;
?>
