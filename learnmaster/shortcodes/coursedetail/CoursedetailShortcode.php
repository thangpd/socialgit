<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/17/17.
 */


namespace lema\shortcodes\coursedetail;


use lema\core\Shortcode;
use lema\models\ChapterModel;
use lema\models\CourseModel;

class CoursedetailShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_course_detail';

	public $contentView = 'coursedetail';


	/**
	 * Get static resources of shortcode
	 *
	 * @return [];
	 *
	 */
	public function getStatic() {
		return [
			[
				'type'         => 'script',
				'id'           => 'coursedetail-script',
				'isInline'     => false,
				'url'          => 'assets/scripts/lema-course-detail.js',
				'dependencies' => [ 'lema', 'lema.shortcode' ]
			],
			[
				'type'         => 'style',
				'id'           => 'coursedetail-style',
				'isInline'     => false,
				'url'          => 'assets/styles/lema-course-detail.css',
				'dependencies' => [ 'lema-shortcode-style' ]
			]
		];
	}

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		return self::SHORTCODE_ID;
	}

	/**
	 * Array of default value of all shortcode options
	 * @return array
	 */
	public function getAttributes() {
		return [
			'style'    => 'style-1',
			'courseID' => '',
		];
	}

	/**
	 * @return boolean
	 */
	public function isEnabled() {
		// TODO: Implement isEnabled() method.
	}

	public function getDataCourseLearningPage( CourseModel $course ) {
		$chapters      = $course->getChapters();
		$list_chapters = [];
		$attr_active   = [
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

					if ( $lesson->ID == $_GET['lesson'] ) {
						$attr_active['chapter_active'] = $list_chapters[ $key ]->ID;
						$attr_active['lesson_active']  = $_GET['lesson'];
					}
				}
				$list_chapters[ $key ]->total_progress = $total_progress;

			}
		}

		$attr = [
			'chapters' => $list_chapters,
		];

		return $attr;
	}

	public function render_course_content( $courseID ) {

		$course = CourseModel::findOne( $courseID );
		if ( $course instanceof CourseModel ) {
			$attr = $this->getDataCourseLearningPage( $course );


			return $this->render( 'course-content', array(
				'dataCourseContent' => $attr,
				'courseModel'       => $course
			) );
		} else {
			return '';
		}


	}


	/**
	 * Get full content of this shortcode
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {

		return $this->render( $this->contentView, array( 'data' => $data ), true, $key );

	}


	/**
	 * get list action register
	 * @return array
	 */
	public function actions() {
		return [
//			'ajax' => [
//				'ajax_update_cart' => [ $this, 'updateCart' ],
//			]
		];
	}


}