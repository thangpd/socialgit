<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\admin\controllers;


use lema\core\components\Form;
use lema\core\interfaces\ControllerInterface;

use lema\models\ChapterModel;
use lema\models\LessonModel;

class LessonController extends AdminController implements ControllerInterface
{

    /**
     * @return string
     */
    public function renderLessonType($control)
    {
        return $this->render('_type', ['control' => $control]);
    }

    /**
     * @return string
     */
    public function renderLessonVideo($control)
    {
        return $this->render('_video',  ['control' => $control]);
    }

    /**
     * @param $control
     * @return string
     */
    public function renderLessonDownloadable($control)
    {
        return $this->render('_downloadable',  ['control' => $control]);
    }
    /**
     * @param $control
     * @return string
     */
    public function renderLessonExternal($control)
    {
        return $this->render('_external',  ['control' => $control]);
    }
    /**
     * @param $control
     * @return string
     */
    public function renderLessonCode($control)
    {
        return $this->render('_code',  ['control' => $control]);
    }

    /**
     * @param $lesson
     * @return bool|\lema\core\interfaces\ModelInterface|\lema\core\Model
     */
    public function getLessonModel($lesson)
    {
       return LessonModel::findOne($lesson);
    }

    /**
     * @return string
     */
    public function renderLessonAudio($control)
    {
        return $this->render('_audio',  ['control' => $control]);
    }
    /**
     * Show chapter form
     */
    public function form($ajax = false)
    {
        $postParent = '';
        if (isset($_POST['post_parent'])) {
            $postParent = $_POST['post_parent'];

        }
        if (isset($_POST['post_id'])) {
            $model = LessonModel::findOne($_POST['post_id']);
        } else {
            $model = new LessonModel();
        }

        $postType = (empty($_POST['post_type'])) ? $model->getName() : $_POST['post_type'];
        
        $form  = new Form();

        $form->bind($model->getData());
        $data = [
            'model' => $model,
            'form'  => $form,
            'postParent' => $postParent,
            'postType'      => $postType,
            'post_id'   => isset($_POST['post_id']) ? $_POST['post_id'] : false
        ];
        if ($ajax) {
            $form = $this->render('form', $data, true);
            return $this->responseJson([
                'code' => 200,
                'data' => $form
            ]);
            exit;
        }
        return $this->render('form', $data);

    }
    /**
     * Show chapter form
     */
    public function ajaxForm()
    {
        return $this->form(true);
    }

    /**
     * @return string|void
     */
    public function renderForm()
    {
        return $this->form();
    }
    /**
     * Delete 1 chapter
     */
    public function deleteLesson()
    {
        if(lema()->wp->wp_delete_post($_POST['post_id'],true)){
            $this->responseJson([
                'code' => 200,
                'message' => 'Delete success',
            ]);
        }
    }


    /**
     * Render partial chapter list
     */
    public function lessonList($chapter_id = false, $ajax = true)
    {
        if (!$chapter_id) {
            $chapter_id = $_GET['chapter_id'];
        }
        if ($chapter_id) {
            /** @var ChapterModel $chapterModel */
            $chapterModel = ChapterModel::findOne($chapter_id);
            $html = $this->render('list', [
                'lessons' => $chapterModel->getLessons(),
                'chapterId' => $chapter_id,
                'current' => (isset($_GET['current']) && $_GET['current']) ?  $_GET['current'] : false
            ], true);
            print $html;
        }
        if ($ajax){
            exit;
        }

    }


    /**
     * Save post type lesson
     */
    public function saveLesson()
    {
        lema()->helpers->general->clearArray($_POST);
        $postId = lema()->wp->wp_write_post();
        if ($postId) {
            return $this->responseJson([
                'message' => __('Your changes saved successfully', 'lema')
            ]);
        } else {
            $this->form();
        }
        exit;
    }

    /**
     * @param \WP_Post $lesson
     * @return mixed
     */
    public function renderQuestionList($lesson)
    {
        $model = QuestionController::getInstance();
        return $model->questionList($lesson->ID, false);
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        add_action('admin_footer-post-new.php',  [self::getInstance(), 'renderForm']);
        add_action('admin_footer-post.php',  [self::getInstance(), 'renderForm']);
        return [
            'actions' => [
                'lesson_form'  => [self::getInstance(), 'form'],
            ],
            'ajax' => [
                'ajax_save_lesson' => [self::getInstance(), 'saveLesson'],
                'ajax_lesson_list' => [self::getInstance(), 'lessonList'],
                'ajax_lesson_form' => [self::getInstance(), 'ajaxForm'],
                'ajax_delete_lesson' => [self::getInstance(), 'deleteLesson'],
            ],

        ];
    }
}