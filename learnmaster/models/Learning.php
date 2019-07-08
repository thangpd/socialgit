<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\models;


use lema\core\BaseObject;
use lema\core\RuntimeException;

class Learning extends BaseObject
{
    /**
     * @var \WP_Post
     */
    public $course;

    /**
     * Learning constructor.
     * @param \WP_User|string $id
     * @throws RuntimeException
     */
    public function __construct($id)
    {
        parent::__construct([]);
        if (!is_object($id)) {
            if (is_numeric($id)) {
                $course = get_post($id);
            } else {
                $course = get_post(['post_type' => 'course', 'name' => $id]);
            }
        }
        if (empty($course)) {
            throw new RuntimeException(__('Invalid course', 'lema'));
        }
        $this->course  = $course;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        $defaultUrl = site_url() . '/lema-learning/' . $this->course->name . '/'.$this->course->ID;
        return lema()->hook->registerFilter('lema_learning_url', $defaultUrl);
    }
}