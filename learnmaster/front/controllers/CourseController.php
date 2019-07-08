<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\front\controllers;


use lema\core\interfaces\CacheableControllerInterface;
use lema\core\interfaces\ControllerInterface;

use lema\front\controllers\FrontController;
use lema\core\components\Style;
use lema\core\components\Script;
use lema\models\CourseLanguageModel;
use lema\models\CourseLevelModel;
use lema\models\CourseModel;
use lema\models\Student;


class CourseController extends FrontController implements ControllerInterface
{
    private $listLanguage = [];
    private $listLevel = [];
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $languages =  (new CourseLanguageModel())->getOptions();
        $this->listLanguage   = lema()->hook->registerFilter('lema_languages_list', $languages);
        $levels = (new CourseLevelModel())->getOptions();
        $this->listLevel      = lema()->hook->registerFilter('lema_levels_list', $levels);
    }
    public function setup()
    {
        global $post;
        if (empty($post) || $post->post_type != 'course') return;
        lema()->hook->listenFilter('single_template', [$this, 'getCourseTemplate']);
    }

    /**
     * filter get course template
     * @param  string 
     * @return string 
     */
    function getCourseTemplate($single_template) {
	     global $post;
         if ($post->post_type == 'course') {
            $controller_name = $this->getControllerName();
            $single_template = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views/'.$controller_name.'Single.php';
	     }
	     return $single_template;
	}





    /**
     * Show details of course
     */
    public function courseDetail()
    {
        if ( have_posts() ) {
            while (have_posts()) {
                the_post();
                global $post;
                /** @var CourseModel $model */
                $model = CourseModel::findOne($post->ID);

                $courseAchievement = $model->course_achievement;
                if (!is_array($courseAchievement)) {
                    $courseAchievement = json_decode($courseAchievement);
                }

                $coursePrerequisites = $model->course_prerequisites;
                if (is_string($coursePrerequisites)) {
                    $coursePrerequisites = json_decode($coursePrerequisites);
                }

                $courseTarget = $model->course_target;
                if (is_string($courseTarget)) {
                    $courseTarget = json_decode($courseTarget);
                }

                $postModified = $post->post_modified;
                $dateFormat = get_option('date_format');
                $postModified = date($dateFormat, strtotime($postModified));
                $category = $model->getCategory('name');

                $dataAuthor = get_userdata($post->post_author);
                $urlVideo = $model->getVideoUrl();

                $listCurriculum = $model->getCurriculum();
                return $this->render('detail' , [
                    'listLanguage'          => $this->listLanguage,
                    'listLevel'             => $this->listLevel,
                    'courseAchievement'     => $courseAchievement,
                    'coursePrerequisites'   => $coursePrerequisites,
                    'courseTarget'          => $courseTarget,
                    'postModified'          => $postModified,
                    'category'              => $category,
                    'dataAuthor'            => $dataAuthor,
                    'urlVideo'              => $urlVideo,
                    'listCurriculum'        => $listCurriculum,
                    'model'                 => $model,
                    'post'                  => $post
                ]);
            }
        }
        wp_reset_query();

    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'actions' => [
                'template_redirect'  =>  [self::getInstance(), 'setup'],
            ],
            'assets' => [
                'css' => [
                    [
                        'id' => 'lema-course-style',
                        'url' => '/front/assets/css/style.css',
                        'dependencies' => []
                    ],
                    [
                        'id' => 'ionicons',
                        'url' => '/front/assets/fonts/ionicons-2.0.1/css/ionicons.min.css',
                        'dependencies' => []
                    ],
                    [
                        'id' => 'lema-course-detail',
                        'url' => '/front/assets/css/course-detail.css',
                        'dependencies' => []
                    ]
                ],
                'js' => [
                    [
                        'id' => 'lema-main-js',
                        'url' => '/front/assets/js/main.js',
                        'dependencies' => []
                    ],
                    [
                        'id' => 'lema-course-detail-script',
                        'url' => '/front/assets/js/course-detail.js',
                        'dependencies' => []
                    ]
                ]
            ]
        ];
    }

}