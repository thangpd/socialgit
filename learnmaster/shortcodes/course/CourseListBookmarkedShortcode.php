<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

namespace lema\shortcodes\course;


use lema\core\Shortcode;
use lema\models\Student;
use lema\shortcodes\course\CourseListShortcode;
use lema\shortcodes\course\shortcodes\CourseBookmarkShortcode;

class CourseListBookmarkedShortcode extends CourseListShortcode {
	const SHORTCODE_ID = 'lema_course_list_bookmarked';

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
		return parent::getAttributes();
	}

	/**
	 * Get all bookmarked course ids
	 * @return array|mixed
	 */
	public function getBookmarkedCourseIds( $userID ) {

		$bookmarked = get_user_meta( $userID, CourseBookmarkShortcode::bookmarkName(), true );
		if ( empty( $bookmarked ) ) {
			$bookmarked = [];
		}

		return $bookmarked;
	}

	/**
	 * Return array of arguments for WP_Query usage
	 *
	 * @param Object $data attributes of the shortcode
	 *
	 * @return bool | array
	 */
	public function getQuery( $data ) {
		if ( is_user_logged_in() ) {
			if ( ! isset( $data->user_id ) ) {
				$data->user_id = get_current_user_id();
			}

			$courseIds = $this->getBookmarkedCourseIds( $data->user_id );
			if ( empty( $courseIds ) ) {
				$courseIds[] = - 1;
			}
			$args = array(
				'post_type' => 'course',
				'post__in'  => $courseIds,
			);

			return $args;
		}

		return false;
	}
}