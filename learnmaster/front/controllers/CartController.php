<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  8/9/17.
 */


namespace lema\front\controllers;


use lema\core\components\cart\Cart;
use lema\core\components\cart\CartItem;
use lema\core\interfaces\ControllerInterface;

use lema\models\CourseModel;

class CartController extends FrontController implements ControllerInterface
{

    /**
     * Add item to card
     *
     * NOTE -- TEST ONLY
     */
    public function addItem()
    {
        if (isset($_POST['course_id']) && isset($_POST['quantity'])) {
            $courseId = $_POST['course_id'];
            $quanity  = (int) $_POST['quantity'];
            /** @var Cart $cart */
            $cart = Cart::getInstance();
            $cartItem = new CartItem(CourseModel::findOne($courseId), $quanity);
            $cart->addItem($cartItem);
            $this->responseJson([
                'data' => $cart->getItems(true)
            ]);
        }
    }

    /**
     * @return string
     */
    public function renderCart() {
        return $this->render('list' , []);
    }


    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'ajax' => [
                'lema_add_card_item' => [self::getInstance(), 'addItem']
            ],
            'pages' => [
                'front' => [
                    lema()->config->getUrlConfigs('lema_cart') => ['renderCart', [
                        'db' => false,
                        'title' => __('Cart page', 'lema')
                    ]],
                ]
            ]
        ];
    }
}