<?php
/**
 * Created by PhpStorm.
 * User: thang
 * Date: 11/13/18
 * Time: 4:19 PM
 */

/*
 * Bundle ID Model
 * Course ID
 *
 * */
if ( ! empty( $data['post_type'] ) ) {
	switch ( $data['post_type'] ) {
		case \lema\models\BundleModel::POST_TYPE:
			if ( $data['post_id'] == 0 ) {
				throw new Exception( 'Bundle must have post_id bundle' );
			} else {
				$bundle = \lema\models\BundleModel::findOne( $data['post_id'] )->__data;
				if ( isset( $bundle['total_price'] ) ) {
					$data['price'] = $bundle['total_price'];
					if ( isset( $bundle['lema_sale_price'] ) ) {
						$data['price'] = $bundle['lema_sale_price'];
					}
				}
			}

			break;
		case \lema\models\OrderModel::ORDER_EXPIRABLE:

			break;
	}
} else {
	throw new Exception ( 'Missing post_type' );
}

if ( ! \lema\helpers\Helper::checkIsBuy( $data['post_type'], $data['post_id'] ) ):
	?>


    <button class="lema-btn-cart checkout-button"
            data-post_type_id="<?php echo $data['post_id'] ?>"
            data-post_type="<?php echo $data['post_type'] ?>"
            data-expire_date="<?php echo esc_html( $data['expire_date'] ); ?>"
            data-price="<?php echo esc_html( ! empty( $data['price'] ) ? $data['price'] : 0 ); ?>"
            data-params="">


		<?php
		switch ( $data['layout'] ) {
			case 'layout-1':
				echo '<i class="fa fa-cart-plus"></i>';
				break;

			case 'layout-2':
				echo esc_html( $data['btn_text'] );
				break;

			default:
				echo esc_html( $data['btn_text'] );
				break;

		}


		?>
    </button>
	<?php
else:
	echo '<button>' . esc_html( $data['btn_text_bought'] ) . '</button>';
endif;
?>