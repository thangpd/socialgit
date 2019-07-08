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

class CourseListCategoryShortcode extends CourseListShortcode
{
    const SHORTCODE_ID              = 'lema_course_list_category';

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
        return array_merge(parent::getAttributes(), ['cat' => '']);
    }

    /**
     * Return array of arguments for WP_Query usage
     * @param Object $data attributes of the shortcode
     * @return Array
     */
    public function getQuery($data) {
        $args = array('post_type' => 'course',
            'tax_query' => array(
                array(
                    'taxonomy' => 'cat_course',
                    'field' => 'slug',
                    'terms' => $data->cat,
                ),
            ),
        );
        return $args;
    }
}