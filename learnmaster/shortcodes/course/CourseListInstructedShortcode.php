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
use lema\models\Instructor;
use lema\shortcodes\course\CourseListShortcode;

/**
 * Display the courses which are instructed by an instructor
 */
class CourseListInstructedShortcode extends CourseListShortcode
{
    const SHORTCODE_ID              = 'lema_course_list_instructed';

    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return self::SHORTCODE_ID;
    }

    /**
     * Array of default value of all shortcode options
     * @return array
     */
    public function getAttributes()
    {
        return array_merge(parent::getAttributes(), ['instructor_id' => '', 'summary' => 1]);
    }

    /**
     * Return array of arguments for WP_Query usage
     * @param Object $data attributes of the shortcode
     * @return Array
     */
	public function getQuery( $data ) {
		if ( ! empty( $data->instructor_id ) ) {
			//maybe instructor is array
			$instructor_array = json_decode( urldecode( $data->instructor_id ) );
			$courses          = array();
			if ( ! empty( $data->instructor_id ) && is_array( $instructor_array ) ) {
				//many instructor get courses;
				$data->instructor_id = $instructor_array;
				foreach ( $data->instructor_id as $i => $ins_id ):
					$instructor = new Instructor( $ins_id );
					$courses    = array_merge( $courses, $instructor->getCourses() );
				endforeach;
			} elseif ( ! empty( $data->instructor_id ) && ! is_array( $instructor_array ) ) {
				//only one instructor
				$instructor = new Instructor( $data->instructor_id );
				$courses    = array_merge( $courses, $instructor->getCourses() );
			}
			if ( ! empty( $courses ) ) {
				$args = array( 'post_type' => 'course', 'post__in' => $courses );
				if ( ! empty( $data->search_term ) ) {
					$args['s'] = $data->search_term;
				}

				return $args;
			}

		}

		return false;

	}
}