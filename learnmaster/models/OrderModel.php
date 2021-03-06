<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\models;


use lema\core\components\Billing;
use lema\core\components\RoleManager;
use lema\core\interfaces\ModelInterface;

use lema\core\Model;

/**
 * @property int $order_status
 * @property int $lema_order_user_id
 * @property int $subtotal
 * @property int $total
 * @property int $tax_value
 * @property string $order_key
 * @property string $tax_class
 * @property string $coupon
 * @property object $billing_address
 *
 **/
class OrderModel extends Model implements ModelInterface
{
    const POST_TYPE                         = 'lema_order';
    const ORDER_EXPIRABLE                         = 'order_expire_date';

    const ORDER_STATUS_NEW                  = 0;
    const ORDER_STATUS_PENDING_PAYMENT      = 1;
    const ORDER_STATUS_PROCESSING           = 2;
    const ORDER_STATUS_ON_HOLD              = 3; //Payment holding
    const ORDER_STATUS_COMPLETED            = 4;
    const ORDER_STATUS_CANCELLED            = 5;
    const ORDER_STATUS_FAILED               = 6;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }



    /**
     * @param bool $item
     * @return string | array
     */
    public static function statusLabels($item =  false)
    {
        $labels = [
            self::ORDER_STATUS_NEW              => __('New order', 'lema'),
            self::ORDER_STATUS_PENDING_PAYMENT  => __('Pending order', 'lema'),
            self::ORDER_STATUS_PROCESSING       => __('Processing order', 'lema'),
            self::ORDER_STATUS_ON_HOLD          => __('Holding order', 'lema'),
            self::ORDER_STATUS_COMPLETED        => __('Completed order', 'lema'),
            self::ORDER_STATUS_CANCELLED        => __('Cancelled order', 'lema'),
            self::ORDER_STATUS_FAILED           => __('Failed order', 'lema'),
        ];
        if ($item === '') {
            $item = self::ORDER_STATUS_NEW;
        }
        if ($item !== false) {
            return $labels[$item];
        }
        return $labels;
    }
    /**
     * @return mixed
     *
     */
    public function getAttributes()
    {
        return [
            'order_status' => [
                'label' => "Order status",
                'form'  => [
                    'label' => "Order status",
                    'type'  => 'select',
                    'options' => self::statusLabels(),
                    'name' => 'LemaOrder[order_status]',
                    'class' => 'la-form-control'
                ]
            ],
            'order_expire_date' => [
                'label' => "Order Expire Date",
                'form'  => [
                    'label' => "Order Expire Date",
                    'type'  => 'date',
                    'name' => 'LemaOrder[order_expire_date]',
                    'class' => 'la-form-control'
                ]
            ],
            'order_key' => [
                'label'     => 'Order key',
                /*'form'  => [
                    'label' => "Order key",
                    'type'  => 'text',
                    'options' => self::statusLabels(),
                    'name' => 'LemaOrder[order_key]',
                    'class' => 'la-form-control',
                    'attributes' => ['readonly' => true]
                ]*/
            ],
            'lema_order_user_id' => [
                'label' => 'User ID'
            ],
            'subtotal' => [
                'label' => 'SubTotal'
            ],
            'total' => [
                'label' => 'Total value'
            ],
            'tax_class' => [
                'label' => 'Tax class'
            ],
            'tax_value' => [
                'label' => 'Tax value'
            ],
            'coupon' => [
                'label' => 'Coupon code'
            ],
            'billing_address' => [
                'label' => 'Billing address'
            ],
            'lema_payment_method' => [
                'label' => 'Payment Method'
            ],
            'lema_payment_token' => [
                'label' => 'Payment token'
            ]

        ];
    }

    /**
     * Abstract function get name of table/model
     * @return mixed
     */
    public function getName()
    {
        return self::POST_TYPE;
    }

    /**
     * Save object properties to database
     * @return boolean
     */
    public function save($postData = [])
    {
        try {
            $defaultPostData = [
                'post_title' => '#Order ' . date('Y-m-d H:i:s'),
                'post_type'     => self::POST_TYPE,
                'post_status'   => 'publish'
            ];
            // TODO: Implement save() method.
            if ($this->isNew) {
                $postId = wp_insert_post(array_merge($defaultPostData, $postData));
                return $postId;
            } else {
                return wp_update_post($this->post);
            }

        } catch (\Exception $e) {

        }
        return false;
    }



    /**
     * Delete a object by primary key
     * @return boolean
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * Recalculate order total after it's item changed
     * @param $orderId
     */
    public static function recalOrderTotal($orderId)
    {
        $order = self::findOne($orderId);
        if (!empty($order)) {
            /** @var OrderModel $order */
            $order->calculateTotal($orderId);
        }
    }

    /**
     * Calculate order subtotal and total
     * if this order was included tax , total value is sum of subtotal and tax
     * if not, those values are same
     * @param $orderId
     */
    public function calculateTotal($orderId)
    {
        if (empty($this->post)) {
            $this->loadPost($orderId);
        }
        if ($orderId) {
            $items = $this->getItems();
            $total = 0;
            foreach ($items as $item) {
                /** @var OrderItemModel $item */
                $total += $item->subtotal;
            }
            $this->subtotal = $total;
            if (!empty($this->tax_value)) {
                $this->total = $this->subtotal + $this->tax_value;
            } else {
                $this->total = $this->subtotal;
            }
            //$this->subtotal = lema()->hook->registerFilter('lema_order_calc_subtotal', $orderId, $this->subtotal);
            //$this->total = lema()->hook->registerFilter('lema_order_calc_total', $orderId, $this->total);

            lema()->wp->update_post_meta($orderId, 'subtotal', $this->subtotal);
            lema()->wp->update_post_meta($orderId, 'total', $this->subtotal);
        }
    }

    /**
     * Override default save meta function
     * to calculate order amount
     * @param $postId
     * @return bool
     */
    public function afterSave($postId , $post = null, $update = false) {
        $this->calculateTotal($postId);
        if (empty($this->order_key)) {
            $hash = lema()->helpers->general->getRandomString(6);
            $this->order_key  = $this->post->ID . $hash;
        }
        return parent::afterSave($postId);
    }

    /**
     * @return mixed|string
     */
    public function generateOrderKey()
    {
        global $post;
        $hash = lema()->helpers->general->getRandomString(6);
        $this->order_key  = $post->ID . $hash;
        return $this->order_key;
    }
    /**
     * @return mixed
     */
    public static function getPosttypeConfig()
    {
        lema()->hook->listenHook('after_order_item_changed', [OrderModel::className(), 'recalOrderTotal']);
        $labels = array(
            'name'               => _x( 'Order', 'post type general name', 'lema' ),
            'singular_name'      => _x( 'Order', 'post type singular name', 'lema' ),
            'menu_name'          => _x( 'Order', 'admin menu', 'lema' ),
            'name_admin_bar'     => _x( 'Order', 'add new on admin bar', 'lema' ),
            'add_new'            => _x( 'Add Order', 'Course', 'lema' ),
            'add_new_item'       => __( 'Add New Order', 'lema' ),
            'new_item'           => __( 'New Order', 'lema' ),
            'edit_item'          => __( 'Edit Order', 'lema' ),
            'view_item'          => __( 'View Order', 'lema' ),
            'all_items'          => __( 'Orders', 'lema' ),
            'search_items'       => __( 'Search Order', 'lema' ),
            'parent_item_colon'  => __( 'Parent Order:', 'lema' ),
            'not_found'          => __( 'No Order found.', 'lema' ),
            'not_found_in_trash' => __( 'No Order found in Trash.', 'lema' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'lema' ),
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => 'lema-setting-page',
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'supports' => array('none')
        );

        return [
            'post' => [
                'name' => 'lema_order',
                'args' => $args
            ]
        ];
    }

    /**
     * Get formatted total value included currency
     */
    public function formatTotal()
    {
        $total = $this->total;
        if ($total === null || !is_numeric($total)) {
            $total = 0;
        }
        return lema()->helpers->general->currencyFormat($total, false);
    }

    /**
     * @return OrderItemModel[]
     */
    public function getItems()
    {
        $items = OrderItemModel::find(['order_id' => $this->post->ID]);
        if ($items === null)
        {
            $items = [];
        }
        return $items;
    }

    /**
     * @return false|\WP_User
     */
    public function getUser()
    {
        return get_user_by('ID', $this->lema_order_user_id);
    }

    /**
     * @return Billing
     */
    public function getBillingAddress()
    {
        return Billing::loadByOrder($this->getId());
    }

    /**
     * If order billing is empty
     * Get list of user's billing addresses
     * and auto assign lastest one
     */
    public function autoSetBillingAddress()
    {
        $bills = Billing::loadByUser(get_current_user_id());
        if (!empty($bills)) {
            //Get lastest billing address
            $bill = $bills[0];
            /** @var Billing $bill */
            lema()->wp->update_post_meta($this->getId(), 'billing_address', $bill->getData());
        }
    }

    /**
     * find an order by payment token
     * @param $token
     * @return bool|ModelInterface|Model
     */
    public static function getOrderByPayment($token) {
        $args = array(
            'post_type' => 'lema_order',
            'meta_query' => array(
                array(
                    'key' => 'lema_payment_token',
                    'value' => $token,
                    'compare' => '=',
                )
            )
        );
        $query = new \WP_Query($args);
        $orders = $query->get_posts();
        if (!empty($orders)){
            $order =  array_shift($orders);
            $model = self::findOne($order);
            return $model;
        }
        return false;
    }
}