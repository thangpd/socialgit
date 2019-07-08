<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\shortcodes\user;



use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;
//use lema\models\UserModal;

class UserInfoShortcode extends Shortcode
{
    const SHORTCODE_ID              = 'lema_user_info';

    const DEFAULT_TEMPLATE          = 'profile';
    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        return self::SHORTCODE_ID;
    }


    /**
     * Get attributes of this shortcode (supported params)
     * @return array
     */
    public function getAttributes()
    {
        return [
            'type'              => 'course',
            'object_id'         => '', //required
            'style'             =>  'simple', //simple
            'template'          => self::DEFAULT_TEMPLATE,
            'readonly'          =>  false,
            'label'             => ''
        ];
    }

    /**
     * Register static assets for this shortcode
     * @return array
     */
    public function getStatic()
    {
        // return [
        //     [
        //         'type'          => 'script',
        //         'id'            => 'lema-shortcode-rating-script',
        //         'isInline'      => false,
        //         'url'           => 'assets/scripts/lema-shortcode-rating.js',
        //         'dependencies'  => ['lema', 'lema.shortcode','lema.ui']
        //     ],
        //     [
        //         'type'          => 'style',
        //         'id'            => 'lema-comment-style',
        //         'isInline'      => false,
        //         'url'           => 'assets/styles/lema-comment.css',
        //     ]
        // ];
    }
}