<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  8/10/17.
 */


namespace lema\front\controllers;


use lema\core\components\cart\Cart;
use lema\core\interfaces\FrontControllerInterface;


class PaymentController extends FrontController implements FrontControllerInterface
{
    /**
     * Check cart
     * - If user not currently logged in redirect to wp login page with redirect url is this checkout
     * - Convert cart to order
     * - Show detail of method payment
     * @return string
     */
    public function cartCheckout()
    {
        if (!is_user_logged_in()) {
            $loginUrl = wp_login_url(lema()->page->getPageUrl(lema()->config->getUrlConfigs('lema-checkout')));
            return lema()->helpers->general->baseRedirect($loginUrl);
        } else {
            /** @var Cart $cart */
            $cart = Cart::getInstance();
            $order = $cart->convertToOrder();
            return $this->render('checkout', [
                'order' => $order
            ]);
        }

    }

    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'pages' => [
                'front' => [
                    lema()->config->getUrlConfigs('lema_checkout') => ['cartCheckout', [
                        'db' => true,
                        'title' => __('Learn master - Checkout page', 'lema')
                    ]]
                ]
            ]
        ];
    }
}