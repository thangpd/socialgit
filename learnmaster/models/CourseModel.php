<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 * @since 1.0
 *
 */

namespace lema\models;

use lema\admin\controllers\CourseController;
use lema\core\interfaces\ModelInterface;

use lema\core\Model;
use mysql_xdevapi\Exception;

class CourseModel extends Model implements ModelInterface {
	const PREFIX_USER = 'u_';
	const COURSE_SLUG = 'course';
	const COURSE_REGULAR_PRICE = 'course_price';
	const COURSE_SALE_PRICE = 'course_sale_price';

	public $type = 'default';

	public function init() {
		parent::init(); // TODO: Change the autogenerated stub
		do_action( 'lema_course_init' );

	}

	/**
	 * Get object/table name
	 * @return string
	 */
	public function getName() {
		return self::COURSE_SLUG;
	}

	/**
	 * Save object properties to database
	 * @return boolean
	 */
	public function save() {
		// TODO: Implement save() method.
	}


	/**
	 * Delete a object by primary key
	 * @return boolean
	 */
	public function delete() {
		// TODO: Implement delete() method.
	}

	/**
	 * @param $postId
	 *
	 * @return mixed
	 */
	public function afterSave( $postId, $post = null, $update = false ) {
		parent::afterSave( $postId );
		if ( $postId ) {
			lema()->session->currentCourse = $postId;
			lema()->wp->do_action( 'after_course_save', $postId );
		}

		$instructors = isset( $_POST['instructor'] ) ? ( $_POST['instructor'] ) : [];
		if ( ! is_array( $instructors ) ) {
			$instructors = [];
		}

		$_instructors = $this->getInstructors( $postId );
		foreach ( $_instructors as $id => $name ) {
			if ( ! in_array( $id, $instructors ) ) {
				delete_user_meta( $id, Instructor::instructorMetaKey(), $postId );
			}
		}
		foreach ( $instructors as $instructor ) {
			if ( ! array_key_exists( $instructor, $_instructors ) ) {
				add_user_meta( $instructor, Instructor::instructorMetaKey(), $postId );

				// send mail for instructor if publish/update
				$instructor = new Instructor( $instructor );
				if ( isset( $_POST['post_status'] ) && $_POST['post_status'] == 'publish' ) {
					$data = [
						'instructor' => $instructor,
						'course'     => $this->loadPost( $postId )
					];
					do_action(
						\lema\core\components\Hook::LEMA_SEND_MAIL,
						\lema\core\components\Mailer::MAIL_INSTRUCTOR_COURSE_PUBLISH,
						__( 'The course is accepted for publication', 'lema' ),
						$instructor->user->user_email,
						$data
					);
				}

			}
		}

		do_action( 'lema_shortcode_course_flushcache', $postId );
	}


	/**
	 * @param $postId
	 */
	public function beforeDelete( $postId ) {
		global $post_type;
		if ( $post_type == 'course' ) {
			$_instructors = $this->getInstructors( $postId );
			foreach ( $_instructors as $id => $name ) {
				delete_user_meta( $id, Instructor::instructorMetaKey(), $postId );
			}
			do_action( 'lema_shortcode_course_flushcache', $postId );
		}
	}

	/**
	 * Get instructors list who assigned to this course
	 *
	 * @param int $postId
	 *
	 * @return array
	 */
	public function getInstructors( $postId = false, $object = '', $limit = - 1 ) {
		if ( ! $postId ) {
			return [];
		} else {

			$user_query = new \WP_User_Query(
				array(
					'meta_key'   => Instructor::instructorMetaKey(),
					'meta_value' => $postId ? $postId : $this->getId()
				)
			);

			$limit       = intval( $limit );
			$instructors = [];
			$users       = $user_query->get_results();
			foreach ( $users as $i => $user ) {
				/** @var \WP_User $user */
				if ( $object !== '' ) {
					$instructors[ $user->ID ] = $user;
				} else {
					$instructors[ $user->ID ] = $user->display_name;
				}

				if ( $limit > 0 && $i == $limit - 1 ) {
					break;
				}
			}

			return $instructors;
		}
	}


