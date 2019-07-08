<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\core\interfaces;


interface CartInterface
{
    /**
     * Add an item to card
     * @param CartItemInterface $item
     * @return CartInterface
     */
    public function addItem(CartItemInterface $item);

    /**
     * Remove an item from cart
     * @param $id
     * @return mixed
     */
    public function removeItem($id);

    /**
     * Get all cart items
     * @return CartItemInterface[]
     */
    public function getItems();

    /**
     * Get subtotal
     * @return float
     */
    public function getSubtotal();

    /**
     * Get total
     * @return float
     */
    public function getTotal();

    /**
     * Convert cart to order after user checked out
     * @return bool
     */
    public function convertToOrder();

    /**
     * Clear shopping cart
     * @return mixed
     */
    public function destroy();

}