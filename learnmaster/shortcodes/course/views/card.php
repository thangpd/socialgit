<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\shortcodes\course\CourseShortcode $context
 */
?>

<div class="item lema-course <?php $context->defineShortcodeBlock()?>" >
    <div class="card-placeholder"></div>
	<?php 
	$data = apply_filters('lema_card_custom_data', $data);
	$format_block = $context->renderHtmlChildShortcode($data);
	//merge html render + content
	if ( preg_match('/\[sc_course_content\]/', $format_block) ) {
		$format_block = str_replace('[sc_course_content]', $content, $format_block);
	} else {
		$format_block.=$content;
	}
	echo $format_block; 
	?>
</div>