	/**
	 * Get object attributes
	 * @return array
	 */
	public function getAttributes() {

		$courseLanguage = $this->getValueMeta( 'course_language' );
		if ( empty( $courseLanguage ) ) {
			$courseLanguage = lema()->config->lema_course_language;
		}
		$courseLevel = $this->getValueMeta( 'course_level' );
		if ( empty( $courseLevel ) ) {
			$courseLevel = lema()->config->lema_course_level;
		}
		$pre = 'lema_course';

		// TODO: Implement getAttributes() method.
		return apply_filters( 'lema_course_attrbutes', array(
			'course_subtitle'    => [
				'label'    => __( 'Subtitle', 'lema' ),
				'validate' => [ 'text', [ 'maxLength' => 120, 'required' => true ] ],
				'form'     => [
					'type'  => 'text',
					'class' => 'la_form_control',
					'name'  => 'course_subtitle',
					'label' => __( 'Subtitle', 'lema' ),
				],
				'render'   => '<div class="list-item">{value}</div>'
			],
			'course_description' => [
				'label' => __( 'Course description' ),
				'form'  => [
					'type'  => 'editor',
					'name'  => 'course_description',
					'rows'  => 4,
					'cols'  => 10,
					'label' => __( 'Course description' ),
				]
			],
			'course_language'    => [
				'label'    => __( 'Languages', 'lema' ),
				'validate' => [ 'text', [] ],
				'form'     => [
					'type'       => 'select',
					'options'    => ( new CourseLanguageModel() )->getOptions(),
					'class'      => 'la_form_control la-select2',
					'name'       => 'course_language',
					'label'      => __( 'Languages', 'lema' ),
					'attributes' => [
						'data-select-2' => true,
					],
					'template'   => '<div class="col-50"><div class="la-form-group"><label>{label}</label>{input}</div></div>',
					'selected'   => $courseLanguage
				],
				'tab'      => 'general'

			],
			'course_level'       => [
				'label'    => __( 'Level', 'lema' ),
				'validate' => [ 'text', [] ],
				'form'     => [
					'type'       => 'select',
					'options'    => ( new CourseLevelModel() )->getOptions(),
					'class'      => 'la_form_control la-select2',
					'name'       => 'course_level',
					'label'      => __( 'Level', 'lema' ),
					'attributes' => [
						'data-select-2' => true
					],
					'template'   => '<div class="col-50"><div class="la-form-group"><label>{label}</label>{input}</div></div><div class="clear"></div> ',
					'selected'   => $courseLevel
				],
				'tab'      => 'general'
			],
			'course_price'       => [
				'label'    => 'Price',
				'validate' => [ 'number', [ 'min' => 0 ] ],
				'form'     => [
					'type'     => 'text',
					'class'    => 'la_form_control',
					'name'     => 'course_price',
					'label'    => 'Price',
					'template' => '<div class="col-50"><div class="la-form-group"><label>{label}</label>{input}</div></div>',
				],
				'tab'      => 'general'
			],
			'course_sale_price'  => [
				'label'    => 'Sale price',
				'validate' => [ 'number', [ 'min' => 0 ] ],
				'form'     => [
					'type'     => 'text',
					'class'    => 'la_form_control',
					'name'     => 'course_sale_price',
					'label'    => 'Sale Price',
					'template' => '<div class="col-50"><div class="la-form-group"><label>{label}</label>{input}</div></div>',
				],
				'tab'      => 'general'
			],

			'course_length' => [
				'label'    => 'Length',
				'validate' => [ 'text', [] ],
				'form'     => [
					'type'        => 'text',
					'class'       => 'la_form_control',
					'name'        => 'course_length',
					'label'       => 'Length',
					'placeholder' => __( 'Example: 4-6 weeks', 'lema' ),
					'template'    => '<div class="col-50"><div class="la-form-group"><label>{label}</label>{input}</div></div>'
				],
				'tab'      => 'general'
			],
			'course_effort' => [
				'label'    => 'Effort',
				'validate' => [ 'text', [] ],
				'form'     => [
					'type'        => 'text',
					'class'       => 'la_form_control',
					'name'        => 'course_effort',
					'label'       => 'Effort',
					'placeholder' => __( 'Example: 6-10 hours per week', 'lema' ),
					'template'    => '<div class="col-50"><div class="la-form-group"><label>{label}</label>{input}</div></div><div class="clear"></div> ',
				],
				'tab'      => 'general'
			],

			'promotional_video'                             => [
				'label' => __( 'Promotional Video', 'lema' ),
				'form'  => [
					'label'    => __( 'Promotional Video', 'lema' ),
					'type'     => 'custom',
					'renderer' => [ CourseController::getInstance(), 'renderVideoField' ],
					'class'    => 'upload_custom_Video la_accodion',
					'name'     => 'promotional_video',
				],
				'tab'   => 'general'
			],
			'course_best'                                   => [
				'label' => 'Best Sell',
				'form'  => [
					'type'     => 'checkbox',
					'class'    => 'la_form_control',
					'name'     => 'course_best',
					'label'    => 'Best Sell',
					'value'    => true,
					'template' => '<div class="col-50"><div class="la-form-group"><label>{label}</label>{input}</div></div><div class="clear"></div> ',
				],
				'tab'   => 'general'
			],
			$pre . '_sentence_repeator'                     => '',
			$pre . '_class_date_area'                       => '',
			$pre . '_class_open_area'                       => '',
			$pre . '_salary_area'                           => '',
			$pre . '_based_on_area'                         => '',
			$pre . '_testimonial_area'                      => '',
			$pre . '_about_area'                            => '',
			$pre . '_created_by_conditinal'                 => '',
			$pre . '_project_area'                          => '',
			$pre . '_creator_by_conditinal'                 => '',
			$pre . '_faq_repeator'                          => '',
			$pre . '_howitwork_conditinal'                  => '',
			$pre . '_content_list_meta_repeator'            => '',
			$pre . '_person_support_list_meta_repeator'     => '',
			$pre . '_additional_feature_list_meta_repeator' => '',
		) );
	}

