<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\checkout;


use lema\admin\controllers\OrderController;
use lema\core\Shortcode;
use lema\models\BundleModel;
use lema\models\CourseModel;
use lema\models\OrderModel;
use lema\models\Student;

class ButtonCheckoutShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_checkout_bundle';

	public $contentView = 'button_checkout';
	/*
	[0] => New order
	[1] => Pending order
	[2] => Processing order
	[3] => Holding order
	[4] => Completed order
	[5] => Cancelled order
	[6] => Failed order*/
	const STATUS_SPENDING = 1;
	const STATUS_PROCESSING = 2;
	const STATUS_COMPLETED = 4;
	const STATUS_CANCELED = 5;

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		return self::SHORTCODE_ID;
	}


	/**
	 * Shortcode options
	 * @return array
	 */
	public function getAttributes() {
		return [
			//support post_type OrderModel::ORDER_EXPIRABLE / expire_date
//			          post_type BundleModel::POST_TYPE    / post_id
//			'post_type'   => OrderModel::ORDER_EXPIRABLE,
			'btn_text'        => __( 'Enroll', 'lema' ),
			'btn_text_bought' => __( 'Enrolled', 'lema' ),
			'post_type'       => '',
			'expire_date'     => '',//expired in number month.
			'post_id'         => 0,
			'price'           => '',
			'layout'          => '',
		];
	}

	/**
	 * Render shortcode content
	 *
	 * @param array $data
	 * @param array $params
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		$data['student'] = new Student( wp_get_current_user() );
		$data            = $this->getData( $data );

		return $this->render( $this->contentView, $data, true );
	}

	/**
	 * @return array
	 */
	public function getStatic() {
		return [
			[
				'type'         => 'script',
				'id'           => 'lema-shortcode-checkout-button-script',
				'url'          => 'assets/scripts/lema-shortcode-checkout-button.js',
				'dependencies' => [ 'lema', 'lema.shortcode', 'lema.ui' ]
			],
			[
				'type'         => 'style',
				'id'           => 'lema-shortcode-checkout-style',
				'url'          => 'assets/styles/lema-shortcode-checkout.css',
				'dependencies' => [ 'lema-shortcode-style' ]
			]
		];
	}

	/**
	 * list action post ajax
	 * @return array
	 */
	public function actions() {
		return [
			'ajax' => [
				'ajax_checkout_button'          => [ $this, 'ajax_button_checkout' ],
				'ajax_checkout_button_callback' => [ $this, 'ajax_button_checkout_callback' ]
			]
		];
	}

	public function ajax_button_checkout_callback() {
		session_start();
		$token      = ! empty( $_GET['token'] ) ? $_GET['token'] : '';
		$payer_id   = ! empty( $_GET['PayerID'] ) ? $_GET['PayerID'] : '';
		$post_type  = ! empty( $_GET['post_type'] ) ? $_GET['post_type'] : '';
		$post_id    = ! empty( $_GET['post_id'] ) ? $_GET['post_id'] : '';
		$url_return = ! empty( $_GET['url_return'] ) ? $_GET['url_return'] : home_url();
		$is_success = false;
		if ( ! empty( $token ) ) {
			global $wpdb;

			$order_id = $wpdb->get_var(
				$wpdb->prepare(
					"
                        SELECT post_id
                        FROM $wpdb->postmeta
                        WHERE meta_key = 'transaction_token'
                        AND meta_value = %s
                        ", esc_sql( $token )
				)
			);

			if ( $order_id ) {
				$order_data = get_post_meta( $order_id );
				if ( ! empty( $payer_id ) ) {
					$total_amount = floatval( $order_data['total'] );
					$ts_id        = lema_paypal_verify( 'lema-paypal-gateway', $token, $payer_id, $total_amount );
					if ( $ts_id ) {
						add_post_meta( $order_id, 'payer_id', $payer_id );
						update_post_meta( $order_id, 'order_status', self::STATUS_COMPLETED );

						wp_redirect( urldecode( $url_return ) );
						$is_success = true;
					}
				}

				if ( ! $is_success ) {
					update_post_meta( $order_id, 'order_status', self::STATUS_CANCELED );
				}
			}
		}


		echo get_404_template();
		exit;
	}

	public function ajax_button_checkout() {
		$success = false;
		$message = esc_html__( 'Bad Request.', 'lema' );
		if ( ! is_user_logged_in() ) {
			header( 'Content-Type: application/json' );
			echo json_encode( array( 'message' => esc_html__( 'requires login', 'lema' ) ) );
			exit();
		}

		if ( ! empty( $_POST ) ) {
			$user     = wp_get_current_user();
			$fullname = $user->display_name;
			$email    = $user->user_email;
			$data     = ! empty( $_POST['params'] ) ? esc_attr( $_POST['params'] ) : '';
			/*			let price = button.data('price');
					let params = button.data('params');
					let post_type_id = button.data('post_type_id');
					let post_type = button.data('post_type');*/
			$quantity = 1;
			$price    = abs( floatval( ! empty( $_POST['price'] ) ? esc_html( $_POST['price'] ) : 0 ) );

			$post_type_id = ! empty( $_POST['post_type_id'] ) ? esc_attr( $_POST['post_type_id'] ) : '';
			$expire_date  = ! empty( $_POST['expire_date'] ) ? ( $_POST['expire_date'] ) : '';
			$post_type    = ! empty( $_POST['post_type'] ) ? esc_attr( $_POST['post_type'] ) : '';
			$url_return   = ! empty( $_POST['url_return'] ) ? esc_attr( $_POST['url_return'] ) : home_url();
			$title        = esc_html__( $post_type . ' ' . $post_type_id . lema()->helpers->general->getRandomString( 6 ), 'lema' );
			$valid_info   = true;
			if ( empty( $fullname ) ) {
				$valid_info = false;
				$message    = esc_html__( 'Fullname not valid.', 'lema' );
			}
			if ( $valid_info && ! is_email( $email ) ) {
				$valid_info = false;
				$message    = esc_html__( 'Email not valid.', 'lema' );
			}

			if ( $valid_info ) {
				$order_id = wp_insert_post( array(
					'post_type'   => OrderModel::POST_TYPE,
					'post_status' => 'publish',
					'post_title'  => $title
				), true );
				$total    = floatval( $price * $quantity );
				if ( ! is_wp_error( $order_id ) ) {
					add_post_meta( $order_id, 'lema_order_user_id', $user->ID );
					add_post_meta( $order_id, 'post_type_product', $post_type );
					if ( BundleModel::POST_TYPE == $post_type ) {
						add_post_meta( $order_id, BundleModel::POST_TYPE, $post_type_id );
					}
					add_post_meta( $order_id, 'fullname', $fullname );
					add_post_meta( $order_id, 'email', $email );
					add_post_meta( $order_id, 'price', $price );
					add_post_meta( $order_id, 'data', $data );
					add_post_meta( $order_id, 'quantity', $quantity );
					update_post_meta( $order_id, 'total', $total );
					add_post_meta( $order_id, 'payment_method', 'paypal' );
					add_post_meta( $order_id, 'status', self::STATUS_SPENDING );
					$callback_url = admin_url( 'admin-ajax.php' ) . '?action=ajax_checkout_button_callback&post_type=' . $post_type . '&post_id=' . $post_type_id . '&url_return=' . $url_return;
					if ( ! empty( $expire_date ) ) {

						$dt2  = new \DateTime( "+" . $expire_date . " month" );
						$date = $dt2->format( "Y-m-d" );
						add_post_meta( $order_id, OrderModel::ORDER_EXPIRABLE, $date );
					}
					switch ( $post_type ) {
						//why add first? for test purpose. You can migrate this code to ajax_button_checkout_callback when payment is success;
						case BundleModel::POST_TYPE:
							add_post_meta( $order_id, $post_type, $post_type_id );
							$bundle_item = get_post_meta( $post_type_id, BundleModel::POST_META );
							if ( ! empty( $bundle_item ) ) {
								foreach ( $bundle_item as $courseID ) {
									OrderController::add_item_to_order( $order_id, $courseID, 1 );
								}
							}
							break;
						case OrderModel::ORDER_EXPIRABLE:
							$courseModel = new CourseModel();
							$courseList  = $courseModel->getAll( array(), false );

							if ( ! empty( $courseList ) ) {
								foreach ( $courseList as $course ) {
									OrderController::add_item_to_order( $order_id, $course->ID, 1 );
								}
							}
							break;
					}
					try {
						$checkout_url = lema_pay( 'lema-paypal-gateway', $title, $price, $quantity, $callback_url, $callback_url );

						$transaction_token = '';
						if ( preg_match( '/token=(.*?)&/', $checkout_url, $transaction_token ) ) {
							$transaction_token = $transaction_token[1];
						}
						add_post_meta( $order_id, 'transaction_token', $transaction_token );

						$success = true;
						$message = '';
					} catch ( \Exception $e ) {
						$message      = $e->getMessage();
						$checkout_url = null;
					}
				} else {
					$message = esc_html__( 'Create order failed.', 'lema' );
				}
			}
		}
//		$total = get_post_meta( $order_id );
		header( 'Content-Type: application/json' );
		echo json_encode( compact( 'success', 'message', 'checkout_url', 'total' ) );
		exit();
	}

}