<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  8/9/17.
 */


namespace lema\shortcodes\cart;


use lema\core\components\cart\Cart;
use lema\core\Shortcode;
use lema\models\CourseModel;

class CartIconShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_cart_icon';

	public $contentView = 'icon';

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		return self::SHORTCODE_ID;
	}

	/**
	 * Register static assets for this shortcode
	 * @return array
	 */
	public function getStatic() {
		return [
			[
				'type'         => 'script',
				'id'           => 'lema-shortcode-cart-script',
				'isInline'     => false,
				'url'          => 'assets/scripts/course-cart.js',
				'dependencies' => [ 'lema', 'lema.shortcode' ]
			],
			[
				'type'         => 'style',
				'id'           => 'lema-shortcode-cart-style',
				'isInline'     => false,
				'url'          => 'assets/styles/course-cart.css',
				'dependencies' => [ 'font-awesome' ]
			]
		];
	}

	/**
	 * @param array $data
	 * @param array $params
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		if ( ! is_array( $params ) ) {
			$params = [];
		}
		$data = $this->getData( $data );

		return $this->render( $this->contentView, array_merge( $data, $params ), true );
	}

	public function actions() {
		return
			[
				'ajax' => [
					'lema-update-cart-dropdown' => [ $this, 'updateCartDropdown' ]
				]
			];
	}

	public function updateCartDropdown() {
		if ( isset( $_POST['cart_item_key'] ) && class_exists( 'WooCommerce' ) ) {
			WC()->cart->remove_cart_item( $_POST['cart_item_key'] );
		}

		list( $data, $content_count, $url_cart ) = $this->renderCartDropdown();
		$this->responseJson( array( 'data' => $data ) );
		wp_die();
	}


	/**
	 * render cart dropdown
	 */
	public function renderCartDropdown() {
		$html = array();

		$url_cart = lema()->page->getPageUrl( lema()->config->getUrlConfigs( 'lema_cart' ) );
		if ( class_exists( 'WooCommerce' ) ) {
			$url_cart = wc_get_cart_url();
		} else {
			return '';
		}

		$html['cart_list'] = '        <div class="cart-list"> %1$s </div>';
		$html['cart_item'] = '<div class="item" data-cart_item_key="%1$s">
                        <div class="block-left">
                            <a class="pic" href="%2$s">
								%3$s
                            </a>
                            <a href="#" data-cart_item_key="%1$s"
                               class="cart-remove">%4$s</a>

                        </div>
                        <div class="block-right">
							%5$s
							%6$s
                            <div class="block-info">
                                <span class="cal-item">
                                    <span>%7$s</span> X
                                    <span>%8$s</span>
                                </span>
                            </div>
                        </div>
                    </div>';
		$html['gotocart']  = '<a href="%1$s"
           class="btn-view-cart btn-block"><i
                    class="fa fa-shopping-cart"></i>' . __( 'Go to cart', 'lema' ) . '</a>';

		$html_cart_items = '';

		if ( ! WC()->cart->is_empty() ) :
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ):
				/*$imgUrl = get_the_post_thumbnail_url( $item->getItemId() );

				if ( ! $imgUrl ) {
					$imgUrl = LEMA_PATH_PLUGIN . '/assets/images/404.png';
				}*/

				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

//				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );


				if ( preg_match( '#' . CourseModel::COURSE_SLUG . '#', $_product->get_sku() ) && ! is_user_logged_in() ) {
					WC()->cart->remove_cart_item( $cart_item_key );
					continue;
				}

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );


					$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );


					$product_name = apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="title" href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );


					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					//$product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );

					$attributes = '';

					//Variation
					$attributes .= $_product->is_type( 'variable' ) || $_product->is_type( 'variation' ) ? wc_get_formatted_variation( $_product ) : '';
					// Meta data
					if ( version_compare( WC()->version, '3.3.0', "<" ) ) {
						$attributes .= WC()->cart->get_item_data( $cart_item );
					} else {
						$attributes .= wc_get_formatted_cart_item_data( $cart_item );
					}


				}
				$html_cart_items .= sprintf( $html['cart_item'],
					$cart_item_key,
					$product_permalink,
					$thumbnail,
					__( 'Remove', 'lema' ),
					$product_name,
					$attributes,
					$cart_item['quantity'],
					$product_price );
			endforeach;
		endif;

		$cart_content_count = WC()->cart->get_cart_contents_count();
		$totl_price         = WC()->cart->get_cart_subtotal();
		$res                = sprintf( '<div class="lema-total">
				<div class="lema-count-item">
					<span class="number">%1$s</span>
					<span class="text">' . __( 'Course(s) in Cart', 'lema' ) . '</span>
				</div>
				<div class="lema-total-price">
					<span class="text">' . __( 'Total', 'lema' ) . '</span>
					<span class="total-price">%2$s</span>
				</div>
			</div>', $cart_content_count, $totl_price );
		if ( ! empty( $html_cart_items ) ) {
			$res .= sprintf( $html['cart_list'], $html_cart_items );
		}
		$res .= sprintf( $html['gotocart'], $url_cart );


		return array( $res, $cart_content_count, $url_cart );

	}

}