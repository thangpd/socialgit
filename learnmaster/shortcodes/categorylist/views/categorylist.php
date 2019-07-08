<?php 
$model = $data['control'];
$dataAttr = '';
$classBlock = '';
$listTerm = $model->filter($data);
$formatBlock = $model->getFormatHtml('block');
$formatItem = $model->getFormatHtml('item');
$classCol = '';
if ( isset($data['cols_in_row']) ) {
	$classCol = 'lema-column-'.$data['cols_in_row'];
}

if ( isset($data['class']) ) {
    $classBlock = $data['class'];
}

if ( isset($data['data']) ) {
    $dataAttr = $data['data'];
}

if ( isset($data['cols_in_row']) ) {
    $classCol = 'lema-column-'.$data['cols_in_row'];
}
if (!empty($listTerm)) {
	$htmlItems = '';
	foreach ($listTerm as $i => $cat) {
		$catID = $cat->term_id;
		$catLink =  get_category_link($catID);
		$catTitle = '';
		if ( isset($data['is_title']) && $data['is_title'] ) {
			$catTitle = sprintf($model->getFormatHtml('title'), $cat->name, $catLink);
		} 
		$catDescription = '';
		if ( isset($data['is_description']) && $data['is_description'] ) {
			$description = $cat->description;
			if ( isset($data['limit_description']) && !empty($data['limit_description']) ) {
				$limit_text = intval($data['limit_description']);
				$description = lema()->helpers->general->limitWords($description, ['limit'=> $limit_text,  'afterStr' => '...']);
			}
			$catDescription = sprintf($model->getFormatHtml('description'), $description);
		}
		$catClassIcon = '';
		if ( isset($data['is_icon']) && $data['is_icon'] ) {
			$icon_class = get_term_meta($catID, 'icon_class', true);
			$icon_class = apply_filters( 'lema_course_category_get_icon_class', $catID, $icon_class );
			$catClassIcon = sprintf($model->getFormatHtml('icon'), $icon_class, $catLink);
		}

		$htmlItem = $formatItem;
		$htmlItem = str_replace('[ICON_CAT]', $catClassIcon, $htmlItem);
		$htmlItem = str_replace('[TITLE]', $catTitle, $htmlItem);
		$htmlItem = str_replace('[DESCRIPTION]', $catDescription, $htmlItem);
		$htmlItems .= $htmlItem;
	}
	$formatBlock = str_replace('[CONTENT]', $htmlItems, $formatBlock);
	$formatBlock = str_replace('[CLASS_COL]', $classCol, $formatBlock);
    $formatBlock = str_replace('[DATA_BLOCK]', $dataAttr, $formatBlock);
    $formatBlock = str_replace('[CLASS_BLOCK]', $classBlock, $formatBlock);
    echo $formatBlock;
}
?>
