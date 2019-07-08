<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\models;


use lema\admin\controllers\LessonController;
use lema\core\interfaces\ModelInterface;

use lema\core\Model;

class LessonModel extends Model implements ModelInterface
{

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return [
            'content_type' => [
                'label' => 'Content Type',
                'form' => [
                    'type' => 'custom',
                    'renderer' => [LessonController::getInstance(), 'renderLessonType'],
                    'class' => 'form-control la-input input-title',
                    'name' => 'Lesson[content_type]',
                    'label' => __('Lesson type', 'lema'),
                ],
                
            ],
            'article' => [
                'label' => 'Lesson Content',
                //'validate' => ['textarea', ['cols' => 5,'rows'=>3, 'required' => true, 'message' => 'Please enter a valid description']],
                'form' => [
                    'type' => 'textarea',
                    'class' => 'tinymce-st-1 la-form-control',
                    'name' => 'Lesson[article]',
                    'label' => __('Lesson content', 'lema'),
                    'template' => '<div class="la-form-group"> <label class="lb-text">{label}</label><div class="la-form-group"><div class="la-editor">{input}</div> </div></div>'
                ],
            ],
            'video' => [
                'label' => 'Video file',
                'form' => [
                    'type' => 'custom',
                    'renderer' => [LessonController::getInstance(), 'renderLessonVideo'],
                    'class' => 'form-control la-input input-title',
                    'name' => 'Lesson[content_video]',
                    'label' => __('Lesson video', 'lema'),
                ],
            ],
            'audio' => [
                'label' => 'Audio file',
                'form' => [
                    'type' => 'custom',
                    'renderer' => [LessonController::getInstance(), 'renderLessonAudio'],
                    'class' => 'form-control la-input input-title',
                    'name' => 'Lesson[content_audio]',
                    'label' => __('Lesson audio', 'lema'),
                ],
            ],
            'resource_downloadable' => [
                'label' => 'Downloadable resource',
                'form' => [
                    'type' => 'custom',
                    'renderer' => [LessonController::getInstance(), 'renderLessonDownloadable'],
                    'class' => 'form-control la-input input-title',
                    'name' => 'Lesson[resource_downloadable]',
                    'label' => __('Downloadable resource', 'lema'),
                ],
            ],
            'resource_external' => [
                'label' => 'External resource',
                'form' => [
                    'type' => 'custom',
                    'renderer' => [LessonController::getInstance(), 'renderLessonExternal'],
                    'class' => 'form-control la-input input-title',
                    'name' => 'Lesson[resource_external]',
                    'label' => __('External resource', 'lema'),
                ],
            ],
            'resource_code' => [
                'label' => 'Source code',
                'form' => [
                    'type' => 'custom',
                    'renderer' => [LessonController::getInstance(), 'renderLessonCode'],
                    'class' => 'form-control la-input input-title',
                    'name' => 'Lesson[resource_code]',
                    'label' => __('Source code', 'lema'),
                ],
            ]

        ];
    }

    /**
     * Get all resource to show
     * return [
     *     [
     *           'filename' => '',
     *          'url' => ''
     *      ]
     * ]
     * @return array
     */
    public function getResourceFiles()
    {
        $files = [];
        $downloadables = $this->resource_downloadable;
        if ( !empty($downloadables) && is_array($downloadables)) {
            foreach ($downloadables as $file) {
                $file = json_decode($file, true);
                $files[] = [
                    'filename' => $file['filename'],
                    'url'       => $file['url'],
                    'mime'       => $file['mime']
                ];
            }
        }
        $codes = $this->resource_code;
        if ( !empty($codes) && is_array($codes)) {
            foreach ($codes as $file) {
                $file = json_decode($file, true);
                $files[] = [
                    'filename' => $file['filename'],
                    'url'       => $file['url'],
                    'mime'       => $file['mime']
                ];
            }
        }
        $externale = $this->resource_external;
        if (!empty($externale) && is_array($externale)) {
            $files[] = [
                'filename' => $externale['title'],
                'url'       => $externale['url']
            ];
        }
        return $files;
    }

    /**
     * Abstract function get name of table/model
     * @return mixed
     */
    public function getName()
    {
        return 'lesson';
    }
    public static function getPosttypeConfig()
    {
        $labels = array(
            'name'               => _x( 'Lesson', 'post type general name', 'lema' ),
            'singular_name'      => _x( 'Lesson', 'post type singular name', 'lema' ),
            'menu_name'          => _x( 'Lesson', 'admin menu', 'lema' ),
            'name_admin_bar'     => _x( 'Lesson', 'add new on admin bar', 'lema' ),
            'add_new'            => _x( 'Add Lesson', 'Course', 'lema' ),
            'add_new_item'       => __( 'Add New Lesson', 'lema' ),
            'new_item'           => __( 'New Lesson', 'lema' ),
            'edit_item'          => __( 'Edit Lesson', 'lema' ),
            'view_item'          => __( 'View Lesson', 'lema' ),
            'all_items'          => __( 'Lessons', 'lema' ),
            'search_items'       => __( 'Search Lesson', 'lema' ),
            'parent_item_colon'  => __( 'Parent Lesson:', 'lema' ),
            'not_found'          => __( 'No Lesson found.', 'lema' ),
            'not_found_in_trash' => __( 'No Lesson found in Trash.', 'lema' )
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
                'name' => 'lesson',
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
}