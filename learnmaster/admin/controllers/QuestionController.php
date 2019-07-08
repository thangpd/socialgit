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

use lema\helpers\form\CustomElement;
use lema\models\CourseModel;
use lema\models\QuestionModel;
use lema\models\QuizModel;

class QuestionController extends AdminController implements ControllerInterface
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
            $model = QuestionModel::findOne($_POST['post_id']);
        } else {
            $model = new QuestionModel();
        }

        $postType = (empty($_POST['post_type'])) ? $model->getName() : $_POST['post_type'];
        
        $form  = new Form();

        $form->bind($model->getData());

        $lessons = [];
        if (isset($_POST['course_id'])) {
            /** @var CourseModel $course */
            $course = CourseModel::findOne($_POST['course_id']);
            if (!empty($course)) {
                $chapters = $course->getAllChapterModel();
                foreach ($chapters as $chapter) {
                    $_lessons = $chapter->getLessons();
                    foreach ($_lessons as $lesson) {
                        $lessons[$lesson->ID] = $lesson->post_title;
                    }
                }
            }
        }
        $data = [
            'model' => $model,
            'form'  => $form,
            'postParent' => $postParent,
            'postType'      => $postType,
            'lessons' => $lessons,
            'post_id'   => isset($_POST['post_id']) ? $_POST['post_id'] : false
        ];
        if ($ajax) {
            $form = $this->render('form',  $data,true);
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
    public function deleteQuestion()
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
    public function questionList($quiz_id = false, $ajax = true)
    {
        if (!$quiz_id) {
            $quiz_id = $_GET['quiz_id'];
        }
        if ($quiz_id) {
            /** @var QuizModel $quizModel */
            $quizModel = QuizModel::findOne($quiz_id);
            $html = $this->render('list', [
                'questions' => $quizModel->getQuestions(),
                'quizId' => $quiz_id
            ], true);
            print $html;
        }
        if ($ajax){
            exit;
        }

    }

    /**
     * @param CustomElement $control
     */
    public function renderAnswerOption($control)
    {
        if (!empty($control)) {
            return $this->render('_answer', [
                'control' => $control
            ], true);
        }
    }
    /**
     * @return string|void
     */
    public function renderForm()
    {
        return $this->form();
    }
    /**
     * Save post type quiz
     */
    public function saveQuestion()
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
     * Add question to quiz from selection list
     */
    public function selectQuestions()
    {
        if (isset($_POST['questions'])) {
            $questions = $_POST['questions'];
            foreach ($questions as $question) {
                $question = QuestionModel::findOne($question);
                if (!empty($question->post)) {
                    $post = clone $question->post;
                    unset($post->ID);
                    $post->post_parent = $_POST['post_parent'];
                    lema()->wp->wp_insert_post($post->to_array());
                }
            }
            die("Success");
        }
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
                'question_form'  => [self::getInstance(), 'form'],
            ],
            'ajax' => [
                'ajax_choose_question' => [self::getInstance(), 'selectQuestions'],
                'ajax_save_question' => [self::getInstance(), 'saveQuestion'],
                'ajax_question_list' => [self::getInstance(), 'questionList'],
                'ajax_question_form' => [self::getInstance(), 'ajaxForm'],
                'ajax_delete_question' => [self::getInstance(), 'deleteQuestion'],
            ],

        ];
    }
}