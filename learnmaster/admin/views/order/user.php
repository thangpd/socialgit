<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 *
 * @var \lema\models\OrderModel $order
 */
?>

<div class="la-form-group" id="lema-order-user">
    <fieldset class="lema <?php echo empty($order->lema_order_user_id) == false ? ' lema-hide ' : ''?>" id="lema-order-user-edit">
        <legend>Select an user :</legend>
        <select data-select2-ajax name="LemaOrder[lema_order_user_id]" data-order_user class="select2" data-url="<?php echo admin_url('amin-ajax.php')?>" data-action="lema_search_user">
            <option selected value="<?php echo $order->lema_order_user_id?>"></option>
        </select>
        <br/><hr/>
        <div class="aling-left">
            <button data-order_id="<?php echo $order->post->ID?>" data-target="#lema-order-user" data-items="data-order_user" data-action="lema_order_add_user" type="button" class="button button-primary button-large ajax-button">Save</button>
            &nbsp;
            <?php if (!empty($order->lema_order_user_id)) :?>
                <a href="javascript:void(0);" data-show="#lema-order-user-info" data-hide="#lema-order-user-edit">cancel</a>
            <?php endif;?>
        </div>
    </fieldset>
    <?php if (empty($order->lema_order_user_id)) :?>
        <div class="lema-message warning is-dismissible">
            <p><?php echo __( 'No student selected', 'lema' ); ?></p>
        </div>
    <?php else:?>
        <?php
            /** @var WP_User $user */
            $user = $order->getUser();
        ?>
        <div id="lema-order-user-info">
            <fieldset class="lema" >
                <legend>Student information</legend>
                <strong><?php echo $user->display_name?></strong> <a href="javascript:void(0);" data-hide="#lema-order-user-info" data-show="#lema-order-user-edit"><i class="fa fa-pencil"></i> </a>
            </fieldset>
            <fieldset class="lema">
                <legend>Billing address <a href="javascript:void(0);" data-hide="#lema-orfer-billing-info" data-show="#lema-order-billding-edit"><i class="fa fa-pencil"></i> </a> </legend>
                <?php echo $context->render('_billing_address', ['form' => $form, 'order' => $order])?>
            </fieldset>
        </div>
    <?php endif;?>
</div>


<script language="javascript">
    lema.ui.select2();
</script>