<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

namespace lema\core\interfaces;


interface CartItemInterface
{
    /**
     * Get item value
     * @return float
     */
    public function getTotalValue();

    /**
     * Get item value
     * @return float
     */
    public function getSubTotalValue();

    /**
     * @param ModelInterface $course
     * @return CartInterface
     */
    public function setCourse(ModelInterface $course);

    /**
     * @return ModelInterface $course
     */
    public function getCourse();

    /**
     * Set item quantity
     * @param $quantity
     * @return CartItemInterface
     */
    public function setQuantity($quantity);

    /**
     * Get quantity of this item
     * @return integer
     */
    public function getQuantity();

    /**
     * @return
     */
    public function getItemId();

}