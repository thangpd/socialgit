<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\shortcodes\checkout;


use lema\core\components\cart\Cart;
use lema\core\interfaces\PaymentGatewayInterface;

use lema\core\Shortcode;
use lema\models\OrderModel;

class CheckoutShortcode extends Shortcode
{

    const SHORTCODE_ID              = 'lema_checkout';

    public $contentView             = 'checkout';

    /**
     * Slug
     * @var string
     */
    private $successUrl             = 'lema-success-order';
    /**
     * Slug
     * @var string
     */
    private $cancelledUrl           = 'lema-cancel-order';


    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        return self::SHORTCODE_ID;
    }

    /**
     * Shortcode options
     * @return array
     */
    public function getAttributes()
    {
        return [
            'order_id' => '',
            'billing_info' => true
        ];
    }

    /**
     * Render shortcode content
     * @param array $data
     * @param array $params
     * @return string
     */
    public function getShortcodeContent($data = [], $params = [], $key = '')
    {
        $this->successUrl = site_url() . '/' . $this->successUrl;
        $this->cancelledUrl = site_url() . '/' . $this->cancelledUrl;
        $this->successUrl = lema()->hook->registerFilter('lema_checkout_success_url', ($this->successUrl));
        $this->cancelledUrl = lema()->hook->registerFilter('lema_checkout_cancel_url',$this->cancelledUrl);
        $paymentGateways = lema()->helpers->general->getEnabledPaymentGateways();
        $data = $this->getData($data);
        $orderId = $data['data']['order_id'];
        /** @var OrderModel $order */
        $order = OrderModel::findOne($orderId);
        if (!empty($order)) {
            $data['gateways'] = $paymentGateways;
            $data['order'] = $order;
            
            if (isset($_POST['payment_method'])) {
                $paymentMethod = $_POST['payment_method'];
                if (!empty($_POST['Billing'])) {
                    lema()->wp->update_post_meta($orderId, 'billing_address', $_POST['Billing']);
                }
                $payment = $paymentGateways[$paymentMethod];
                if (!empty($payment)) {
                    /** @var PaymentGatewayInterface $payment */
                    $payment->setCancelUrl($this->cancelledUrl);
                    $payment->setSuccessUrl($this->successUrl);
                    $payment->setOrder($order);
                    $form = $payment->getPaymentForm();
                    $data['form'] = $form;
                    lema()->wp->update_post_meta($orderId, 'lema_payment_method', $paymentMethod);
                    update_post_meta($order->getId(),'order_status', OrderModel::ORDER_STATUS_PROCESSING);
                    return $this->render('payment', $data, true);
                }
            }
            if (empty($order->billing_address)) {
                $order->autoSetBillingAddress();
            }

            return $this->render($this->contentView, $data, true);
        }
    }

    /**
     * @return array
     */
    public function getStatic()
    {
        return [
            [
                'type'          => 'script',
                'id'            => 'lema-shortcode-checkout-script',
                'url'           => 'assets/scripts/lema-shortcode-checkout.js',
                'dependencies'  => ['lema', 'lema.shortcode','lema.ui']
            ],
            [
                'type'          => 'style',
                'id'            => 'lema-shortcode-checkout-style',
                'url'           => 'assets/styles/lema-shortcode-checkout.css',
                'dependencies'  => ['lema-shortcode-style']
            ]
        ];
    }

    /**
     * @param $image
     * @return string
     */
    public function getPaymentImage($image)
    {
        if (preg_match('/^(http(s)?|\|\/)/', $image)) {
            return sprintf('<img src="%s" class="payment-image"/>', $image);
        } else {
            return $image;
        }
    }
}