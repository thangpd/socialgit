<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\core\components\cart;


use lema\core\BaseObject;
use lema\core\interfaces\CartInterface;
use lema\core\interfaces\CartItemInterface;
use lema\core\interfaces\ModelInterface;
use lema\models\CourseModel;

class CartItem extends BaseObject implements CartItemInterface
{
    const LIMIT_CART_ITEM_QUANTITY          = 100;
    private $course, $quantity;

    /**
     * CartItem constructor.
     * @param CourseModel $course_id
     * @param $quantity
     */
    public function __construct(CourseModel $course, $quantity)
    {
        parent::__construct([]);
        $this->course = $course;
        $this->quantity = $quantity;
    }

    /**
     * Get item value
     * @return float
     */
    public function getTotalValue()
    {
        return $this->getSubTotalValue();
    }

    /**
     * Get item value
     * @return float
     */
    public function getSubTotalValue()
    {
        return floatval($this->course->getPrice()) * intval($this->quantity);
    }

    /**
     * @param ModelInterface $course
     * @return CartInterface
     */
    public function setCourse(ModelInterface $course)
    {
        $this->course = $course;
    }

    /**
     * @return ModelInterface $course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set item quantity
     * @param $quantity
     * @return CartItemInterface
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        if ($this->quantity > self::LIMIT_CART_ITEM_QUANTITY) {
            $this->quantity = self::LIMIT_CART_ITEM_QUANTITY;
        }
        return $this;
    }

    /**
     * Get quantity of this item
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get id of cart item
     * @return int
     */
    public function getItemId()
    {
        return $this->course->getId();
    }
}