	/**
	 * @param $id
	 * @param \WP_Post $post
	 */
	public function afterPublishCourse( $id, $post ) {
		// send mail for instructor
		if ( $post->post_type == self::getName() ) {
			$this->loadPost( $post );
			$sent = get_post_meta( $this->post->ID, 'lema_course_publishmail_sent', true );
			if ( $sent !== '1' ) {
				$instructors = $this->getInstructors( $post->ID );
				foreach ( $instructors as $user => $name ) {
					$instructor = new Instructor( $user );
					$data       = [
						'instructor' => $instructor,
						'course'     => $this
					];
					do_action(
						\lema\core\components\Hook::LEMA_SEND_MAIL,
						\lema\core\components\Mailer::MAIL_INSTRUCTOR_COURSE_PUBLISH,
						__( 'The course is accepted for publication', 'lema' ),
						$instructor->user->user_email,
						$data

					);
				}
				add_post_meta( $this->post->ID, 'lema_course_publishmail_sent', true );
			}

		}
	}

	/**
	 * @return mixed
	 */
	public static function getPosttypeConfig() {
		$labels = array(
			'name'                  => _x( 'Course', 'post type general name', 'lema' ),
			'singular_name'         => _x( 'Course', 'post type singular name', 'lema' ),
			'menu_name'             => _x( 'Course', 'admin menu', 'lema' ),
			'name_admin_bar'        => _x( 'Course', 'add new on admin bar', 'lema' ),
			'add_new'               => _x( 'Add Course', 'Course', 'lema' ),
			'add_new_item'          => __( 'Add New Course', 'lema' ),
			'new_item'              => __( 'New Course', 'lema' ),
			'edit_item'             => __( 'Edit Course', 'lema' ),
			'view_item'             => __( 'View Course', 'lema' ),
			'all_items'             => __( 'Courses', 'lema' ),
			'search_items'          => __( 'Search Course', 'lema' ),
			'parent_item_colon'     => __( 'Parent Course:', 'lema' ),
			'not_found'             => __( 'No Course found.', 'lema' ),
			'not_found_in_trash'    => __( 'No Course found in Trash.', 'lema' ),
			'featured_image'        => _x( 'Course Image', '', 'lema' ),
			'set_featured_image'    => _x( 'Set course image', '', 'lema' ),
			'remove_featured_image' => _x( 'Remove course image', '', 'lema' ),
			'use_featured_image'    => _x( 'Use course image', '', 'lema' ),
		);

		$args = array(
			'labels'          => $labels,
			'description'     => __( 'Description.', 'lema' ),
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => false,
			'capability_type' => 'post',
			'has_archive'     => false,
			'hierarchical'    => false,
			'supports'        => apply_filters( 'lema_course_supports', array( 'title', 'thumbnail' ) ),
		);

		// add_action('publish_course', [self::getInstance(), 'afterPublishCourse'], 10, 2);
		// add_action('update_course', [self::getInstance(), 'afterPublishCourse'], 10, 2);

		return [
			'post' => [
				'name' => 'course',
				'args' => $args
			]
		];
	}

	/**
	 * @param $post_parent
	 * @param $post_type
	 *
	 * @return array
	 */
	public static function getPosts( $post_parent, $post_type ) {

		if ( ! is_array( $post_type ) ) {
			$post_type = array( $post_type );
		}

		$args  = array(
			'post_type'      => $post_type,
			'post_parent'    => $post_parent,
			'posts_per_page' => - 1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'draft',
		);
		$query = new \WP_Query( $args );

		return $query->posts;

	}

