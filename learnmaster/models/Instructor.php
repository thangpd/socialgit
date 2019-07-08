<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\models;


use lema\core\RuntimeException;

class Instructor extends UserModel {
	/**
	 * @var \WP_User
	 */
	public $user;

	/**
	 * Instructor constructor.
	 *
	 * @param \WP_User $user
	 */
	public function __construct( $user ) {
		parent::__construct( [] );
		if ( ! is_object( $user ) ) {
			if ( is_numeric( $user ) ) {
				$user = get_user_by( 'ID', $user );
			} else {
				$user = get_user_by( 'login', $user );
			}
		}
		if ( empty( $user ) ) {
			throw new RuntimeException( __( 'Invalid user', 'lema' ) );
		}
		$this->user = $user;
	}

	/**
	 * @return bool
	 */
	public function isInstructorRole() {
		return in_array( 'lema_instructor', $this->user->roles );
	}

	/**
	 * @return bool
	 */
	public static function isInstructor( \WP_User $user ) {
		return in_array( 'lema_instructor', $user->roles );
	}

	/**
	 * Get all course of an instructor
	 *
	 * @param bool $object
	 *
	 * @return array|mixed
	 */
	public function getCourses( $object = false ) {
		$assigned = get_user_meta( $this->user->ID, self::instructorMetaKey() );
		if ( $object ) {
			$models = [];
			foreach ( $assigned as $item ) {
				if ( $item instanceof CourseModel ) {
					$course_obj = CourseModel::findOne( $item );
					if ( $course_obj ) {
						$models[] = $course_obj;
					}
				}
			}

			return $models;
		}

		return $assigned;
	}


	/**
	 * @return object
	 */
	public function getRating() {
		$rate = lema()->cache->get( 'instructor_rating_' . $this->user->user_login );
		if ( empty( $rate ) || LEMA_DEBUG ) {
			$courses = $this->getCourses();
			if ( ! empty( $courses ) ) {
				$rate = RatingModel::getRateStatus( 'course', $courses );
				lema()->cache->set( 'instructor_rating_' . $this->user->user_login, $rate );
			} else {
				$rate = (object) [
					'avg'   => 0.0,
					'total' => 0
				];
			}

		}

		return $rate;
	}

	/**
	 * @return mixed
	 */
	public function getProfileUrl() {
		$defaultUrl = lema()->page->getPageUrl( 'lema-instructor/' . $this->user->user_login . '/profile' );

		return lema()->hook->registerFilter( 'lema_instructor_profile_url', $defaultUrl, $this );
	}

	/**
	 * Get instructor mata key
	 * @return string
	 */
	public static function instructorMetaKey() {
		$metaKey = 'lema_course_instructor';
		if ( is_multisite() ) {
			$siteId  = get_current_blog_id();
			$metaKey = "site{$siteId}-{$metaKey}";
		}

		return $metaKey;
	}
}