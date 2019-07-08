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
use lema\core\NotfoundException;
use lema\models\CourseModel;
use lema\models\LessonModel;
use lema\models\ChapterModel;
use lema\models\QuizModel;
use lema\models\OrderItemModel;
use lema\models\Student;

class LearningController extends FrontController implements FrontControllerInterface {
	public function addMetaTags() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	}

	//render learning page
	public function learningPage() {
		if ( isset( $_GET['course_name'] ) ) {
			$courseName = $_GET['course_name'];
			$obj        = get_page_by_path( $courseName, OBJECT, 'course' );
			$courseId   = '';
			if ( isset( $obj->ID ) ) {
				$courseId    = $obj->ID;
				$course = CourseModel::findOne( $courseId );
			} else {
				wp_redirect( home_url() );
			}
			if ( is_user_logged_in() ) {
				$student = new Student( get_current_user_id() );
				if ( ! $student->checkEnrolled( $courseId ) ) {
					wp_redirect( home_url() );
				}
			} else {
				wp_redirect( $course->getDashboardUrl() );
			}

			$course = CourseModel::findOne( $courseId );

			$chapters = $course->getChapters();

			global $wpdb;

			$user_id = get_current_user_id();

			$student = Student::getCurrentUser();
			if ( empty( $student ) && $student->checkEnrolled( $courseId ) ) {
				throw new NotfoundException( __( 'Not found order', 'lema' ) );
			}

			$list_chapters     = [];
			$learning_progress = get_user_meta( $user_id, "learning_progress_{$courseId}", true );
			$attr_active       = [
				'chapter_active' => null,
				'lesson_active'  => null,
			];

			foreach ( $chapters as $key => $chapter ) {

				$chapterModel = ChapterModel::findOne( $chapter );

				$list_chapters[ $key ]          = $chapter;
				$list_chapters[ $key ]->lessons = $chapterModel->getLessons();
				$list_chapters[ $key ]->quizs   = $chapterModel->getQuizs();

				// merge array lessons with quizs
				$list_chapters[ $key ]->lessons = array_merge( $list_chapters[ $key ]->lessons, $list_chapters[ $key ]->quizs );

				if ( $key == 0 && ! isset( $_GET['lesson'] ) ) {
					$_GET['lesson'] = $list_chapters[ $key ]->lessons[0]->ID;
				}

				if ( count( $list_chapters[ $key ]->lessons ) ) {
					$total_progress = 0;

					foreach ( $list_chapters[ $key ]->lessons as $keyLesson => $lesson ) {
						$modelName   = '\lema\models\\' . ucfirst( $lesson->post_type ) . 'Model';
						$lessonModel = $modelName::findOne( $lesson );


						if ( $lesson->post_type !== 'quiz' ) {
							$list_chapters[ $key ]->lessons[ $keyLesson ]->resourceFiles = $lessonModel->getResourceFiles();
						} else {
							$list_chapters[ $key ]->lessons[ $keyLesson ]->content_type = $lesson->post_type;
						}

						if ( isset( $learning_progress[ $lesson->ID ] ) && $learning_progress[ $lesson->ID ] == '1' ) {
							$total_progress ++;
						}

						if ( $lesson->ID == $_GET['lesson'] ) {
							$attr_active['chapter_active'] = $list_chapters[ $key ]->ID;
							$attr_active['lesson_active']  = $_GET['lesson'];
						}
					}
					$list_chapters[ $key ]->total_progress = $total_progress;

				}
			}

			$attr = [
				'course'            => $course,
				'chapters'          => $list_chapters,
				'learning_progress' => $learning_progress
			];

			$attr = array_merge( $attr, $attr_active );

			return $this->render( 'learning', $attr );
		}

	}

	public function getContent( $renderHtml = false ) {

		$html = 'Nothing !';

		if ( isset( $_POST['id'] ) ) {

			$post = get_post( $_POST['id'] );

			if ( $post ) {

				$chapter = ChapterModel::findOne( $post->post_parent );

				if ( $post->post_type == 'quiz' ) {
					$quizModel          = QuizModel::findOne( $post );
					$post->questions    = $quizModel->getQuestions();
					$post->content_type = $post->post_type;
				}

				$attr = [
					'post'    => $post,
					'chapter' => $chapter->post,
				];

				if ( empty( $post->content_type ) ) {
					$post->content_type = 'none';
				}

				$html = $this->render( $post->content_type, $attr, true );

				update_user_meta( get_current_user_id(), 'learning_lesson', $post->ID );

			}

		}
		if ( $renderHtml ) {

			return $html;

		} else {

			return $this->responseJson( [
				'code' => 200,
				'html' => $html,
			] );

		}
	}

	public function successLesson() {
		$message = '';

		if ( isset( $_POST['post_id'] ) && isset( $_POST['course_id'] ) && isset( $_POST['success'] ) ) {
			$user_id  = get_current_user_id();
			$courseId = trim( $_POST['course_id'] );
			$post_id  = trim( $_POST['post_id'] );
			if ( $user_id ) {
				$data_post = [ $post_id => $_POST['success'] ];

				$data_progress = get_user_meta( $user_id, "learning_progress_{$courseId}", true );

				if ( empty( $data_progress ) ) {
					$data_progress = $data_post;
					add_user_meta( $user_id, "learning_progress_{$courseId}", $data_progress, true );
				} else {
					$data_progress[ $post_id ] = $_POST['success'];
					update_user_meta( $user_id, "learning_progress_{$courseId}", $data_progress );
				}
				lema()->wp->do_action( 'lema_shortcode_course_flushcache', $courseId );
			} else {
				$message = 'Not found user !';
			}
		}

		return $this->responseJson( [
			'code'    => 200,
			'message' => $message,
		] );

	}

	public function submitQuiz() {
		$html    = 'ERROR';
		$user_id = get_current_user_id();
		if ( isset( $_POST['quiz'] ) && $user_id && isset( $_SESSION['take_time'] ) ) {

			$take_time = time() - $_SESSION['take_time'];

			$quizModel = QuizModel::findOne( $_POST['quiz'] );

			$list_questions = $quizModel->getQuestions();

			$correct = $empty = 0;

			foreach ( $list_questions as $question ) {
				if ( isset( $_POST['question'][ $question->ID ] ) ) {
					foreach ( $question->answer as $key => $answer ) {
						if ( ( $_POST['question'][ $question->ID ] == $key ) && ( $answer['correct'] == '1' ) ) {
							//correct
							$correct ++;
							break;
						}
					}
				} else {
					$empty ++;
				}
			}


			$history_submit = json_decode( get_user_meta( $user_id, 'submit_quiz' )[0], true );

			$data = [
				'total_questions' => count( $list_questions ),
				'correct'         => $correct,
				'empty'           => $empty,
				'success_percent' => round( $correct / count( $list_questions ) * 100, 1 ),
				'take_time'       => $take_time,
			];

			$update_history = [];

			if ( count( $history_submit ) ) {
				$update_history = $history_submit;
			}

			$update_history[ time() ] = $data;

			update_user_meta( $user_id, 'submit_quiz', json_encode( $update_history ) );

			$data['history_submit'] = $history_submit;
			$data['quiz']           = $quizModel;
			$data['take_time']      = $take_time;
			$html                   = $this->render( 'result', $data, true );
		}

		return $this->responseJson( [
			'html' => $html,
			'code' => 200,
		] );

	}

	public function removeHistory() {
		$message = '';
		$code    = 500;
		$user_id = get_current_user_id();

		if ( isset( $_POST['id'] ) && $user_id ) {

			$history_submit = json_decode( get_user_meta( $user_id, 'submit_quiz' )[0], true );

			unset( $history_submit[ $_POST['id'] ] );

			if ( empty( $history_submit ) ) {
				$history_submit = '';
			}

			if ( update_user_meta( $user_id, 'submit_quiz', json_encode( $history_submit ) ) ) {
				$code = 200;
			} else {
				$message = 'Can not update user meta ! ';
			}
		} else {
			$message = 'Can not find id or user invaild !';
		}

		return $this->responseJson( [
			'code'    => $code,
			'message' => $message,
		] );
	}

	public function setTime() {
		if ( ! session_id() ) {
			session_start();
		}
		$_SESSION['take_time'] = time();
	}

	public function actionCompleteCourse() {
		return $this->responseJson( [
			'code' => 200,
			'html' => $this->render( 'complete', [], true ),
		] );
	}

	public function completeCourse() {
		do_action( 'lema_complete_course' );

	}

	/**
	 * Register all actions that controller want to hook
	 * @return mixed
	 */
	public static function registerAction() {
		$learningUrl = lema()->config->getUrlConfigs( 'lema_learning' );

		return [
			'ajax'    => [
				'get_content'         => [ self::getInstance(), 'getContent' ],
				'success_lesson'      => [ self::getInstance(), 'successLesson' ],
				'submit_quiz'         => [ self::getInstance(), 'submitQuiz' ],
				'remove_history'      => [ self::getInstance(), 'removeHistory' ],
				'set_time_start_quiz' => [ self::getInstance(), 'setTime' ],
				'complete_course'     => [ self::getInstance(), 'completeCourse' ]
			],
			'actions' => [
				'wp_head'              => [ self::getInstance(), 'addMetaTags' ],
				'lema_complete_course' => [ self::getInstance(), 'actionCompleteCourse' ]
			],
			'pages'   => [
				'front' => [
					$learningUrl . '/lema-[(.*?) as course_name]' => [
						'learningPage',
						[
							'single' => true,
							//'db' => true,
							'title'  => __( 'Lema Learning Page', 'lema' )
						]
					]
				]
			],
			'assets'  => [
				'css' => [
					[
						'id'       => 'videojs-style',
						'isInline' => false,
						'url'      => '/front/assets/libs/videojs/video-js.min.css'
					],
					[
						'id'           => 'lema-learning',
						'isInline'     => false,
						'url'          => '/front/assets/css/course-learning.css',
						'dependencies' => [ 'lema-style', 'font-awesome' ],
					],
					[
						'id'       => 'lema-curriculum',
						'isInline' => false,
						'url'      => '/front/assets/css/curriculum-list.css'
					]
				],
				'js'  => [
					[
						'id'           => 'lema-main-js',
						'isInline'     => false,
						'url'          => '/front/assets/js/main.js',
						'dependencies' => [ 'jquery', 'lema' ]
					],
					[
						'id'           => 'lema-curriculum-js',
						'isInline'     => false,
						'url'          => '/front/assets/js/lms.js',
						'dependencies' => [ 'jquery', 'lema' ]
					],
					[
						'id'           => 'lema-learning-js',
						'isInline'     => false,
						'url'          => '/front/assets/js/course-learning.js',
						'dependencies' => [ 'jquery', 'lema', 'lema-curriculum-js' ]
					],
					[
						'id'           => 'videojs',
						'isInline'     => false,
						'url'          => '/front/assets/libs/videojs/video.js',
						'dependencies' => [ 'jquery', 'lema' ]
					]
				]
			]
		];
	}


}