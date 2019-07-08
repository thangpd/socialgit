<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

    $model = \lema\models\CourseCategoryModel::getInstance();
    $categories = $model->getChildren(0);

?>
<div class="lema-category-dropdown-block lema-dropdown box-style <?php $context->defineShortcodeBlock()?>">
    <a href="javascript:void(0);" class="btn btn-default lema-dropdown-toggle"><?php echo esc_html__('Categories', LEMA_NAMESPACE); ?></a>
    <div class="lema-dropdown-menu">
        <ul class="course-categories-list">
	        <?php $context->buildMenu( $data, $categories ); ?>
        </ul>
    </div>
</div>

