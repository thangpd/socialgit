<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\bundle\shortcodes;


use lema\core\Shortcode;
use lema\models\BundleModel;

class BundleInstructorShortcode extends Shortcode {
	const SHORTCODE_ID = 'lema_bundlecard_instructor';
	public $contentView = 'instructor';


	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		// TODO: Implement getId() method.
		return self::SHORTCODE_ID;
	}

	public function getStatic() {
		return [
			[
				'type'         => 'style',
				'id'           => 'lema-bundle-detail-style',
				'isInline'     => false,
				'url'          => 'assets/styles/lema-bundle-detail.css',
				'dependencies' => [ 'lema-shortcode-style' ]
			]
		];
	}

	/**
	 * Array of default value of all shortcode options
	 * @return array
	 */
	public function getAttributes() {
		$attrs = [
			'user_by'             => '',
			'user_filter'         => 'id',
			'post_id'             => '',
			'layout'              => 'card',
			'limit'               => - 1,
			'show_image'          => 1,
			'show_social'         => 1,
			'show_name'           => 1,
			'show_statistic'      => 1,
			'show_description'    => 1,
			'show_bundle_popular' => 1,
			'show_role'           => 1,
			'show_icon_avatar'    => 1,
		];

		return $attrs;
	}

	public function getStatisticInstructor( $instructor ) {

		global $wpdb;

		$avgRating     = 0;
		$list_reviews  = $list_endrolls = [];
		$total_bundles = count( $instructor->list_bundles );

		if ( $total_bundles ) {
			$list_id_bundle = [];
			foreach ( $instructor->list_bundles as $bundle ) {
				$list_id_bundle[] = $bundle->ID;
			}
			// get list review
			$list_id_where = implode( ',', $list_id_bundle );
			$table         = $wpdb->prefix . 'lema_rating';
			$list_reviews  = $wpdb->get_results( $wpdb->prepare( "SELECT rate from {$table} WHERE object_id IN (%s)", $list_id_where ) );

			$table         = $wpdb->prefix . 'lema_order_item';
			$list_endrolls = $wpdb->get_results( $wpdb->prepare( "SELECT id from {$table} WHERE bundle_id IN (%s)", $list_id_where ) );

			if ( count( $list_reviews ) ) {
				foreach ( $list_reviews as $review ) {
					$avgRating += intval( $review->rate );
				}
			}

		}

		if ( is_array( $list_reviews ) || is_object( $list_reviews ) ) :
			if ( $avgRating > 0 && count( $list_reviews ) > 0 ) {
				$avgRating = round( ( $avgRating / count( $list_reviews ) ), 1 );
			}
		endif;

		return [
			[
				'title' => 'Average rating',
				'value' => $avgRating,
			],
			[
				'title' => 'Reviews',
				'value' => count( $list_reviews ),
			],
			[
				'title' => 'Student',
				'value' => count( $list_endrolls ),
			],
			[
				'title' => 'Bundles',
				'value' => $total_bundles,
			],
		];
	}

	public function getBundlesInstructor( $id_instructor ) {
		$user_query = new \WP_User_Query(
			array(
				'meta_key' => 'lema_bundle_instructor',
				'user_id'  => $id_instructor,
			)
		);

		return $user_query->results;
	}

	/**
	 * @param $id
	 * @param int $limit
	 *
	 * @return BundleModel[]
	 */
	public function getInstructorBundles( $id, $limit = 2 ) {
		$assigned = get_user_meta( $id, 'lema_bundle_instructor' );
		$models   = [];
		if ( count( $assigned ) > 0 ) {
			for ( $i = 0; $i < $limit; $i ++ ) {
				//Just get random for now
				$models[] = BundleModel::findOne( $assigned[ $i ] );
				if ( $i == count( $assigned ) - 1 ) {
					break;
				}
			}
		}

		return $models;
	}

	/**
	 * [getReviews description]
	 *
	 * @param  [string] $listId [id,id,id]
	 *
	 * @return [object]         [list rows review]
	 */
	public function getReviews( $listId ) {

	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {

		$data            = $this->getData( $data );
		$list_instructor = [];

		//get list_instructor by user_id
		if ( $data['data']['user_by'] !== '' ) {
			if ( $data['data']['user_filter'] == 'id' ) {
				$list_instructor = get_users( [ 'include' => explode( ',', $data['data']['user_by'] ) ] );
			} else {
				$list_instructor = get_user_by( $data['data']['user_filter'], $data['data']['user_by'] );
			}

		} else {
			//get list_instructor by post_id

			/** @var BundleModel $model */
			$model           = BundleModel::findOne( $data['data']['post_id'] );
			$meta_instructor = $model->getInstructors( $data['data']['post_id'], '', $data['data']['limit'] );

			if ( ! empty( $meta_instructor ) ) {
				$list_instructor = get_users( [ 'include' => array_keys( $meta_instructor ) ] );
			}

		}


		$data['list_instructor'] = $list_instructor;

		return $this->render( $this->contentView, $data, true );
	}
}