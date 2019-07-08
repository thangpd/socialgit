<?php
namespace lema\admin\controllers;


use lema\core\components\Script;
use lema\core\components\Style;
use lema\core\interfaces\AdminControllerInterface;


class AdminController extends \lema\core\Controller implements AdminControllerInterface{
	public function init(){
        $this->viewPath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views/' . $this->getControllerName();
        parent::init();
	}


    /**
     *
     */
	public function searchUser()
    {
        $response = [];
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
            $users = new \WP_User_Query( array(
                'search'         => '*'.esc_attr( $q ).'*',
                'search_columns' => array(
                    'user_login',
                    'user_nicename',
                    'user_email',
                    'user_url',
                ),
            ) );
            $users = $users->get_results();
            $data = [];
            if ($users) {
                $response['message'] = 'Success';
                $response['data'] = [];
                foreach ($users as $user) {
                    /** @var \WP_User $user */
                    $data[] = [
                        'id'    => $user->ID,
                        'text'  => $user->display_name
                    ];
                }
            }
            $data = apply_filters('lema_search_user', $data, $q);
            $response['data'] = $data;
        }

        return $this->responseJson($response);
    }

    public function searchCourse()
    {
        $response = [];
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
            /** @var \WP_Query $query */
            $query = new \WP_Query( array(
                's'         => esc_attr( $q ),
                'post_type' => array('course'),
                'posts_per_page' => -1,
                'post_status' => ['publish'],
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ) );
            $courses = $query->posts;
            if ($courses) {
                $response['message'] = 'Success';
                $response['data'] = [];
                foreach ($courses as $course) {
                    $response['data'][] = [
                        'id'    => $course->ID,
                        'text'  => $course->post_title
                    ];
                }
            }
        }

        return $this->responseJson($response);
    }

    /**
     * Search an instructor
     */
    public function searchInstructor()
    {
        $response = [];
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
            $users = new \WP_User_Query( array(
                'search'         => '*'.esc_attr( $q ).'*',
                'role' => ['lema_instructor'],
                'search_columns' => array(
                    'user_login',
                    'user_nicename',
                    'user_email',
                    'user_url',
                ),
            ) );
            $users = $users->get_results();
            $data = [];
            if ($users) {
                $response['message'] = 'Success';
                $response['data'] = [];
                foreach ($users as $user) {
                    /** @var \WP_User $user */
                    $data[] = [
                        'id'    => $user->ID,
                        'text'  => $user->display_name
                    ];
                }
            }
            $data = apply_filters('lema_search_instructor', $data, $q);
            $response['data'] = $data;
        }

        return $this->responseJson($response);
    }

    /**
     * [sortData sort post]
     * @return [data] [data]
     */
    public function sortData(){
        if(isset($_POST['data']) && count($_POST['data'])){
            foreach($_POST['data'] as $key=>$data){
                wp_update_post(['ID' => $data, 'menu_order' => $key]);
            }

            return $this->responseJson(['data' => $_POST['data']]);
        }
    }

    /**
     * @return array
     */

    public static  function registerAction(){

        return [
            'ajax' => [
                'lema_search_user' => [self::getInstance(), 'searchUser'],
                'lema_search_course' => [self::getInstance(), 'searchCourse'],
                'lema_search_instructor' => [self::getInstance(), 'searchInstructor'],
                'lema_sort' => [self::getInstance(), 'sortData'],
            ],
        ];
    }

}