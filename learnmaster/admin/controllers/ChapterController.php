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
use lema\models\CourseModel;

class ChapterController extends AdminController implements ControllerInterface
{

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
            $model = ChapterModel::findOne($_POST['post_id']);
        } else {
            $model = new ChapterModel();
        }

        $postType = isset($_POST['post_type']) ?$_POST['post_type'] : 'chapter';
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
            $form = $this->render('form', $data , true);
            return $this->responseJson([
                'code' => 200,
                'data' => $form
            ]);
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
    public function deleteChapter()
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
    public function chapterList()
    {
        if (isset($_GET['course_id'])) {
            $courseModel =CourseModel::findOne($_GET['course_id']);
            $html = $this->render('list', [
                'chapters' => $courseModel->getChapters(),
                'current' => (isset($_GET['current']) && $_GET['current']) ?  $_GET['current'] : false
            ], true);
            print $html;
        }
        exit;
    }
    /**
     * Save post type chapter
     */
    public function saveChapter()
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
     * @param \WP_Post $chapter
     * @return mixed
     */
    public function renderDataList($chapter_id, $ajax = true)
    {
        if (!$chapter_id) {
            $chapter_id = $_GET['chapter_id'];
        }

        $listData = CourseModel::getPosts($chapter_id, ['lesson', 'quiz']);
        $current = (isset($_GET['current']) && $_GET['current']) ?  $_GET['current'] : false;
        if(count($listData)){
            foreach($listData as $key=>$data){
                print $this->render('item-'.$data->post_type, [
                    'i' => $key+1,
                    'data' => $data,
                    'chapterId' => $chapter_id,
                    'current' => $current,
                ], true);
            }
        }
        if ($ajax){
            exit;
        }
    }
    /**
     * @param \WP_Post $chapter
     * @return mixed
     */
    public function renderQuizList($chapter)
    {
        /** @var QuizController $quizCtrl */
        $quizCtrl = QuizController::getInstance();
        return $quizCtrl->quizList($chapter->ID, false);
    }
    /**
     * @param \WP_Post $chapter
     * @return mixed
     */
    public function renderLessonList($chapter)
    {
        /** @var LessonController $lessonCtrl */
        $lessonCtrl = LessonController::getInstance();
        return $lessonCtrl->lessonList($chapter->ID, false);
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
                'chapter_form'  => [self::getInstance(), 'form'],
            ],
            'ajax' => [
                'ajax_save_chapter' => [self::getInstance(), 'saveChapter'],
                'ajax_chapter_list' => [self::getInstance(), 'chapterList'],
                'ajax_chapter_form' => [self::getInstance(), 'ajaxForm'],
                'ajax_data_list' => [self::getInstance(), 'renderDataList'],
                'ajax_delete_chapter' => [self::getInstance(), 'deleteChapter'],
            ],

        ];
    }
}