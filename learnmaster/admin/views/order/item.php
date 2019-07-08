<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\helpers\Helper $helper
 */
?>
<div id="lema-order-items">
    <div class="la-form-group">
        <table class="lema-order-item">
            <tr>
                <th width="60%">Item</th>
                <th width="10%">Price</th>
                <th width="10%">Quantity</th>
                <th width="10%">Total</th>
                <th width="10%"></th>
            </tr>
            <?php
            $items = $order->getItems();
            if (empty($items)) :
            ?>
            <tr>
                <td colspan="5">
                    <div class="lema-message warning">
                        <?php echo __('No item found.', 'lema')?>
                    </div>
                </td>
            </tr>
            <?php else:?>
            <?php foreach ($order->getItems() as $item):?>
                <?php $course = $item->getCourse()?>
                <tr>
                    <td>
                        <a href="<?php echo get_permalink($course->post)?>" target="_blank"><?php echo $course->post->post_title?></a>
                    </td>
                    <td><?php echo lema()->helpers->general->currencyFormat(($item->subtotal / $item->quantity))?></td>
                    <td><?php echo $item->quantity?></td>
                    <td><?php echo lema()->helpers->general->currencyFormat($item->subtotal)?></td>
                    <td>
                        <a class="ajax-button" href="javacript:void(0)" data-order_id="<?php echo $order->post->ID?>" data-target="#lema-order-items" data-action="lema_order_remove_item"  data-item_id="<?php echo $item->id?>">
                            <i class="fa fa-close"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach;?>
            <?php endif;?>
            <tr>
                <td colspan="3" align="right"><strong><?php echo __('Amount', 'lema')?></strong></td>
                <td>
                    <input type="hidden" name="LemaOrder[subtotal]" value="<?php echo $order->subtotal?>" />
                    <input type="hidden" name="LemaOrder[total]" value="<?php echo $order->total?>" />
                    <?php echo lema()->helpers->general->currencyFormat($order->subtotal)?>
                </td>
            </tr>
        </table>
        <fieldset class="lema">
            <legend>Add more item</legend>
            <div class="lema-col-50">
                <label>Select a course :</label>
                <select data-select2-ajax data-order_data name="course_id" class="select2" data-url="<?php echo admin_url('amin-ajax.php')?>" data-action="lema_search_course"></select>
            </div>
            <div class="lema-col-50">
                <label>Quantity</label>
                <input type="number" data-order_data class="la-form-control" name="quantity" min="1" max="100" value="1"/>
                <br/>
                <br/>
            </div>
            <div class="aling-left"><button data-order_id="<?php echo $order->post->ID?>" data-target="#lema-order-items" data-items="data-order_data" data-action="lema_order_add_item" type="button" class="button button-primary button-large ajax-button">Add item</button></div>
        </fieldset>
    </div>


</div>
<script language="javascript">
    lema.ui.select2();
</script>