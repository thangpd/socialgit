<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\front\controllers;


use lema\core\components\Style;
use lema\core\components\Script;
use lema\core\interfaces\FrontControllerInterface;


class QuizController extends FrontController implements FrontControllerInterface
{

    //render learning page
    public function quizPage()
    {

        return $this->render('index');
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'pages' => [
                'front' => [
                    'lema-quiz' => ['quizPage', [
                        'single' => true
                    ]]
                ]
            ],
            'assets' => [
                'css' => [
                    [
                        'id' => 'lema-learning',
                        'isInline' => false,
                        'url'   => '/front/assets/css/course-learning.css',
                        'dependencies'  => ['lema-style','font-awesome'],
                    ],
                    [
                        'id' => 'lema-curriculum',
                        'isInline' => false,
                        'url'   => '/front/assets/css/curriculum-list.css'
                    ]

                ],
                'js' => [
                    [
                        'id' => 'lema-main-js',
                        'isInline' => false,
                        'url'   => '/front/assets/js/main.js',
                        'dependencies' => ['jquery']
                    ],
                    [
                        'id' => 'lema-curriculum-js',
                        'isInline' => false,
                        'url'   => '/front/assets/js/lms.js'
                    ]
                ]
            ]
        ];
    }
}