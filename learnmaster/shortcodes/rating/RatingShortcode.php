<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\rating;


use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;
use lema\models\RatingModel;

class RatingShortcode extends Shortcode {
	const SHORTCODE_ID = 'lema_rating';
	const STYLE_TYPE_FULL = 'full';
	const STYLE_TYPE_SIMPLE = 'simple';
	const STYLE_TYPE_CUSTOM = 'custom';
	const STYLE_TYPE_VIEW = 'viewcomment';

	const DEFAULT_TEMPLATE = '{label}{rating}{number}{total}';

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		return self::SHORTCODE_ID;
	}


	/**
	 * Get attributes of this shortcode (supported params)
	 * @return array
	 */
	public function getAttributes() {
		return [
			'type'                => 'course',
			'object_id'           => '', //required
			'style'               => self::STYLE_TYPE_FULL, //simple
			'template'            => self::DEFAULT_TEMPLATE,
			'readonly'            => true,
			'label'               => '',
			'review_only'         => false,
			'static'              => false,
			'static_value'        => 0,
			'has_rating'          => 0,
			'show_average_rating' => 1,
			'show_total_rating'   => 1,
			'text_rating'         => 'RATE THIS COURSE',
		];
	}

	/**
	 * Register static assets for this shortcode
	 * @return array
	 */
	public function getStatic() {
		return [
			[
				'type'         => 'script',
				'id'           => 'lema-shortcode-rating-script',
				'isInline'     => false,
				'url'          => 'assets/scripts/lema-shortcode-rating.js',
				'dependencies' => [ 'lema', 'lema.shortcode', 'lema.ui' ]
			],
			[
				'type'         => 'style',
				'id'           => 'lema-shortcode-rating-style',
				'isInline'     => false,
				'url'          => 'assets/styles/lema-shortcode-rating.css',
				'dependencies' => [ 'lema-shortcode-style', 'font-awesome' ]
			],
			[
				'type'     => 'style',
				'id'       => 'lema-comment-style',
				'isInline' => false,
				'url'      => 'assets/styles/lema-comment.css',
			]
		];
	}

	/**
	 * Do rate
	 */
	public function doRating() {
		$data = $_POST;
		if ( lema()->wp->is_user_logged_in() ) {
			$userId   = lema()->wp->get_current_user_id();
			$objectId = @$data['object_id'];
			$rate     = @$data['value'];

			if ( $userId && $objectId ) {
				$rate            = new RatingModel();
				$rate->type      = $data['type'];
				$rate->object_id = $objectId;
				$rate->rate      = $rate;
				$rate->user_id   = $userId;
				$rate->save();
				if ( $data['type'] == 'course' ) {
					lema()->hook->registerHook( 'lema_shortcode_course_flushcache', $objectId );
				}

				return $this->responseJson( [
					'message' => __( 'Rate success', 'lema' ),
					'data'    => $this->getShortcodeContent( $data )
				] );
			}

		}

		return $this->responseJson( [
			'code'    => 403,
			'message' => __( 'Please login your account to do this action.', 'lema' )
		] );

	}


	/**
	 * @param array $data
	 * @param array $params
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		global $wpdb;
		$data     = $this->getData( $data );
		$objectId = (int) $data['data']['object_id'];
		$type     = $data['data']['type'];
		$mode     = 'simple';
		switch ( $data['data']['style'] ) {
			case self::STYLE_TYPE_SIMPLE :
				$this->contentView = 'simple';
				break;
			case self::STYLE_TYPE_CUSTOM :
				$this->contentView = 'custom';
				break;
			case self::STYLE_TYPE_VIEW :
				$this->contentView = 'viewcomment';
				break;
			default :
				$this->contentView = 'full';
				$mode              = 'adv';
				break;
		}
		if ( $data['data']['static'] ) {
			$this->contentView = 'static';
		} else {
			if ( ! empty( $type ) && ! empty( $objectId ) ) {
				$status         = RatingModel::getRateStatus( $type, $objectId, $mode );
				$data['status'] = $status;
				// get all list comments
				$list_comments         = RatingModel::getAllReviews( $objectId );
				$data['list_comments'] = $list_comments;

			}
		}
		$this->contentView = apply_filters( 'lema_rating_view', $this->contentView );

		return $this->render( $this->contentView, $data, true );
		/*$rate = RatingModel::findOne(1);
		$rate->type = 'course';
		$rate->object_id = '1234';
		$rate->rate = 4;
		$rate->user_id = 123;
		$rate->save();*/
	}

	/**
	 * [get_all_cols_in_table description]
	 *
	 * @param  [string] $table_name [ name table to check ]
	 * @param  [array] $data       [ post array ]
	 * @param  [object] $list_cols  [ object data to append ]
	 *
	 * @return [object]             [ return object has been appended ]
	 */
	public function get_all_cols_in_table( $table_name, $data, $list_cols ) {
		global $wpdb;
		$table_name = $wpdb->prefix . $table_name;
		// get all cols in table rating
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			// check isset $_POST value & push to array
			if ( isset( $data[ $column_name ] ) ) {
				$list_cols->$column_name = $data[ $column_name ];
			}
		}

		return $list_cols;
	}

	public function postRating() {
		$data = $_POST;

		if ( lema()->wp->is_user_logged_in() ) {
			$userId   = lema()->wp->get_current_user_id();
			$objectId = @$data['object_id'];
			//$rate = @$data['value'];

			if ( $userId && $objectId ) {
				$rate = new RatingModel();

				//add some params to save
				$data['user_id'] = $userId;
				$data['type']    = 'course';

				$rate = $this->get_all_cols_in_table( \lema\models\RatingModel::TABLE_NAME, $data, $rate );
				// check status rating $rate->isNew
				$rate->checkStatus();
				$rate->save();
				lema()->hook->registerHook( 'lema_rating_refresh', $objectId, $data['type'], 'simple' );
				lema()->hook->registerHook( 'lema_rating_refresh', $objectId, $data['type'], 'full' );
				if ( $data['type'] == 'course' ) {
					lema()->hook->registerHook( 'lema_shortcode_course_flushcache', $objectId );
				}

				return $this->responseJson( [
					'message' => __( 'Rate success', 'lema' ),
					'html'    => $this->getShortcodeContent( $data )
				] );
			}

		}

		return $this->responseJson( [
			'code'    => 403,
			'message' => __( 'Please login your account to do this action.', 'lema' )
		] );
	}

	public function deleteRating() {
		$data = $_POST;

		if ( current_user_can( 'administrator' ) ) {

			$userId   = get_current_user_id();
			$rateID   = ! empty( $_POST['rateID'] ) ? $_POST['rateID'] : '';
			$data     = ! empty( $_POST['data'] ) ? json_decode( urldecode( $_POST['data'] ) ) : '';
			$objectId = $data->object_id;
			if ( $userId && $rateID ) {
				$rate = new RatingModel( array( 'id' => $rateID ) );

				if ( $rate->delete() ) {
					$message = __( 'Rate delete success', 'lema'
					);
				} else {
					$message = __( 'Rate delete fail', 'lema' );
				}
				if ( $data->type == 'course' ) {
					lema()->hook->registerHook( 'lema_shortcode_course_flushcache', $objectId );
				}
				$list_comments = RatingModel::getAllReviews( $objectId );
				$res           = $this->render(
					'rating_comment',
					array(
						'data'          => $data,
						'list_comments' => $list_comments
					) );

				return $this->responseJson( [
					'ajax'    => $res,
					'message' => $message,
				] );
			} else {
				return $this->responseJson( [
					'code'    => 403,
					'message' => __( 'RateID or userID not found', 'lema' ),
				] );
			}

		}

		return $this->responseJson( [
			'code'    => 403,
			'message' => __( 'Please login your account to do this action.', 'lema' )
		] );
	}

	public function actions() {
		return [
			'ajax' => [
				'lema_post_rating'   => [ $this, 'postRating' ],
				'lema_delete_rating' => [ $this, 'deleteRating' ],
			]
		];
	}

}