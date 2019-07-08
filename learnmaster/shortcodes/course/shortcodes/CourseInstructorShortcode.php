<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\course\shortcodes;


use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;
use lema\models\CourseModel;
use lema\models\Instructor;
use lema\models\UserModel;

class CourseInstructorShortcode extends Shortcode {
	const SHORTCODE_ID = 'lema_coursecard_instructor';
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
				'id'           => 'lema-course-detail-style',
				'isInline'     => false,
				'url'          => 'assets/styles/lema-course-detail.css',
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
			'show_course_popular' => 1,
			'show_role'           => 1,
			'show_icon_avatar'    => 1,
		];

		$attrs = apply_filters( 'lema_get_attribute_courseinstructor', $attrs );

		return $attrs;
	}

	public function getStatisticInstructor( $instructor ) {

		global $wpdb;

		$avgRating     = 0;
		$list_reviews  = $list_endrolls = [];
		$total_courses = count( $instructor->list_courses );

		if ( $total_courses ) {
			$list_id_course = [];
			foreach ( $instructor->list_courses as $course ) {
				$list_id_course[] = $course->ID;
			}
			// get list review
			$list_id_where = implode( ',', $list_id_course );
			$table         = $wpdb->prefix . 'lema_rating';
			$list_reviews  = $wpdb->get_results( $wpdb->prepare( "SELECT rate from {$table} WHERE object_id IN (%s)", $list_id_where ) );

			$table         = $wpdb->prefix . 'lema_order_item';
			$list_endrolls = $wpdb->get_results( $wpdb->prepare( "SELECT id from {$table} WHERE course_id IN (%s)", $list_id_where ) );

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
				'title' => 'Courses',
				'value' => $total_courses,
			],
		];
	}

	public function getCoursesInstructor( $id_instructor ) {
		$user_query = new \WP_User_Query(
			array(
				'meta_key' => 'lema_course_instructor',
				'user_id'  => $id_instructor,
			)
		);

		return $user_query->results;
	}

	/**
	 * @param $id
	 * @param int $limit
	 *
	 * @return CourseModel[]
	 */
	public function getInstructorCourses( $id, $limit = 2 ) {
		$assigned = get_user_meta( $id, 'lema_course_instructor' );
		$models   = [];
		if ( count( $assigned ) > 0 ) {
			for ( $i = 0; $i < $limit; $i ++ ) {
				//Just get random for now
				$models[] = CourseModel::findOne( $assigned[ $i ] );
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
		global $wpdb;
		global $wp_roles;

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

			/** @var CourseModel $model */
			$model           = CourseModel::findOne( $data['data']['post_id'] );
			$meta_instructor = $model->getInstructors( $data['data']['post_id'], '', $data['data']['limit'] );

			if ( ! empty( $meta_instructor ) ) {
				$list_instructor = get_users( [ 'include' => array_keys( $meta_instructor ) ] );
			}

		}

		//get list course instructor
		foreach ( $list_instructor as $key => $instructor ) {
			$instructorMetaKey = Instructor::instructorMetaKey();
			$list_courses      = $wpdb->get_results( $wpdb->prepare( "SELECT post_title,ID from $wpdb->posts LEFT JOIN $wpdb->usermeta ON $wpdb->posts.ID = $wpdb->usermeta.meta_value WHERE $wpdb->usermeta.meta_key = %s AND $wpdb->usermeta.user_id = %d AND $wpdb->posts.post_status = 'publish' ", $instructorMetaKey, $instructor->ID ) );

			$list_instructor[ $key ]->list_courses = $list_courses;

			$list_instructor[ $key ]->statistic = $this->getStatisticInstructor( $list_instructor[ $key ] );

		}


		$data['list_instructor'] = $list_instructor;

		return $this->render( $this->contentView, $data, true );
	}
}