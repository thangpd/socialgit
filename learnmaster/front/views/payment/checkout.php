<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\models\OrderModel $order
 */

?>
<div class="lema-cart-checkout">
    <?php if ($order == false) :?>
        <div class="lema-message error">
            <?php echo __('No cart item found', 'lema')?>
        </div>
    <?php else:?>
        <?php echo lema_do_shortcode("[lema_checkout order_id='{$order->getId()}']");?>
    <?php endif;?>
</div>