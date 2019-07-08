<?php
/**
* @copyright © 2017 by Solazu Co.,LTD
* @project Learn Master Plugin
* @since 1.0
*
*/
$cartItems = $data['cartItems'];
$cart = $data['cartObject'];
$link_checkout = '';
if (isset($data['link_checkout'])) {
	$link_checkout = $data['link_checkout'];
}
?>
<div class="page-card-content <?php $context->defineShortcodeBlock()?>">
    <?php if (!empty($cartItems)) :?>
	<div class="lema-card-page-detail">
	<form class="lema-form-cart">
		<table class="lemn-cart-table" cellspacing="0">
			<thead>
				<tr>
					<th class="product-name" colspan="3"><?php echo esc_html('Product', 'lema') ?></th>
					<th class="product-price"><?php echo esc_html('Price', 'lema') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cartItems as $keyItem => $item): ?>

				<?php 
				$model = $item->course;
				$itemTitle = $model->post_title;
				$thumbnail_default = lema()->wp->site_url() . '/wp-content/plugins/' . LEMA_NAME .'/assets/images/thumb-default.jpg';
				$itemThumbnail = $model->getThumbnailUrl($thumbnail_default);
				$itemLink = get_permalink($keyItem);
				$itemPrice = $model->getPrice();
				?>
				<tr class="cart_item">
					<td class="product-remove">
						<a href="#" class="remove"><?php echo esc_html('×', 'lema') ?></a>
						<input type="hidden" name="item_id" value="<?php echo esc_attr($keyItem) ?>">
                        <input type="hidden" name="quantity" value="1">
                    </td>
					<td class="product-thumbnail">
						<a href="#">
							<img width="180" height="180" src="<?php echo esc_url($itemThumbnail) ?>">
						</a>
					</td>
					<td class="product-name" data-title="Product">
						<a href="<?php echo esc_url($itemLink) ?>"><?php echo esc_html($itemTitle) ?></a>						
					</td>
					<td class="product-price" data-title="Price">
						<span class="amount"><?php echo esc_html(lema()->helpers->general->currencyFormat($itemPrice) ) ?></span>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</form>
		<?php 
		$total = $cart->getSubtotal();
		?>
		<div class="lema-block-checkout">
			<h2><?php echo esc_html('CART TOTALS', 'lema')  ?></h2>
			<ul>
				<li><?php echo esc_html__('Total:', 'lema') ?> <span class="lema-total-cart"><?php echo esc_html(lema()->helpers->general->currencyFormat($total)) ?></span></li>
			</ul>
			<?php if (!empty($link_checkout)): ?>
			<a href="<?php echo esc_url($link_checkout); ?>" class="lema-btn"><?php echo esc_html('Checkout', 'lema') ?></a>
			<?php endif; ?>
		</div>
	</div>
    <?php else :?>
       <div class="lema-message error">
           <?php echo __('Cart empty!', 'lema')?>
       </div>
    <?php endif;?>
</div>