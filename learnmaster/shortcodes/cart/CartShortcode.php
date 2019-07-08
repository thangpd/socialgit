<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/17/17.
 */


namespace lema\shortcodes\cart;


use lema\core\components\cart\Cart;
use lema\core\components\cart\CartItem;
use lema\core\Shortcode;
use lema\models\CourseModel;

class CartShortcode extends Shortcode
{

    const SHORTCODE_ID      = 'lema_cart';
    const LIMIT_CART_ITEM_QUANTITY          = 1;

    public $contentView = 'cart';


    /**
     * Get static resources of shortcode
     *
     * @return [];
     *
     */
    public function getStatic()
    {
        return [
            [
                'type'          => 'script',
                'id'            => 'cardpage-script',
                'isInline'      => false,
                'url'           => 'assets/scripts/lema-cart.js',
                'dependencies'  => ['lema', 'lema.shortcode']
            ],
            [
                'type'          => 'style',
                'id'            => 'cardpage-style',
                'isInline'      => false,
                'url'           => 'assets/styles/lema-cart.css',
                'dependencies'  => ['lema-shortcode-style']
            ]
        ];
    }

    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        return self::SHORTCODE_ID;
    }

    /**
     * Array of default value of all shortcode options
     * @return array
     */
    public function getAttributes()
    {
        /** @var Cart $cart */
        $cart  = Cart::getInstance();
        /** @var CartItem[] $cartItems */
        $cartItems = $cart->getItems();
        return [
        'cartItems' => $cartItems,
        'cartObject'      => $cart,
        'link_checkout' => lema()->page->getPageUrl(lema()->config->getUrlConfigs('lema_checkout'))
        ];
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
    }

         /**
     * get list action register
     * @return array 
     */
         public function actions()
         {
            return [
            'ajax'  => [
            'ajax_update_cart'   => [$this, 'updateCart'],
            ]
            ];
        }

        public function updateCart() {
            $model = Cart::getInstance();
            if (isset($_POST['data'])) {
                $data = $_POST['data'];
                $item = [];
                $listData = [];

            //format data
                for ($i = 0; $i < count($data); $i++) {
                    $obj = $data[$i];
                    if ( isset($item[$obj['name']]) ) {
                        $course = CourseModel::findOne($item['item_id']);
                        $listData[$item['item_id']] = new CartItem($course, $item['quantity']);
                        $item = [];
                    }

                    $item[$obj['name']] = $obj['value'];
                    if ($i == (count($data)-1)) {
                        $course = CourseModel::findOne($item['item_id']);
                        $listData[$item['item_id']] = new CartItem($course, $item['quantity']);
                    }
                }
                $model->setItems($listData);
            } else {
                $items = $model->getItems();
                foreach ($items as $item_id => $obj) {
                    $model->removeItem($item_id);
                }
            }
            echo 1;
            die;
        }
    }