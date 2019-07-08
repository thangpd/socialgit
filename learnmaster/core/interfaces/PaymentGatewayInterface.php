<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\core\interfaces;


use lema\models\OrderModel;

interface PaymentGatewayInterface extends ExtensionInterface
{
    /**
     * Set success redirect uri
     * After payment was succeed client need to redirect to success_url to
     * complete payment action
     * @param string $url
     * @return PaymentGatewayInterface
     */
    public function setSuccessUrl($url);
    /**
     *
     * Set cancellation url
     * After payment was denied
     * return to this URI to cancel related order
     * @param $url
     * @return PaymentGatewayInterface
     */
    public function setCancelUrl($url);

    /**
     * Set current order
     * @param OrderModel $order
     * @return PaymentGatewayInterface
     */
    public function setOrder(OrderModel $order);


    /**
     * Return this payment icon
     * @return mixed
     */
    public function getIcon();

    /**
     * Get current gateway ID
     * @return string Gateway ID
     */
    public function getPaymentGatewayId();

    /**
     * Get name of current gateway
     * @return string
     */
    public function getGatewayName();
    /**
     * Get payment description
     * @return string
     */
    public function getDescription();

    /**
     * Get form of this payment method
     * @return mixed
     */
    public function getPaymentForm();

    /**
     * @param array $params
     * @return mixed
     */
    public function verifyPayment($params = []);

    /**
     * Show setting form
     * @return mixed
     */
    public function settings();

    /**
     *
     * Allow user pay anything
     * @param string $item_name Name of item
     * @param float $item_price Price of item
     * @param integer $item_quantity Number of quantity
     * @return mixed
     */
    public function payAnything($item_name, $item_price, $item_quantity);
}