	/**
	 * Get all chapter of course
	 * @return array
	 */
	public function getChapters() {
		$args  = array(
			'post_type'      => array( 'chapter' ),
			'post_parent'    => $this->post->ID,
			'posts_per_page' => - 1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'draft',
		);
		$query = new \WP_Query( $args );

		return $query->posts;

	}

	/**
	 * @param $post_id
	 * @param $data_meta
	 */
	public function saveMetaData( $post_id, $data_meta ) {
		$attrs = $this->getAttributes();
		foreach ( $attrs as $key => $data ) {
			if ( isset( $data_meta[ $key ] ) ) {
				update_post_meta( $post_id, $key, $data_meta[ $key ] );
			}
		}
	}

	/**
	 * @param $meta_name
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	public function getValueMeta( $meta_name, $default = '' ) {
		return isset( $this->{$meta_name} ) ? $this->{$meta_name} : $default;
	}

	/**
	 * Return model data
	 * Model data is attribute data received from post meta table
	 * @return object
	 */
	public function getData( $attrs = [] ) {
		$data = [];
		if ( ! empty( $this->post ) ) {
			if ( empty( $attrs ) ) {
				$attrs = $this->attributes;
			}
			foreach ( $attrs as $attr => $params ) {
				$vAttr = get_post_meta( $this->post->ID, $attr, true );
				if ( ! is_string( $vAttr ) ) {
					//$vAttr = json_encode($vAttr);
				}
				$this->{$attr} = $vAttr;
				$data[ $attr ] = $vAttr;
			}
		}

		return (object) $data;
	}

	/**
	 * Get all chapter related to this course
	 * @return ChapterModel[]
	 */
	public function getAllChapterModel() {
		/** @var ChapterModel[] $chapter */
		$chapters = [];
		$args     = array(
			'post_type'      => array( 'chapter' ),
			'post_parent'    => $this->post->ID,
			'posts_per_page' => - 1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC'
		);
		$query    = new \WP_Query( $args );
		foreach ( $query->posts as $post ) {
			$chapters[] = ChapterModel::findOne( $post );
		}

		return $chapters;
	}


	/**
	 * get hours in course
	 * @return int
	 */
	public function getHours() {
		$course_length = lema()->wp->get_post_meta( $this->post->ID, 'course_length', true );

		return $course_length;
	}

	/**
	 * get category of post
	 *
	 * @param  string field need take in category
	 *
	 * @return mixed
	 */
	public function getCategory( $field = '' ) {
		$model    = CourseCategoryModel::getInstance();
		$category = '';
		if ( ! empty( $this->post ) ) {
			$categories = $model->getCategoryCourse( $this->post->ID );
			if ( count( $categories ) > 0 ) {
				$category = $categories[0];
			}

			if ( ! empty( $category ) && ! empty( $field ) ) {
				if ( isset( $category->{$field} ) ) {
					return $category->{$field};
				} else {
					return '';
				}
			}
		}

		return $category;

	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		$value = parent::__get( $name );
		if ( empty( $value ) || $value === 'null' ) {
			$post = $this->post;
			if ( isset( $this->post->ID ) ) {
				return get_post_meta( $this->post->ID, $name, true );
			}
		}

		return $value; // TODO: Change the autogenerated stub
	}

	/**
	 * get thumbnail url
	 *
	 * @param  string $urlDefault "url default when empty url"
	 *
	 * @return string
	 */
	public function getThumbnailUrl( $urlDefault = '' ) {
		if ( isset( $this->post->ID ) ) {
			$thumbnail_url = lema()->wp->get_the_post_thumbnail_url( $this->post->ID );
			if ( ! empty( $thumbnail_url ) ) {
				return $thumbnail_url;
			}
		}

		return $urlDefault;
	}


	/**
	 *
	 * Get current price
	 * if currently sale price was set return sale price
	 * else return regular price
	 *
	 * @return float
	 */
	public function getPrice() {
		$price = $this->course_price;
		if ( is_numeric( $this->course_sale_price ) ) {
			$price = $this->course_sale_price;
		}
		if ( empty( $price ) ) {
			$price = 0;
		}

		return $price;
	}

	/**
	 * get meta video url
	 * @return string
	 */
	public function getVideoUrl() {
		$url = '';
		if ( isset( $this->post->ID ) ) {
			$promotional_video = $this->promotional_video;
			if ( ! empty( $promotional_video ) ) {
				$url = lema()->wp->wp_get_attachment_url( $promotional_video );
			}
		}

		return $url;
	}

