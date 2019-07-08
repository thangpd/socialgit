<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  8/9/17.
 */


namespace lema\shortcodes\cart;


use lema\core\components\cart\Cart;
use lema\core\components\cart\CartItem;
use lema\core\interfaces\ControllerInterface;

use lema\helpers\Helper;
use lema\models\CourseModel;
use lema\core\Shortcode;
use lema\models\Student;

class AddCartShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_add_cart';

	public $contentView = 'add-cart';

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
				'id'           => 'lema-shortcode-add-cart-script',
				'isInline'     => false,
				'url'          => 'assets/scripts/add-cart.js',
				'dependencies' => [ 'lema', 'lema.shortcode', 'lema.ui' ]
			],
			[
				'type'         => 'style',
				'id'           => 'lema-shortcode-add-cart-style',
				'isInline'     => false,
				'url'          => 'assets/styles/add-cart.css',
				'dependencies' => [ 'lema-style', 'lema-shortcode-style', 'font-awesome' ]
			]
		];
	}

	public function getAttributes() {
		if ( class_exists( 'WooCommerce' ) ) {
			$url_checkout = wc_get_checkout_url();
			$url_cart     = wc_get_cart_url();
		} else {
			$url_checkout = ( get_current_user_id() ) ? lema()->page->getPageUrl( lema()->config->getUrlConfigs( 'lema_checkout' ) ) : '';
			$url_cart     = lema()->page->getPageUrl( lema()->config->getUrlConfigs( 'lema_cart' ) );
		}

		return [
			'post_id'      => '',
			'title'        => 'Add to cart',
			'url-cart'     => $url_cart,
			'url-checkout' => $url_checkout,
			'class'        => 'lema-btn-cart',
			'show_image'   => 1,
		];
	}

	/**
	 * [addCart description]
	 */
	public function addItem() {
		if ( ! isset( $_POST ) ) {
			die();
		}

		global $woocommerce;


		$courseID = ! empty( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		$quantity = ! empty( $_POST['quantity'] ) ? $_POST['quantity'] : '';


		if ( is_user_logged_in() ) {
			$student = Student::getCurrentUser();
			if ( ! empty( $student ) ) {
				if ( $student->checkEnrolled( $courseID ) ) {
					return $this->responseJson( [
						'modal'  => '<div class="lema-message waring">' . __( 'Course enrolled, no effect for your action', 'lema' ) . '</div>',
						'status' => 'enrolled'
					] );
				}
			}
		} else {
			return $this->responseJson( [
				'modal'  => sprintf( '<div class="lema-message waring">%4$s<a class="open-modal-login" href="%1$s"><b>%2$s</b></a>%3$s</div>', home_url() . '/' . lema()->config->getUrlConfigs( 'lema_login' ), __( 'Login', 'lema' ), __( ' before enroll this course', 'lema' ), __( 'Please ', 'lema'
				) ),
				'status' => 'login'
			] );
		}

		$courseModel        = CourseModel::findOne( $courseID );
		$slug               = get_post_field( 'post_name', $courseID );
		$product_id         = Helper::getCourseinWoo( $courseID, $slug );
		$post_meta['title'] = get_the_title( $courseID );
		if ( isset( $courseModel->__data['course_price'] ) ) {
			$post_meta['_regular_price'] = $courseModel->__data['course_price'] == '' ? 0 : $courseModel->__data['course_price'];
			$post_meta['_price']         = $courseModel->__data['course_price'] == '' ? 0 : $courseModel->__data['course_price'];
			if ( isset( $courseModel->__data['course_sale_price'] ) ) {
				$post_meta['_sale_price'] = $courseModel->__data['course_sale_price'] == '' ? $courseModel->__data['course_price'] : $courseModel->__data['course_sale_price'];
				$post_meta['_price']      = $courseModel->__data['course_sale_price'] == '' ? $courseModel->__data['course_price'] : $courseModel->__data['course_sale_price'];
			}
		}
		$post_meta['post_id'] = $courseID;
		$post_meta['prefix']  = $courseModel->getName();
		$post_meta['slug']    = $slug;

		if ( ! isset( $product_id ) || empty( $product_id ) ) {
			$product_id = $this->create_woocommerce_product( $post_meta );
		} else {
			$this->update_available_product( $product_id, $post_meta );
		}

		if ( $product_id > 0 ) {


			$found = false;
			//check if product already in cart
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->get_id() == $product_id ) {
					$found = true;
				}
			}
			// if product not found, add it
			if ( ! $found ) {
				WC()->cart->add_to_cart( $product_id );
				$woocommerce->session->set_customer_session_cookie( true );
				$woocommerce->session->set( 'lema_book_session_key_' . $cart_item_key,
					array(
						'booking_type' => 'course',
						'booking_data' => $post_meta
					) );
			}
		}

		$this->responseJson( [
			'html'  => lema_do_shortcode( '[lema_cart_icon]' ),
			'modal' => '<div class="lema-message success">Added "<a href="' . get_permalink( $courseID ) . '"><b>' . $post_meta['title'] . '</b></a>" to cart !</div>',
			'total' => WC()->cart->get_cart_contents_count(),
		] );


		wp_die();


	}

	public function random_sku( $prefix = '', $len = 6 ) {
		$str = '';
		for ( $i = 0; $i < $len; $i ++ ) {
			$str .= rand( 0, 9 );
		}

		return $prefix . '_' . $str;
	}

	public function create_woocommerce_product( $post_meta = array() ) {

		$default   = array(
			'title'          => '',
			'_sale_price'    => '',
			'_regular_price' => '',
			'_price'         => '',
			'post_id'        => '',
			'prefix'         => '',
			'slug'           => ''
		);
		$post_meta = array_merge( $default, $post_meta );

		$new_post   = array(
			'post_title'     => $post_meta['title'],
			'post_content'   => esc_html__( 'This is a variable product used for purchased processed with WooCommerce', 'slzexploore-core' ),
			'post_status'    => 'publish',
			'post_name'      => $post_meta['slug'],
			'post_type'      => 'product',
			'comment_status' => 'closed',
			'post_author'    => get_current_user_id(),
		);
		$product_id = wp_insert_post( $new_post );

//		$product_cat = $this->update_product_categories( $post_meta['post_id'], 'course_cat', esc_html__( 'Course Products', 'lema' ) );

		wp_set_object_terms( $product_id, 'simple', 'product_type' );
		wp_set_object_terms( $product_id, $post_meta['prefix'], 'product_cat' );


		if ( has_post_thumbnail( $post_meta['post_id'] ) ) {
			set_post_thumbnail( $product_id, get_post_thumbnail_id( $post_meta['post_id'] ) );
		}
		$sku = $this->random_sku( $post_meta['prefix'] . '_' . $post_meta['post_id'], 6 );
//		$sku = $post_meta['prefix'] . '_' . $post_meta['post_id'];


		update_post_meta( $product_id, '_course', $post_meta['post_id'] );
		update_post_meta( $product_id, '_sku', $sku );
		//		update_post_meta( $post_id, '_visibility', 'hidden' );
		update_post_meta( $product_id, '_stock_status', 'instock' );
		//		update_post_meta( $post_id, 'total_sales', '0' );
		update_post_meta( $product_id, '_downloadable', 'no' );
		update_post_meta( $product_id, '_virtual', 'yes' );
		update_post_meta( $product_id, '_regular_price', ! empty( $post_meta['_regular_price'] ) ? $post_meta['_regular_price'] : 0 );
		update_post_meta( $product_id, '_price', ! empty( $post_meta['_price'] ) ? $post_meta['_price'] : 0 );
		update_post_meta( $product_id, '_sale_price', $post_meta['_sale_price'] );
		update_post_meta( $product_id, '_purchase_note', "" );
		update_post_meta( $product_id, '_featured', "no" );
		update_post_meta( $product_id, '_weight', "" );
		update_post_meta( $product_id, '_length', "" );
		update_post_meta( $product_id, '_width', "" );
		update_post_meta( $product_id, '_height', "" );
		update_post_meta( $product_id, '_sale_price_dates_from', "" );
		update_post_meta( $product_id, '_sale_price_dates_to', "" );
		update_post_meta( $product_id, '_sold_individually', "yes" );
		update_post_meta( $product_id, '_manage_stock', "no" );
		update_post_meta( $product_id, '_backorders', "no" );
		update_post_meta( $product_id, '_stock', "" );

		// hide this product in front end
		$visibility_ids = wc_get_product_visibility_term_ids();
		if ( isset( $visibility_ids['exclude-from-catalog'] ) && isset( $visibility_ids['exclude-from-search'] ) ) {
			$product_visibility = array(
				$visibility_ids['exclude-from-catalog'],
				$visibility_ids['exclude-from-search']
			);
			wp_set_object_terms( $product_id, $product_visibility, 'product_visibility' );
		}


		$product_attributes = array(
			$post_meta['prefix'] => array(
				'name'         => $post_meta['prefix'],
				'value'        => '',
				'is_visible'   => '1',
				'is_variation' => '0',
				'is_taxonomy'  => '0'
			)
		);
		update_post_meta( $product_id, '_product_attributes', $product_attributes );

		return $product_id;
	}

	public function update_available_product( $product_id, $post_meta = array() ) {

		$default   = array( '_regular_price' => '', '_sale_price' => '' );
		$post_meta = array_merge( $default, $post_meta );

		if ( ! empty( $product_id ) ) {
			update_post_meta( $product_id, '_regular_price', $post_meta['_regular_price'] );
			update_post_meta( $product_id, '_sale_price', ! empty( $post_meta['_sale_price'] ) ? $post_meta['_sale_price'] : $post_meta['_regular_price'] );
			update_post_meta( $product_id, '_price', $post_meta['_price'] );
		}

	}

	/**
	 * [actions description]
	 * @return [type] [description]
	 */
	public function actions() {
		return [
			'ajax' => [
				'lema-add-cart' => [ $this, 'addItem' ],
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

		$data         = $this->getData( $data );
		$data['cart'] = Cart::getInstance();
		if ( isset( $data['data']['post_id'] ) && ! empty( $data['data']['post_id'] ) ) {
			$model          = CourseModel::findOne( $data['data']['post_id'] );
			$data['course'] = $model;
		}

		return $this->render( $this->contentView, array_merge( $data, $params ), true );
	}
}