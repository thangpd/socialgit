<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\core\components\cart\Cart $cart
 */

$total_item = 0;
if ( class_exists( 'WooCommerce' ) ) {
	list( $dropdown_menu, $total_item ) = $context->renderCartDropdown();
}
?>

<div class="lema-dropdown inline-link lema-cart-dropdown only-icon <?php $context->defineShortcodeBlock() ?>">
    <a href="javascript:void(0);" class="link lema-dropdown-toggle" data-toggle="lema-dropdown" aria-haspopup="true"
       aria-expanded="false">
        <i class="fa fa-shopping-cart"></i>
        <span class="text"><?php echo esc_html__( 'My cart', 'lema' ); ?></span>
		<?php if ( $total_item > 0 ) : ?>
            <span id="total-item-cart" class="lema-num">
	       <?php echo $total_item; ?>
       </span>
		<?php endif; ?>
    </a>

    <div class="dropdown-menu">
		<?php echo $dropdown_menu ?>
    </div>
</div>