<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>

<div class="shortcode-blocks" data-id="<?php echo $context->getId()?>" data-priority="<?php echo $context->getPriority()?>" <?php echo $context->generateAttrbuteHtml($data)?> >
    <?php echo isset($content) ? $content : ''?>
</div>

