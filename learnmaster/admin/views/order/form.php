<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\models\OrderModel $order
 * @var \lema\core\components\Form $form
 * @var \lema\helpers\Helper $helpers
 */

$fields = $helpers->form->generateFormElement($order, $form);
 ?>
<input type="hidden" name="LemaOrder[order_key]" value="<?php echo empty($order->order_key) ? $order->generateOrderKey() : $order->order_key?>" />

<div class="lema-order-form">
    <div class="la-form-group">
        <?php echo $helpers->form->getField('order_status')?>
    </div>
    <div class="la-form-group">
        <?php echo $helpers->form->getField('order_expire_date')?>
    </div>
    <div class="la-form-group">
        <?php echo $helpers->form->getField('order_key')?>
    </div>
</div>

