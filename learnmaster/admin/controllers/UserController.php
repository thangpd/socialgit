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
use lema\models\Instructor;

class UserController extends AdminController implements AdminControllerInterface
{
    /**
     * @param $columns
     * @return mixed
     */
    public function userColumns($columns) {
        $columns['courses'] = __('Courses', 'lema');
        return $columns;
    }

    /**
     * @param $val
     * @param $column_name
     * @param $user_id
     * @return mixed
     */
    public function userColumnValue($val, $column_name, $user_id)
    {
        switch ($column_name) {
            case 'courses' :
                $_val = lema()->cache->get('instructor_courses_list_'. $user_id);
                if (empty($_val) || LEMA_DEBUG) {
                    $instructor = new Instructor($user_id);
                    if ($instructor->isInstructorRole()) {
                        $courses = $instructor->getCourses();
                        $val = count($courses);
                        if ($val > 0) {
                            $val = '<a href="edit.php?post_type=course&instructor=' . $user_id . '">' . $val  . '</a>';
                        }
                    }
                }

                break;
        }
        return $val;
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'actions' => [
                'manage_users_columns' => [self::getInstance(), 'userColumns'],
                'manage_users_custom_column' => [self::getInstance(), 'userColumnValue', 15, 3],
            ]
        ];
    }
}