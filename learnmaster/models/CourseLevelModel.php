<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/24/17.
 */

namespace lema\models;

use lema\core\interfaces\CourseCategoryInterface;
use lema\core\Model;

class CourseLevelModel extends Model implements CourseCategoryInterface
{

    /**
     * Get Custom Meta Data
     *
     * @return array
     */
    public function getAttributes()
    {
        return [];
    }

    /**
     * Abstract function get name of table/model
     * @return mixed
     */
    public function getName()
    {
        return 'level_course';
    }

    /**
     * Save object properties to database
     * @return boolean
     */
    public function save()
    {
    }

    /**
     * Delete a object by primary key
     *
     *
     * @return boolean
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * Get list of course related to this category
     *
     * @return bool
     */
    public function getCourses()
    {
        $courses = false;
        if (isset($this->term_id) && is_int($this->term_id)) {
            $course_model = CourseModel::getInstance();
            $courses = $course_model->getAll(array(
                'tax_query' => array(
                    array(
                        'taxonomy' => $this->getName(),
                        'field' => 'id',
                        'terms' => $this->term_id,
                        'include_children' => true, // get child post
                    )
                ),
            ));
        }

        return $courses;
    }

    /**
     * @return mixed
     */
    public static function getPosttypeConfig()
    {
        //taxonomy Cat
        $labels = array(
            'name' => _x('Levels', 'taxonomy general name', 'lema'),
            'singular_name' => _x('Level', 'taxonomy singular name', 'lema'),
            'search_items' => __('Search Course Level', 'lema'),
            'all_items' => __('All Levels', 'lema'),
            'parent_item' => __('Parent Level', 'lema'),
            'parent_item_colon' => __('Parent Level :', 'lema'),
            'edit_item' => __('Edit Level', 'lema'),
            'update_item' => __('Update Level', 'lema'),
            'add_new_item' => __('Add New Level', 'lema'),
            'new_item_name' => __('New Level Name', 'lema'),
            'menu_name' => __('Levels', 'lema'),
        );

        return [
            'taxonomy' => [
                'name' => 'level_course',
                'object_type' => 'course',
                'args' => [
                    'labels' => $labels,
                    'show_ui' => true,
                    'show_admin_column' => false,
                    'show_in_menu' => true,
                    'show_in_nav_menus' => true,
                    'query_var' => true,
                    'meta_box_cb' => false,
                    'hierarchical' => false

                ]
            ],

        ];

    }

    /**
     * @param $id
     *
     * @return bool|Model
     */
    public static function findOne($id)
    {
        $post = get_term($id, 'level_course');
        if (!empty($post)) {
            $modelClass = self::className();
            /** @var Model $model */
            $model = new $modelClass();
            $model->post = $post;
            $model->getData();

            return $model;
        }

        return false;
    }

    /**
     * get all data taxonomy level
     * @return [object]
     */
    public function getAll($args = array())
    {
        $args = array_merge(
            array(
                'taxonomy' => $this->getName(),
                'hide_empty' => true,
            ),
            $args
        );

        $categories = array();
        $terms = get_terms($args);
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $categories[] = self::findOne($term->term_id);
            }
        }

        return $categories;
    }

    /**
     * get Option [slug=>name]
     * @return []
     */
    public function getOptions()
    {
        $options = [];
        $categories = $this->getAll(['hide_empty' => false]);
        if (!empty($categories)) {
            foreach ($categories as $i => $cat) {
                $options[$cat->slug] = $cat->name;
            }
        }
        return $options;
    }

    /**
     * save Level for post
     * @param  string $post_id
     * @param  [] $listLevel
     */
    public function saveLevel($post_id, $listLevel)
    {
        wp_set_post_terms($post_id, $listLevel, $this->getName());
    }

}