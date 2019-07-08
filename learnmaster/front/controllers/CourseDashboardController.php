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
use lema\models\CourseLanguageModel;
use lema\models\CourseLevelModel;
use lema\models\CourseModel;
use lema\models\Student;

class CourseDashboardController extends FrontController implements FrontControllerInterface {
	private $listLanguage = [];
	private $listLevel = [];

	public function __construct( array $config = [] ) {
		parent::__construct( $config );
		$languages          = ( new CourseLanguageModel() )->getOptions();
		$this->listLanguage = lema()->hook->registerFilter( 'lema_languages_list', $languages );
		$levels             = ( new CourseLevelModel() )->getOptions();
		$this->listLevel    = lema()->hook->registerFilter( 'lema_levels_list', $levels );
	}

	//render learning page
	public function courseDashboardPage() {

		if ( isset( $_GET['course_name'] ) ) {
			$courseName = $_GET['course_name'];
			$obj        = get_page_by_path( $courseName, OBJECT, 'course' );
			$courseId   = '';
			if ( isset( $obj->ID ) ) {
				$courseId = $obj->ID;
			}

			//check student enrolled course
			/** @var Student $student */
			$student = Student::getCurrentUser();
			if ( ! empty( $student ) && $student->checkEnrolled( $courseId ) ) {

				/** @var CourseModel $courseModel */
				$courseModel     = \lema\models\CourseModel::findOne( $courseId );
				$dataProcessItem = get_user_meta( $student->user->ID, "learning_progress_{$courseId}", true );
				if ( ! empty( $dataProcessItem ) ) {
					$courseButtonText = 'continue to lesson';
				} else {
					$courseButtonText = 'enroll now';
				}
				//courseItemSC

				$attr_lema_coursecard_description = "attr_lema_coursecard_description=\"limit_text=50\"";
				$attr_lema_coursecard_image       = "attr_lema_coursecard_image=\"has_hours=0 has_label=0 has_view_button=0 has_add_button=0\"";
				// $linkCourse = get_home_url().'/lema-learning/lema-'.$courseName.'/'.$courseId.'/';
				$linkCourse                  = $courseModel->getUrlFirstLesson();
				$attr_lema_coursecard_button = "attr_lema_coursecard_button=\"text_button='{$courseButtonText}' class_button='button continue-lesson lema-btn lema-btn-primary' type='link' link='{$linkCourse}'\"";
				$attr_lema_rating            = "attr_lema_rating=\"style=custom text_rating='RATE THIS COURSE' has_rating=1\"";
				$courseItemSC                = "[lema_course post_id={$courseId} 
                                    layout=\"layout-2\" 
                                    {$attr_lema_coursecard_description} 
                                    lema_coursecard_category=0 lema_coursecard_price=0 lema_coursecard_bookmark=0 
                                    {$attr_lema_coursecard_image}
                                    {$attr_lema_coursecard_button}
                                    {$attr_lema_rating}]";

				$courseDescription = "[lema_coursecard_description limit_text=-1 post_id={$courseId}]";
				$courseInstructor  = "[lema_coursecard_instructor show_icon_avatar='0' post_id={$courseId} layout=\"\"]";


				return $this->render( 'index', [
					'courseId'          => $courseId,
					'courseName'        => $courseName,
					'courseItemSC'      => $courseItemSC,
					'courseInstructor'  => $courseInstructor,
					'courseModel'       => $courseModel,
					'listLevel'         => $this->listLevel,
					'listLanguage'      => $this->listLanguage,
					'courseDescription' => $courseDescription,
					'linkCourse'        => $linkCourse,
					'courseButtonText'  => $courseButtonText
				] );
			} else {
				if ( is_user_logged_in() ) {
					wp_redirect( get_permalink( $courseId ) );
				} else {
					$message = __( "'You\'re not logged in, you\'re being redirected to our Homepage.<br> Thank you very much for your patience!'", "lema" );
					wp_add_inline_script( 'lema-dashboard-js', '<script>jQuery(function($){
				    $(document).ready(function(e){
			                var modal=$("#login-modal");
			                modal.on("click", function (e) {
			                		if(e.target !== this){
			                			return;		                		    
			                		}else{
			                		lema.ui.loading.show(' . $message . ');
				        			window.location.assign( lemaConfig.baseUrl );
				        			}
				    			})
						    $(document).keyup(function(e) {
									     if (e.key === "Escape") { // escape key maps to keycode `27`
									     	lema.ui.loading.show(' . $message . ');
									        window.location.assign( lemaConfig.baseUrl ) ;
									    }
								});
							$(".slz-header-wrapper .slz-wrapper-pc .btn-login").trigger("click");
							
						})
					});
	
				</script>' );
				}

				return $this->render( 'unqualifield', [ 'courseId' => $courseId ] );

			}

		}
	}

	/**
	 * Register all actions that controller want to hook
	 * @return mixed
	 */
	public static function registerAction() {
		$dashboardUrl = lema()->config->getUrlConfigs( 'lema_dashboard' );

		return [
			'pages'  => [
				'front' => [
					$dashboardUrl . '/lema-[(.*?) as course_name]' => [
						'courseDashboardPage',
						[
							'db'    => true,
							'title' => 'Course DashBoard'
						]
					]
				]
			],
			'assets' => [
				'css' => [
					[
						'id'           => 'lema-coursedashboard',
						'isInline'     => false,
						'url'          => '/front/assets/css/course-dashboard.css',
						'dependencies' => [ 'lema-style', 'lema-shortcode-style', 'font-awesome' ],
					],
					[
						'id'       => 'lema-curriculum',
						'isInline' => false,
						'url'      => '/front/assets/css/curriculum-list.css'
					]
				],
				'js'  => [
					[
						'id'       => 'lema-main-js',
						'isInline' => false,
						'url'      => '/front/assets/js/main.js',
					],
					[
						'id'       => 'lema-curriculum-js',
						'isInline' => false,
						'url'      => '/front/assets/js/lms.js'
					],
					[
						'id'       => 'lema-dashboard-js',
						'isInline' => false,
						'url'      => '/front/assets/js/course-dashboard.js',
					],
					[
						'id'       => 'lema-dashboard-search-js',
						'isInline' => false,
						'url'      => '/front/assets/js/dashboard-search.js',
					],
					[
						'id'           => 'sticky',
						'url'          => '/front/assets/libs/sticky/jquery.sticky.js',
						'dependencies' => []
					]
				]
			]
		];
	}
}