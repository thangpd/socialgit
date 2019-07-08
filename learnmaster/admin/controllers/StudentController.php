<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\admin\controllers;


use lema\core\interfaces\AdminControllerInterface;
use lema\admin\controllers\UserController;
class StudentController extends UserController implements AdminControllerInterface
{

	public $student;
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [];
    }

    /**
     * check student enrolled this course
     */
    public function checkCourseEnrolled($courseId)
    {
        if ( isset($this->student->ID) ) {
            $model = new \lema\models\Student($this->student->ID);
            return $model->checkEnrolled($courseId);
        }
        return false;
    }

    /**
     * check status course complete
     */
    public function checkCourseStatus($courseId)
    {
        $model = new \lema\models\Student($this->student->ID);
        return $model->checkStatusCompleted($courseId);
    }

    /**
     * get data current user
     */
    static function getCurrentUser()
    {
        $controller = self::getInstance();
    	if ( is_user_logged_in() ) {
    		$userId = get_current_user_id();
            $userData = get_userdata($userId);
            $controller->student = $userData;
    	}
        return $controller;
    }
}