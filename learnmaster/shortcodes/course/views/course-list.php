<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<div class="lema-course-list <?php $context->defineShortcodeBlock()?>">
    <?php if (isset($data['summary']) && $data['summary'] && isset($total) && $data['show_course_list_title']) :?>
       <!-- <h2 class="lema-course-list-title"><?php /*echo sprintf(esc_html__("Total %d item(s) found.", "lema"), $total); */?></h2>
        <div class="clearfix"></div>-->
    <?php endif;?>
    	<?php echo $block?>
        <?php if ($total < 1 && $data['show_course_list_title']) :?>
        <div class="lema lema-waring"><?php echo __('There is no course found', 'lema')?></div>
        <?php endif;?>
</div>
