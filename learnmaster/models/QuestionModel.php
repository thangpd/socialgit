<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\models;


use lema\admin\controllers\QuestionController;
use lema\core\interfaces\ModelInterface;

use lema\core\Model;
use lema\admin\controllers\CourseController;

class QuestionModel extends Model implements ModelInterface
{

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return [
            'answer' =>[
                'label' => __('Answers', 'lema'),
                'form' => [
                    'label' => __('Answers :', 'lema'),
                    'type' => 'custom',
                    'renderer' => [QuestionController::getInstance(), 'renderAnswerOption'],
                    'class' => 'la_form_control',
                    'name' => 'answer[]',
                    'placeholder' => __('Example: You should be able to use a PC at a beginner level', 'lema'),
                ]
            ],
            'related_lesson' => [
                'label' => __('Related lesson', 'lema'),
            ]
        ];
    }

    /**
     * Abstract function get name of table/model
     * @return mixed
     */
    public function getName()
    {
        return 'question';
    }
    public static function getPosttypeConfig()
    {
        $labels = array(
            'name'               => _x( 'Question', 'post type general name', 'lema' ),
            'singular_name'      => _x( 'Question', 'post type singular name', 'lema' ),
            'menu_name'          => _x( 'Question', 'admin menu', 'lema' ),
            'name_admin_bar'     => _x( 'Question', 'add new on admin bar', 'lema' ),
            'add_new'            => _x( 'Add Question', 'Course', 'lema' ),
            'add_new_item'       => __( 'Add New Question', 'lema' ),
            'new_item'           => __( 'New Question', 'lema' ),
            'edit_item'          => __( 'Edit Question', 'lema' ),
            'view_item'          => __( 'View Question', 'lema' ),
            'all_items'          => __( 'Questions', 'lema' ),
            'search_items'       => __( 'Search Question', 'lema' ),
            'parent_item_colon'  => __( 'Parent Question:', 'lema' ),
            'not_found'          => __( 'No Question found.', 'lema' ),
            'not_found_in_trash' => __( 'No Question found in Trash.', 'lema' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'lema' ),
            'public'             => true,
            'show_ui'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'thumbnail'),
        );

        return [
            'post' => [
                'name' => 'question',
                'args' => $args
            ]
        ];
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
     * @return array
     */
    public static function findAll()
    {
        $args = array(
            'post_type' => array('question'),
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $query = new \WP_Query( $args );
        return $query->posts;
    }
}