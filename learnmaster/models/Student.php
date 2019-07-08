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

class Student extends UserModel
{
    /**
     * @var \WP_User
     */
    public $user;
    /**
     * Instructor constructor.
     * @param \WP_User|int $user
     */
    public function __construct($user)
    {
        parent::__construct([]);
        if (!is_object($user)) {
            if (is_numeric($user)) {
                $user = get_user_by('ID', $user);
            } else {
                $user = get_user_by('login', $user);
            }
        }
        if (empty($user)) {
            throw new RuntimeException(__('Invalid user', 'lema'));
        }
        $this->user  = $user;
    }

    /**
     * @return mixed
     */
    public function getProfileUrl()
    {
        $defaultUrl = lema()->page->getPageUrl(lema()->config->getUrlConfigs('lema_user_profile'));
        return lema()->hook->registerFilter('lema_student_profile_url', $defaultUrl);
    }

    /**
      * check student enrolled this course
      */ 
    public function checkEnrolled($courseId) {
        $isCheck = \lema\models\OrderItemModel::checkUserEnrolledCourse($courseId, $this->user->ID);
	    $isCheck = lema()->wp->apply_filters('lema_pre_check_enrolled', $isCheck, $courseId);
        return $isCheck;
    }

    /**
     * this course is completed
     */
    public function checkStatusCompleted($courseId)
    {
        $isCheck = \lema\models\OrderItemModel::checkStatusCourseCompleted($courseId);
        return $isCheck;    
    }

    /**
     * Get data of current user
     * @return Student|null
     */
    public static function getCurrentUser()
    {
        if ( is_user_logged_in() ) {
            $userId = get_current_user_id();
            return new Student($userId);
        }
        return null;
    }



}