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
use lema\shortcodes\course\CourseListShortcode;
use lema\models\RatingModel;

class CourseListTopRatingShortcode extends CourseListShortcode
{
    const SHORTCODE_ID              = 'lema_course_list_top_rating';

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
        return parent::getAttributes();
    }

    /**
     * Return array of arguments for WP_Query usage
     * @param Object $data attributes of the shortcode
     * @return Array
     */
    public function getQuery($data) {
        global $wpdb;

        $list_id = RatingModel::getTopRating();

        $list_id = array_column($list_id,'object_id');

        $args = array('post_type' => 'course', 'post__in' => $list_id);
        return $args;
    }
}