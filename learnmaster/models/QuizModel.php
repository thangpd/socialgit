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

class QuizModel extends Model implements ModelInterface
{

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return [
            'post_title' => [
                'label' => 'Title',
                'validate' => ['text', ['required' => true, 'message' => 'Please enter a valid achievement']],
                'form' => [
                    'type' => 'text',
                    'class' => 'form-control la-input input-title',
                    'name' => 'post_title',
                    'label' => __('Quiz title', 'lema'),
                    'attributes' => [
                        'maxlength' => 80
                    ],
                    'template' => '<div class="la-form-group">{label}<div class="la-textbox-group">{input}<span class="la-input-length">80</span> </div></div>'
                ],
                
            ],
            'deadline' => [
                'label' => 'Deadline',
                'validate' => ['text', ['message' => 'Please enter content']],
                'form' => [
                    'type' => 'text',
                    'class' => 'form-control la-input input-title',
                    'name' => 'Quiz[deadline]',
                    'label' => __('Deadline time', 'lema'),
                    'attributes' => [
                        'maxlength' => 80
                    ],
                    'template' => '<div class="la-form-group">{label}<div class="la-textbox-group">{input}<span class="la-input-length">80</span> </div></div>'
                ],
                
            ],
            'est_time' => [
                'label' => 'Estimate time',
                'validate' => ['text', ['message' => 'Please enter content']],
                'form' => [
                    'type' => 'text',
                    'class' => 'form-control la-input input-title',
                    'name' => 'Quiz[est_time]',
                    'label' => __('Estimate time', 'lema'),
                    'attributes' => [
                        'maxlength' => 80,
                        'placeHolder' => 'Please enter number "seconds" Estimate time'
                    ],
                    'template' => '<div class="la-form-group">{label}<div class="la-textbox-group">{input}<span class="la-input-length">80</span> </div></div>'
                ],
                
            ],
            'attempt' => [
                'label' => 'Attempt',
                'validate' => ['text', ['message' => 'Please enter Attempt percent']],
                'form' => [
                    'type' => 'text',
                    'class' => 'form-control la-input input-title',
                    'name' => 'Quiz[attempt]',
                    'label' => __('Attempt time', 'lema'),
                    'attributes' => [
                        'maxlength' => 3,
                        'placeHolder' => 'Please enter Attempt percent (< 100%)'
                    ],
                    'template' => '<div class="la-form-group">{label}<div class="la-textbox-group">{input}<span class="la-input-length">80</span> </div></div>'
                ],
                
            ],
            'description' => [
                'label' => 'Description',
                'validate' => ['textarea', ['cols' => 5,'rows'=>3, 'required' => true, 'message' => 'Please enter a valid description']],
                'form' => [
                    'type' => 'textarea',
                    'class' => 'tinymce-st-1 la-form-control',
                    'name' => 'Quiz[description]',
                    'label' => __('Quiz description', 'lema'),
                    'template' => '<div class="la-form-group">{label}<div class="la-form-group">{input}</div></div>'
                ],
            ]

        ];
    }

    /**
     * Abstract function get name of table/model
     * @return mixed
     */
    public function getName()
    {
        return 'quiz';
    }
    public static function getPosttypeConfig()
    {
        $labels = array(
            'name'               => _x( 'Quiz', 'post type general name', 'lema' ),
            'singular_name'      => _x( 'Quiz', 'post type singular name', 'lema' ),
            'menu_name'          => _x( 'Quiz', 'admin menu', 'lema' ),
            'name_admin_bar'     => _x( 'Quiz', 'add new on admin bar', 'lema' ),
            'add_new'            => _x( 'Add Quiz', 'Course', 'lema' ),
            'add_new_item'       => __( 'Add New Quiz', 'lema' ),
            'new_item'           => __( 'New Quiz', 'lema' ),
            'edit_item'          => __( 'Edit Quiz', 'lema' ),
            'view_item'          => __( 'View Quiz', 'lema' ),
            'all_items'          => __( 'Quizs', 'lema' ),
            'search_items'       => __( 'Search Quiz', 'lema' ),
            'parent_item_colon'  => __( 'Parent Quiz:', 'lema' ),
            'not_found'          => __( 'No Quiz found.', 'lema' ),
            'not_found_in_trash' => __( 'No Quiz found in Trash.', 'lema' )
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
                'name' => 'quiz',
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
     * Get all question of quiz
     * @return array
     */
    public function getQuestions(){
        $args = array(
            'post_type' => array('question'),
            'post_parent' => $this->post->ID,
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'post_status' => 'draft',
        );
        $query = new \WP_Query( $args );
        return $query->posts;

    }

}