<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 * @var \lema\core\components\cart\Cart $cart
 * @var \lema\models\CourseModel $course
 */
if ( isset( $data['post_id'] ) ) {
	$class = '';
	if ( isset( $data['class'] ) ) {
		$class = $data['class'];
	}

	?>
    <button class="<?php echo $class ?>" data-url_cart="<?php echo $data['url-cart'] ?>"
            data-url_checkout="<?php echo $data['url-checkout'] ?>" data-modal="modal_<?php echo $data['post_id'] ?>"
            data-lema-add-cart="<?php echo $data['post_id'] ?>" data-quantity="1">
		<?php
		if ( $data['show_image'] ) {
			?>
            <i class="fa fa-cart-plus"></i>
			<?php
		} ?>
		<?php echo $data['title']; ?>
    </button>

<?php } ?>


