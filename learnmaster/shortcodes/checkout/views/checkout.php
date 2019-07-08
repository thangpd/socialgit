<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\models\OrderModel $order
 * @var \lema\core\components\Billing  $address
 * @var \lema\shortcodes\checkout\CheckoutShortcode $context
 */
$address = $order->getBillingAddress();
?>
<div class="<?php $context->defineShortcodeBlock()?>">
<form method="post">
    <?php if ($data['billing_info']):?>
    <div class="lema-checkout-billing">
        <h1>Checkout</h1>
        <?php echo __('<span>Billing address</span>', 'lema')?> &nbsp;&nbsp; <a href="javascript:void(0);" data-hide="#lema-order-billing-info" data-show="#lema-order-billding-edit"><i class="fa fa-pencil"></i> </a>
        <div id="lema-order-billing">
            <div id="lema-order-billing-info">
                <?php if( !$address->empty) :?>
                    <?php echo $address?>

                <?php else:?>
                    <div class="lema-message warning is-dismissible" id="lema-order-billing-warning">
                        <p><?php echo __( 'No billing address found.', 'lema' ); ?> &nbsp; <a href="javascript:void(0);" data-hide="#lema-order-billing-info" data-show="#lema-order-billding-edit"> <?php echo __('Add new', 'lema')?> <sup><i class="fa fa-pencil"></i> </sup></a> </p>
                    </div>
                <?php endif;?>
            </div>
            <div id="lema-order-billding-edit" class="lema-hide">
                <div class="la-form-group lema-form">
                    <div class="lema-col-50">
                        <label><?php echo __('First name', 'lema')?></label>
                        <input value="<?php echo $address->first_name?>" name="Billing[first_name]" data-order_billing type="text" class="la-form-control" />
                    </div>
                    <div class="lema-col-50">
                        <label><?php echo __('Last name', 'lema')?></label>
                        <input value="<?php echo $address->last_name?>" name="Billing[last_name]" data-order_billing type="text" class="la-form-control" />
                    </div>
                </div>
                <div class="la-form-group">
                    <label><?php echo __('Company', 'lema')?></label>
                    <input name="Billing[company]" value="<?php echo $address->company?>" data-order_billing type="text" class="la-form-control" />
                </div>
                <div class="la-form-group lema-form">
                    <div class="lema-col-50">
                        <label><?php echo __('Address line 1', 'lema')?></label>
                        <input name="Billing[address_line1]"  value="<?php echo $address->address_line1?>"data-order_billing type="text" class="la-form-control" />
                    </div>
                    <div class="lema-col-50">
                        <label><?php echo __('Address line 2', 'lema')?></label>
                        <input name="Billing[address_line2]" value="<?php echo $address->address_line2?>" data-order_billing type="text" class="la-form-control" />
                    </div>
                </div>
                <div class="la-form-group lema-form">
                    <div class="lema-col-50">
                        <label><?php echo __('City', 'lema')?></label>
                        <input  name="Billing[city]" value="<?php echo $address->city?>" data-order_billing type="text" class="la-form-control" />
                    </div>
                    <div class="lema-col-50">
                        <label><?php echo __('Postcode/ZIP', 'lema')?></label>
                        <input name="Billing[postcode]" value="<?php echo $address->postcode?>" data-order_billing type="text" class="la-form-control" />
                    </div>
                </div>
                <div class="la-form-group lema-form">
                    <div class="lema-col-50">
                        <label><?php echo __('Country', 'lema')?></label>
                        <select name="Billing[country]"  data-order_billing class="la-form-control">
                            <?php
                            $countries = apply_filters('lema_countries_list', []);
                            if (!empty($countries)) :
                            ?>
                                <?php foreach ($countries as $key => $name) :?>
                                    <option value="<? echo $key?>"><?php echo $name?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                    <div class="lema-col-50">
                        <label><?php echo __('State/County', 'lema')?></label>
                        <input name="Billing[state]" value="<?php echo $address->state?>" data-order_billing type="text" class="la-form-control" />
                    </div>
                </div>
                <div class="la-form-group lema-form">
                    <div class="lema-col-50">
                        <label><?php echo __('Email', 'lema')?></label>
                        <input name="Billing[email]" value="<?php echo $address->email?>" data-order_billing type="text" class="la-form-control" />
                    </div>
                    <div class="lema-col-50">
                        <label><?php echo __('Phone', 'lema')?></label>
                        <input  name="Billing[phone]" value="<?php echo $address->phone?>" data-order_billing type="text" class="la-form-control" />
                    </div>
                </div>

                <div class="la-form-group button-block button-block-left">
                    <a class="cancel" href="javascript:void(0);" data-show="#lema-order-billing-info" data-hide="#lema-order-billding-edit"><i class="fa fa-close"></i> Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div class="lema-checkout-payment">
        <h3>Please select payment method</h3>
        <?php foreach ($gateways as $gateway):?>
            <?php /** @var \lema\core\interfaces\PaymentGatewayInterface $gateway */?>
            <div class="lema-payment-method">
                <input class="lema-hide" type="radio" id="payment-method-<?php echo $gateway->getPaymentGatewayId()?>" name="payment_method" value="<?php echo $gateway->getPaymentGatewayId()?>" />
                <label title="<?php echo $gateway->getDescription()?>" class="payment-block" for="payment-method-<?php echo $gateway->getPaymentGatewayId()?>">
                    <?php echo $context->getPaymentImage($gateway->getIcon());?>
                    <div class="clear clarfix"></div>
                    <strong><?php echo $gateway->getGatewayName()?></strong>
                </label>
            </div>

        <?php endforeach;?>
        <div class="clear clearfix"></div>
        <div class="la-form-group button-block button-block-left">
            <button class="button button-primary" type="submit" id="lema-checkout-confirm" disabled>Continue</button>
        </div>
    </div>
</form>
</div>