	/**
	 * get data curriculum
	 * @return []
	 */
	public function getCurriculum() {
		$listData = [];
		$args     = [
			'post_parent' => $this->post->ID,
			'post_type'   => 'chapter',
			'orderby'     => 'menu_order',
			'order'       => 'ASC'
		];
		$chapters = get_children( $args );
		foreach ( $chapters as $chapter_id => $chapter ) {

			$args['post_parent']     = $chapter_id;
			$listData[ $chapter_id ] = [ 'lesson' => [], 'quiz' => [] ];

			$args['post_type'] = 'lesson';
			$lessons           = get_children( $args );
			foreach ( $lessons as $lesson_id => $lesson ) {
				$listData[ $chapter_id ]['lesson'][] = $lesson_id;
			}
			$args['post_type'] = 'quiz';
			$quizs             = get_children( $args );
			foreach ( $quizs as $quiz_id => $quiz ) {
				$listData[ $chapter_id ]['quiz'][] = $quiz_id;
			}

		}

		return $listData;
	}

	/**
	 * devide tab attr
	 * @return []
	 */
	public function getChildTab() {
		$tabs       = [
			'general' => [],
			'other'   => []
		];
		$attributes = $this->attributes;
		foreach ( $attributes as $name => $options ) {
			if ( isset( $options['tab'] ) && $options['tab'] == 'general' ) {
				$tabs['general'][] = $name;
			}
			if ( isset( $options['tab'] ) && $options['tab'] == 'other' ) {
				$tabs['other'][] = $name;
			}
		}

		return apply_filters( 'lema_course_form_section', $tabs );
	}

	/* Get all Course
	*
	* @param $args
	*
	* @return array
	*/
	public function getAll( $args = array(), $all = true ) {
		$args = array_merge(
			array(
				'post_type'        => $this->getName(),
				'post_status'      => 'publish',
				'numberposts'      => - 1,
				'suppress_filters' => true,
			),
			$args
		);

		$courses = array();
		$posts   = get_posts( $args );
		if ( $all == true ) {
			if ( ! is_wp_error( $posts ) ) {
				foreach ( $posts as $post ) {
					$courses[] = self::findOne( $post->ID );
				}
			}
		} else {
			$courses = $posts;
		}

		return $courses;
	}

	/**
	 * @param string $query
	 *
	 * @return string
	 */
	public function getLearningUrl( $query = '' ) {
		$learningUrl = lema()->page->getPageUrl( 'learning' );
		$slugLink    = '/lema-';

		return $learningUrl . $slugLink . $this->post->post_name . $query;
	}

	/**
	 * @param string $query
	 *
	 * @return string
	 */
	public function getDashboardUrl( $query = '' ) {
		$dashboardUrl = lema()->page->getPageUrl( 'dashboard' );
		$slugLink     = '/lema-';

		return $dashboardUrl . $slugLink . $this->post->post_name . $query;
	}

	/**
	 * @return url first lesson
	 */
	public function getUrlFirstLesson() {
		$listChapter = $this->getCurriculum();
		$lessonUrl   = '';
		foreach ( $listChapter as $chapter ) {
			if ( isset( $chapter['lesson'][0] ) ) {
				$lessonUrl = '?lesson=' . $chapter['lesson'][0];
				break;
			}
		}

		return $this->getLearningUrl( $lessonUrl );
	}


	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public static function customFields( $fields = [] ) {
		$fields = FieldModel::getLastUsed();

		return apply_filters( 'lema_course_custom_fields', $fields );
	}

	/**
	 * @param integer $courseId
	 */
	public static function viewDetailAction( $courseId ) {
		$key = "view_course_{$courseId}" . ( is_multisite() ? '_' . get_site()->id : '' );
		if ( ! empty( $courseId ) && ! isset( $_COOKIE[ $key ] ) ) {
			$current = get_post_meta( $courseId, 'course_view_count', true );
			if ( $current == null ) {
				$current = 0;
			}
			$current += 1;
			update_post_meta( $courseId, 'course_view_count', $current );
			setcookie( $key, $courseId, time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
		}
	}


	/**
	 * Get num of user's view.
	 *
	 * @param int $courseId
	 *
	 * @return number
	 * */
	public function getViewNumber( $courseID = false ) {

		$view_count = 0;
		if ( $courseID ) {
			$course_view_count = get_post_meta( $courseID, 'course_view_count', true );
			$view_count        = ! empty( $course_view_count ) ? $course_view_count : $view_count;
		}

		return $view_count;
	}


}
