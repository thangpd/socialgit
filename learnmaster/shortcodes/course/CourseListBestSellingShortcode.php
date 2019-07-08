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
use lema\models\OrderItemModel;
use lema\shortcodes\course\CourseListShortcode;

class CourseListBestSellingShortcode extends CourseListShortcode
{
    const SHORTCODE_ID              = 'lema_course_list_best_selling';

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
        return array_merge(parent::getAttributes(),[
            'limit' => 10, //Limit of list
            'filter_by_cat' => '',
        ]);
    }

    /**
     * Return array of arguments for WP_Query usage
     * @param Object $data attributes of the shortcode
     * @return Array
     */
    public function getQuery($data) {
        $limit = $data->limit;
        if (!is_numeric($limit) ||  empty($limit)) {
            $limit = 10;
        }
        $courses = OrderItemModel::getTopSales($limit);
        $courseIds = array_column($courses, 'course_id');
        $courseIds = lema()->hook->registerFilter('lema_courselist_bestselling' , $courseIds, $data);
        if (!empty($courseIds)) {
            $cacheName = "course_bestselling";
            if (!empty($data->filter_by_cat)) {
                $cacheName = $cacheName . '_' . $data->filter_by_cat;
            }
            lema()->cache->set($cacheName, $courseIds);
        }
        $args = array(
            'post_type' => 'course',
            'post__in' => $courseIds,
        );

        if($data->filter_by_cat !== ''){
            
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'cat_course',
                    'field' => (is_numeric($data->filter_by_cat)) ? 'term_id' : 'slug',
                    'terms' => $data->filter_by_cat
                )
            );
        }
        $args = lema()->hook->registerFilter('lema_courselist_bestselling_query' , $args, $data);
        return $args;
    }
}