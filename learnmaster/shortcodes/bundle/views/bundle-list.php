<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
?>
<div class="lema-bundle-list <?php $context->defineShortcodeBlock()?>">
    <?php if (isset($data['summary']) && $data['summary'] && isset($total) && $data['show_bundle_list_title']) :?>

    <?php endif;?>
    	<?php echo $block?>
        <?php if ($total < 1 && $data['show_bundle_list_title']) :?>
        <div class="lema lema-waring"><?php echo __('There is no bundle found', 'lema')?></div>
        <?php endif;?>
</div>
