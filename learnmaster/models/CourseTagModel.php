<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\models;


use lema\core\interfaces\CourseInterface;
use lema\core\interfaces\CourseTagInterface;
use lema\core\interfaces\ModelInterface;
use lema\core\Model;

class CourseTagModel extends Model implements CourseTagInterface
{

    /**
     * Get list of course related to this tag
     * @return CourseInterface[]
     */
    public function getCourses()
    {
        // TODO: Implement getCourses() method.
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        // TODO: Implement getAttributes() method.
    }

    /**
     * Abstract function get name of table/model
     * @return mixed
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }


    /**
     * Save object properties to database
     * @return boolean
     */
    public function save()
    {
        // TODO: Implement save() method.
    }




    /**
     * Delete a object by primary key
     * @return boolean
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return mixed
     */
    public static function getPosttypeConfig()
    {
        $labels = array(
            'name'              => _x( 'Course Tags', 'taxonomy general name', 'lema' ),
            'singular_name'     => _x( 'Course Tag', 'taxonomy singular name', 'lema' ),
            'search_items'      => __( 'Search Tag', 'lema' ),
            'all_items'         => __( 'All Tags', 'lema' ),
            'parent_item'       => __( 'Parent Tag', 'lema' ),
            'parent_item_colon' => __( 'Parent Tag :', 'lema' ),
            'edit_item'         => __( 'Edit Tag', 'lema' ),
            'update_item'       => __( 'Update Tag', 'lema' ),
            'add_new_item'      => __( 'Add New Tag', 'lema' ),
            'new_item_name'     => __( 'New Tag Name', 'lema' ),
            'menu_name'         => __( 'Tags', 'lema' ),
        );
        return [
            'taxonomy' => [
                'name' => 'tag_course',
                'object_type' => ['course'],
                'args' => [
                    'hierarchical'      => false,
                    'labels'            => $labels,
                    'show_ui'           => true,
                    'show_in_menu'      => false,
                    'show_admin_column' => true,
                    'query_var'         => true,
                ]
            ],
        ];
    }
}