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
use lema\core\RuntimeException;
use lema\models\CourseModel;
use lema\models\Instructor;


class InstructorController extends UserController implements FrontControllerInterface {

	// Instructor - My Course
	public function myCoursePage() {

		return $this->render( 'my-course' );
	}

	// Instructor - Profile
	public function profilePage() {

		$instructor = null;
		if ( ! empty( $_GET['username'] ) ) {
			$instructor = new Instructor( $_GET['username'] );
		}
		if ( empty( $instructor ) ) {
			throw new RuntimeException( __( "Profile of instructor {$_GET['username']} could not be found.", 'lema' ) );
		}

		$search = isset( $_GET['search'] ) ? $_GET['search'] : '';

//		$courses       = $instructor->getCourses( true );
		$currentCourse = isset( $_GET['course_id'] ) ? $_GET['course_id'] : false;
		if ( ! empty( $currentCourse ) && ! is_object( $currentCourse ) ) {
			$currentCourse = CourseModel::findOne( $currentCourse );
		}
		if ( empty( $currentCourse ) && ! empty( $courses ) ) {
			$currentCourse = $courses[0];
		} else {
			$courses = [];
		}

		$rate = $instructor->getRating();
		$user = wp_get_current_user();

		return $this->render( 'profile', [
			'instructor'    => $instructor,
			'currentCourse' => $currentCourse,
			'courses'       => $courses,
			'tab'           => isset( $_GET['tab'] ) ? $_GET['tab'] : 'course',
			'search'        => $search,
			'rate'          => $rate,
			'user'          => $user
		] );
	}

	/**
	 * Edit user info
	 */
	public function editProfilePage() {
		$user       = wp_get_current_user();
		$instructor = new Instructor( $user );

		return $this->render( 'edit-profile', [ 'user' => $user, 'instructor' => $instructor, ] );
	}

	// Instructor - Purchase History
	public function purchaseHistoryPage() {
		// Style
		lema()->resourceManager->registerResource( new Style( [

		] ) );
		// Script
		lema()->resourceManager->registerResource( new Script( [
			'id'       => 'lema-main-js',
			'isInline' => false,
			'url'      => '/front/assets/js/main.js'
		] ) );

		return $this->render( 'purchase-history' );
	}

	// Instructor - Receipt
	public function receiptPage() {
		//Style
		lema()->resourceManager->registerResource( new Style( [
			'id'           => 'lema-instructor',
			'isInline'     => false,
			'url'          => '/front/assets/css/lema-instructor.css',
			'dependencies' => [ 'lema-style', 'font-awesome' ]
		] ) );

		return $this->render( 'receipt' );
	}

	/**
	 * Register all actions that controller want to hook
	 * @return mixed
	 */
	public static function registerAction() {
		return [
			'pages'  => [
				'front' => [
					'lema-instructor-my-course'                                     => 'myCoursePage',
					'lema-instructor/[(.*?) as username]/profile'                   => [
						'profilePage',
						[
							'title' => lema()->hook->registerFilter( 'lema_instructor_page_title', __( 'Lema Instructor Page', 'lema' ) )
						]
					],
					'lema-instructor-purchase-history'                              => 'purchaseHistoryPage',
					'lema-instructor-receipt'                                       => 'receiptPage',
					lema()->config->getUrlConfigs( 'lema_instructor_edit_profile' ) => [
						'editProfilePage',
						[ 'title' => __( 'Learn master - Edit Instructor Profile', 'lema' ) ]
					],
				]
			],
			'assets' => [
				'css' => [
					[
						'id'       => 'category',
						'isInline' => false,
						'url'      => '/front/assets/css/category.css'
					],
					[
						'id'           => 'lema-instructor',
						'isInline'     => false,
						'url'          => '/front/assets/css/lema-instructor.css',
						'dependencies' => [ 'lema-style', 'font-awesome' ]
					],
					[
						'id'       => 'lema-user-profile',
						'isInline' => false,
						'url'      => '/front/assets/css/lema-user-profile.css'
					]
				],
				'js'  => [
					[
						'id'       => 'lema-main-js',
						'isInline' => false,
						'url'      => '/front/assets/js/main.js'
					]
				]
			]
		];
	}
}