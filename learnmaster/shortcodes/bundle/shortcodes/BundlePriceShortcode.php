<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\bundle\shortcodes;


use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;
use lema\models\BundleModel;

class BundlePriceShortcode extends Shortcode {
	const SHORTCODE_ID = 'lema_bundlecard_price';
	const PRICE_TYPE_REGULAR = 'bundle_price_regular';
	const PRICE_TYPE_SALE = 'bundle_price_sale';
	const PRICE_TYPE_BOTH = 'bundle_price_both';

	public $contentView = 'price';

	public function _init() {
		lema()->wp->add_filter( 'lema_bundle_footer', [ $this, 'register' ] );
	}

	/**
	 * Register child shortcode to parent
	 *
	 * @param $shortcodes
	 *
	 * @return array
	 */
	public function register( $shortcodes ) {
		$shortcodes[] = $this->getId();

		return $shortcodes;
	}

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		// TODO: Implement getId() method.
		return self::SHORTCODE_ID;
	}


	/**
	 * Array of default value of all shortcode options
	 * @return array
	 */
	public function getAttributes() {
		return [
			'show_remain_time'      => true,
			'post_id'               => '',
			'show_discount_percent' => 1,
			'show_discount_value'   => 1,
		];
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		$data          = $this->getData( $data );
		$data['price'] = [];

		$postId        = $data['data']['post_id'];
		$regular_price = lema()->wp->get_post_meta( $postId, BundleModel::REGULAR_PRICE, true );
		if ( ! is_numeric( $regular_price ) ) {
			$regular_price = 0;
		}
		$data['price']['regular'] = $regular_price;

		$sale_price = lema()->wp->get_post_meta( $postId, BundleModel::SALE_PRICE, true );
		if ( is_numeric( $sale_price ) ) {
			$data['price']['sale'] = $sale_price;
			$saleTimeLeft          = get_post_meta( $postId, 'bundle_price_expire', true );
			if ( $saleTimeLeft ) {
				$saleTimeLeft = strtotime( $saleTimeLeft );
				$now          = time();
				$remainTime   = $now - $saleTimeLeft;
				if ( $remainTime > 0 ) {
					$days = intval( $remainTime / 86400 );
					if ( $days * 86400 < $remainTime ) {
						$days ++;
					}
					$data['timeLeft'] = $days;

				} else {
					//The sale off price was expired
					unset( $data['price']['sale'] );
				}
			}
			$regular        = $data['price']['regular'] ? 0 : 1;
			$sale           = $data['price']['sale'];
			$discountPercen = 0;


			if ( $regular && $sale ) {
				$discountPercen = intval( ( ( $regular - $sale ) / $regular ) * 100 );
			}

			$data['discount_percen'] = $discountPercen;
		}
		foreach ( $data['price'] as &$price ) {
			$price = $this->formatCurrency( $price );
		}

		return $this->render( $this->contentView, $data, true );
	}


	/**
	 * @param $price
	 *
	 * @return string
	 */
	private function formatCurrency( $price ) {
		$price_replace = lema()->config->lema_price_setting;
		if ( intval( $price ) == 0 ) {
			$price = ! empty( $price_replace ) ? $price_replace : lema()->helpers->general->currencyFormat( $price );
		} else {
			$price = lema()->helpers->general->currencyFormat( $price );
		}

		return $price;
	}
}