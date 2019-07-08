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
use lema\models\QuizModel;

class QuizController extends AdminController implements ControllerInterface
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
            $model = QuizModel::findOne($_POST['post_id']);
        } else {
            $model = new QuizModel();
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
     * Delete 1 chapter
     */
    public function deleteQuiz()
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
    public function quizList($chapter_id = false, $ajax = true)
    {
        if (!$chapter_id) {
            $chapter_id = $_GET['chapter_id'];
        }
        if ($chapter_id) {
            /** @var ChapterModel $chapterModel */
            $chapterModel = ChapterModel::findOne($chapter_id);
            $html = $this->render('list', [
                'questions' => $chapterModel->getQuizs(),
                'chapterId' => $chapter_id,
                'current' => (isset($_GET['current']) && $_GET['current']) ?  $_GET['current'] : false
            ], true);
            print $html;
        }
        if ($ajax){
            exit;
        }

    }

    public function sortQuiz()
    {
        if (isset($_POST['order'])) {
            $order = $_POST['order'];
        }
    }
    /**
     * Save post type quiz
     */
    public function saveQuiz()
    {
        lema()->helpers->general->clearArray($_POST);
        $postId = lema()->wp->wp_write_post();
        if ($postId) {
            $form = $this->render('form', [
                'message' => 'Saved successfully',
            ], true);
            print $form;
        } else {
            $this->form();
        }
        exit;
    }

    /**
     * @param \WP_Post $quiz
     * @return mixed
     */
    public function renderQuestionList($quiz)
    {
        $model = QuestionController::getInstance();
        return $model->questionList($quiz->ID, false);
    }
    /**
     * @return string|void
     */
    public function renderForm()
    {
        return $this->form();
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
                'quiz_form'  => [self::getInstance(), 'form'],
            ],
            'ajax' => [
                'ajax_save_quiz' => [self::getInstance(), 'saveQuiz'],
                'ajax_quiz_list' => [self::getInstance(), 'quizList'],
                'ajax_quiz_form' => [self::getInstance(), 'ajaxForm'],
                'ajax_delete_quiz' => [self::getInstance(), 'deleteQuiz'],
                'meta-box-order' => [self::getInstance(), 'sortQuiz'],
            ],

        ];
    }
}