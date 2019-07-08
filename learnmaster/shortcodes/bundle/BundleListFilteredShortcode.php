<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\bundle;


use lema\core\interfaces\ShortcodeInterface;
use lema\core\Shortcode;
use lema\models\BundleModel;
use lema\models\OrderModel;

class BundleListFilteredShortcode extends BundleListShortcode implements ShortcodeInterface {

	const SHORTCODE_ID = 'lema_bundlelist_filtered';

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		// TODO: Implement getId() method.
		return self::SHORTCODE_ID;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		$attrbutes = parent::getAttributes(); // TODO: Change the autogenerated stub

		return array_merge( $attrbutes, [
			'cat_bundle' => '',
			'layout'     => 'list',
			'summary'    => 0,
			'sort_type'  => 'desc',
			'sort_by'    => 'title',
			'subfilter'  => '',
			'q'          => '',
			'page_url'   => 1
		] );
	}

	public function all_bundle_purchased() {

		$current_user          = wp_get_current_user();
		$user_purchased_course = new \WP_Query( array(

			'post_type'   => OrderModel::POST_TYPE,
			'post_status' => array( 'publish' ),
			'author'      => ( $current_user->ID ),
			'meta_query'  => array(
				array(
					'key'   => 'order_status',
					'value' => array(
						OrderModel::ORDER_STATUS_PROCESSING,
						OrderModel::ORDER_STATUS_COMPLETED
					),
				),
				array(
					'key'     => 'post_type_product',
					'value'   => BundleModel::POST_TYPE,
					'compare' => '=='
				),
			),
		) );


		if ( ! $user_purchased_course ) {
			return;
		}

		$user_purchased_course = wp_list_pluck( $user_purchased_course->posts, 'ID' );
		foreach ( $user_purchased_course as $i => $orderID ) {
			$res[] = get_post_meta( $orderID, BundleModel::POST_TYPE, true );
		}


		return array_unique( $res );

	}


	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function getQuery( $data ) {
		$query = [
			'post_type' => 'lema_bundle'
		];
		if ( ! empty( $data->q ) ) {
			$query['s'] = $data->q;
		}
		$query['tax_query'] = [];
		$terms              = [
			'cat_bundle',
		];
		foreach ( $terms as $term ) {
			if ( ! empty( $data->$term ) ) {
				$ids                  = explode( ',', $data->$term );
				$query['tax_query'][] = array(
					'taxonomy'         => $term,
					'field'            => 'term_id',
					'terms'            => $ids,
					'include_children' => false,
					'operator'         => 'IN'
				);
			}
		}
		$query['tax_query']['relation'] = 'AND';
		$query['order']                 = $data->sort_type;


		preg_match( '/(.+)_(asc|desc)$/', $data->sort_by, $sort_type );

		if ( $sort_type ) {
			$query['order'] = strtoupper( $sort_type[2] );
			if ( empty( $sort_by ) ) {
				switch ( $sort_type[1] ) {

					default :
						$sort_by = $sort_type[1];
						if ( preg_match( '/^__/', $sort_by ) ) {
							$query['orderby']  = 'meta_value_num';
							$query['meta_key'] = str_replace( '__', '', $sort_type[1] );
						} else {
							$query['orderby'] = $sort_type[1];
						}
				}
			}
		}
		switch ( $data->subfilter ) {
			case 'free':
				$query['meta_key']   = BundleModel::REGULAR_PRICE;
				$query['meta_value'] = 0;
				break;
			case 'sale':
				$query['meta_query'] = array(
					array(
						'key'     => BundleModel::SALE_PRICE,
						'value'   => array( '' ),
						'compare' => 'NOT IN'
					)
				);
				break;
			case 'paid':
				$post__in = $this->all_bundle_purchased();
				if ( ! empty( $post__in ) ) {
					$query['post__in'] = $post__in;
				} else {
					return array();
				}

				break;
		}

		$query = lema()->hook->registerFilter( 'lema_bundle_filtered_query', $query );

		return $query;
	}
}