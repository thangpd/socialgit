<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\models;


use lema\core\interfaces\ModelInterface;

use lema\core\Model;

class ChapterModel extends Model implements ModelInterface
{
    public $courseId;
    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return [
            'post_title' => [
                'label' => 'Title',
                'validate' => ['text', ['maxLength' => 200, 'minLength' => 20, 'required' => true, 'message' => 'Please enter a valid achievement']],
                'form' => [
                    'type' => 'text',
                    'class' => 'form-control la-input input-title',
                    'name' => 'post_title',
                    'label' => __('Chapter title', 'lema'),
                    'attributes' => [
                        'maxlength' => 80
                    ],
                    'template' => '<div class="la-form-group">{label}<div class="la-textbox-group">{input}<span class="la-input-length">80</span> </div></div>'
                ],
                
            ],
            'achievement' => [
                'label' => 'Achievement',
                'validate' => ['text', ['maxlength' => 200, 'required' => true, 'message' => 'Please enter a valid achievement']],
                'form' => [
                    'type' => 'text',
                    'class' => 'form-control la-input input-title',
                    'name' => 'Chapter[achievement]',
                    'label' => __('What will students be able to do at the end of this Chapter?*', 'lema'),
                    'attributes' => [
                        'maxlength' => 200,
                    ],
                    'template' => '<div class="la-form-group">{label}<div class="la-textbox-group">{input}<span class="la-input-length">200</span> </div></div>'
                ],
            ]
        ];
    }

    /**
     * @param $courseId
     */
    public function beforeSave($courseId)
    {

    }

    /**
     *
     */
    public function save()
    {
        lema()->wp->wp_write_post();

    }
    /**
     * Abstract function get name of table/model
     * @return mixed
     */
    public function getName()
    {
        return 'chapter';
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
     * Get all Quiz of chapter
     * @return array
     */
    public function getQuizs(){
        $args = array(
            'post_type' => array('quiz'),
            'post_parent' => $this->post->ID,
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_status' => ['private', 'public', 'draft']
        );
        $query = new \WP_Query( $args );
        return $query->posts;

    }
    /**
     * Get all Quiz of chapter
     * @return array
     */
    public function getLessons(){
        $args = array(
            'post_type' => array('lesson'),
            'post_parent' => $this->post->ID,
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_status' => ['private', 'public', 'draft']
        );
        $query = new \WP_Query( $args );
        return $query->posts;

    }

    /**
     * @return mixed
     */
    public static function getPosttypeConfig()
    {
        $labels = array(
            'name'               => _x( 'Chapter', 'post type general name', 'lema' ),
            'singular_name'      => _x( 'Chapter', 'post type singular name', 'lema' ),
            'menu_name'          => _x( 'Chapter', 'admin menu', 'lema' ),
            'name_admin_bar'     => _x( 'Chapter', 'add new on admin bar', 'lema' ),
            'add_new'            => _x( 'Add Chapter', 'Course', 'lema' ),
            'add_new_item'       => __( 'Add New Chapter', 'lema' ),
            'new_item'           => __( 'New Chapter', 'lema' ),
            'edit_item'          => __( 'Edit Chapter', 'lema' ),
            'view_item'          => __( 'View Chapter', 'lema' ),
            'all_items'          => __( 'Chapters', 'lema' ),
            'search_items'       => __( 'Search Chapter', 'lema' ),
            'parent_item_colon'  => __( 'Parent Chapter:', 'lema' ),
            'not_found'          => __( 'No Chapter found.', 'lema' ),
            'not_found_in_trash' => __( 'No Chapter found in Trash.', 'lema' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'lema' ),
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'supports'           => array( 'title', 'thumbnail'),
        );

        return [
            'post' => [
                'name' => 'chapter',
                'args' => $args
            ],
            'actions' => [
                'after_course_save' => [self::getInstance(), 'beforeSave']
            ]
        ];
    }


}