<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>
<div class="lema-rating lema-rating-simple <?php $context->defineShortcodeBlock()?>">
    <label class="lema-shortcode lema-rating-label"><?php echo $data['label']?></label>
    <label class="lema-shortcode lema-rating-value">
        <strong><?php echo number_format($status->avg, 1)?></strong>
    </label>
    <div class="lema-star-rating view-only">
      <span class="bg-rate"></span>
      <span class="rating" style="width:<?php echo $status->avg * 20?>%"></span>
    </div>
    <div class="lema-number-rating">
	    <label class="lema-shortcode lema-rating-total">
	        (<?php echo number_format($status->total, 0)?>)
	    </label>
    </div>
    <?php if ($data['has_rating']): ?>
        <?php echo $context->render( 'rating', [ 'context' => $context, 'data' => $data, 'status' => $status]) ?>
    <?php endif; ?>
</div>
