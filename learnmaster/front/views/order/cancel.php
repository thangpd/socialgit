<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */
 ?>
 <div class="lema-cart-checkout">
	<h2><?php echo __('Canceled Order', 'lema')?></h2>
	<div class="lema-message error">
	    <?php echo __("The order #{$order->getId()} has been canceled successfully.", 'lema');?>
	</div>
</div>