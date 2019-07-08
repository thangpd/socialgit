<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\front\controllers;


use lema\core\interfaces\ControllerInterface;

use lema\extensions\data\DataExtension;
use lema\front\controllers\FrontController;
use lema\core\components\Style;
use lema\core\components\Script;
use lema\models\CourseModel;
use lema\models\Student;


class CourseCatController extends FrontController implements ControllerInterface
{

    public function renderCategoryDetail()
    {
        global $term;
        if (!is_object($term)) {
            $term = get_queried_object();
        }
        return $this->render('index', [
            'term' => $term
        ]);
    }






    /**
     * Show details of course
     */
    public function courseDetail()
    {
        $this->render('detail');
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'actions' => [

            ],
            'assets' => [
                'css' => [

                ],
                'js' => [

                ]
            ]
        ];
    }

    public function addStyles() {
        $list = [
            'course-detail' => '/front/assets/css/course-detail.css',
            'layout' => '/front/assets/css/layout.css',
            'hours-course' => '/front/assets/css/hours-course.css',
            'best-selling' => '/front/assets/css/best-selling.css',
            'button-view-add' => '/front/assets/css/button-view-add.css',
            'button-category' => '/front/assets/css/button-category.css',
            'author' => '/front/assets/css/author.css',
            'date-begin-course' => '/front/assets/css/date-begin-course.css',
            'star-rating' => '/front/assets/css/star-rating.css',
            'discount' => '/front/assets/css/discount.css',
            'live-view' => '/front/assets/css/live-view.css',
            'level-save' => '/front/assets/css/level-save.css',
            'course-category-list' => '/front/assets/css/course-category-list.css',
            'course-category-block' => '/front/assets/css/course-category-block.css',
            'course-autocomplete-search' => '/front/assets/css/course-autocomplete-search.css',
            'course-cart-dropdown' => '/front/assets/css/course-cart-dropdown.css',
            'inoicons' => '/front/assets/fonts/ionicons-2.0.1/css/ionicons.min.css',

        ];

        foreach ($list as $id => $url) {
            lema()->resourceManager->registerResource(new Style([
               'id' => $id,
                'url' => $url,
                'dependencies' => []
            ]));
        }
    }

    public function addScripts() {
       $list = [
            'main-search' => '/front/assets/js/main.js',
            'course-detail-js' => '/front/assets/js/course-detail.js',

        ];

        foreach ($list as $id => $url) {
            lema()->resourceManager->registerResource(new Script([
                'id' => $id,
                'url' => $url,
                'dependencies' => []
            ]));
        }
    }

}