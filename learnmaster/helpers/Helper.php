<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/18/17.
 */


namespace lema\helpers;


use lema\core\BaseObject;
use lema\core\interfaces\ComponentInterface;
use lema\models\BundleModel;
use lema\models\OrderItemModel;
use lema\models\OrderModel;
use lema\models\CourseModel;
use lema\models\Student;

class Helper extends BaseObject implements ComponentInterface {
	/**
	 * @var GeneralHelper
	 */
	public $general;
	/**
	 * @var FileHelper
	 */
	public $file;

	/**
	 * @var WordpressHelper
	 */
	public $wp;

	/**
	 * @var ValidatorHelper
	 */
	public $validator;

	/** @var  FormHelper */
	public $form;

	/** @var SecurityHelper */
	public $security;

	public function __construct( array $config = [] ) {
		parent::__construct( $config );
		$this->general   = new GeneralHelper();
		$this->file      = new FileHelper();
		$this->wp        = new WordpressHelper();
		$this->validator = new ValidatorHelper();
		$this->form      = new FormHelper();
		$this->security  = new SecurityHelper();
	}

	/**
	 * @param $_name
	 *
	 * @return mixed
	 */
	public function getHelper( $_name ) {
		$name = ucfirst( $_name ) . 'Helper';
		if ( empty( $this->$_name ) ) {
			$this->$_name = new $name();
		}

		return $this->$_name;
	}

	/**
	 * Check the course
	 * if user enrolled, redirect it to dashboard
	 *
	 * @param $post
	 */
	public function checkUserEnroll( $post ) {
		global $post;
		if ( ! empty( $post ) ) {
			if ( ! is_admin() && is_user_logged_in() && $post->post_type == 'course' && is_single() ) {
				$student = Student::getCurrentUser();
				if ( $student->checkEnrolled( $post->ID ) ) {
					/** @var CourseModel $course */
					$course = CourseModel::findOne( $post );
					wp_redirect( $course->getDashboardUrl() );
					exit;
				}
			}
		}
	}

	/**
	 * Inserts any number of scalars or arrays at the point
	 * in the haystack immediately after the search key ($needle) was found,
	 * or at the end if the needle is not found or not supplied.
	 * Modifies $haystack in place.
	 *
	 * @param array &$haystack the associative array to search. This will be modified by the function
	 * @param string $needle the key to search for
	 * @param mixed $stuff one or more arrays or scalars to be inserted into $haystack
	 *
	 * @return int the index at which $needle was found
	 */
	public function arrayInsertAfter( &$haystack, $needle = '', $stuff ) {
		if ( ! is_array( $haystack ) ) {
			return $haystack;
		}

		$new_array = array();
		for ( $i = 2; $i < func_num_args(); ++ $i ) {
			$arg = func_get_arg( $i );
			if ( is_array( $arg ) ) {
				$new_array = array_merge( $new_array, $arg );
			} else {
				$new_array[] = $arg;
			}
		}

		$i = 0;
		foreach ( $haystack as $key => $value ) {
			++ $i;
			if ( $key == $needle ) {
				break;
			}
		}

		$haystack = array_merge( array_slice( $haystack, 0, $i, true ), $new_array, array_slice( $haystack, $i, null, true ) );

		return $i;
	}

	/**
	 * Check is buy Bundle or membership
	 */
	public static function checkIsBuy( $post_type, $postID ) {
		$is_check = false;
		$orderIds = [];

		if ( is_user_logged_in() ) {
			global $wpdb;
			$userId = get_current_user_id();
			$query  = $wpdb->prepare( 'select * from ' . $wpdb->posts . ' p INNER JOIN ' . $wpdb->postmeta . ' mp on p.ID=mp.post_id and mp.meta_key="lema_order_user_id" and mp.meta_value=%1$s and p.post_status="publish"',
				array(
					$userId,
				) );

			$rows = $wpdb->get_results( $query );
			if ( ! empty( $rows ) ) {
				foreach ( $rows as $row ) {
					$bought = get_post_meta( $row->post_id, $post_type );

					switch ( $post_type ) {
						case BundleModel::POST_TYPE:

							if ( ! empty( $bought ) && is_array( $bought ) ) {
								if ( $bought[0] == $postID ) {
									$orderIds[] = $row->ID;
								}
							} else {
								if ( $bought == $postID ) {
									$orderIds[] = $row->ID;
								}
							}
							break;
						case OrderModel::ORDER_EXPIRABLE:
							$expired = get_post_meta( $row->post_id, 'order_expire_date', true );

							if ( ! empty( $expired ) ) {
								$expired = new \DateTime( $expired );
								$today   = new \DateTime( 'now' );
								if ( $expired > $today ) {
									$orderIds[] = $row->ID;
								}
							} else {
								//case never expire;
								$orderIds[] = $row->ID;
							}
							break;
					}

				}
			}

			if ( ! empty( $orderIds ) ):
				$orderIds = implode( ',', $orderIds );
				$query    = 'SELECT count(meta_id) total FROM ' . $wpdb->postmeta . " WHERE post_id IN ({$orderIds}) AND meta_value = " . OrderModel::ORDER_STATUS_COMPLETED;
				$rows     = $wpdb->get_results( $query );
				if ( $rows[0]->total > 0 ) {
//					$row = array_shift( $rows );

					$is_check = true;
				}
			endif;
		}

		return $is_check;
	}

	/**
	 * Get course bestselling
	 *
	 * @param bool $category
	 *
	 * @return mixed|void
	 */
	public function getCourseBestSelling( $category = false ) {
		$cacheName   = "course_bestselling" . ( $category ? ( "_" . $category ) : '' );
		$bestSelling = [];
		$bestSelling = lema()->cache->get( $cacheName, [] );
		if ( ! is_array( $bestSelling ) ) {
			$bestSelling = [];
		}

		apply_filters( 'lema_course_bestselling', $bestSelling, $category );
	}

	// get post ID from name
	public static function get_post_name2id( $name, $post_type ) {
		$args  = array(
			'name'             => $name,
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'posts_per_page'   => 1,
			'suppress_filters' => false,
		);
		$posts = get_posts( $args );
		if ( $posts ) {
			return $posts[0]->ID;
		}

		return false;
	}

	public static function getCourseinWoo( $courseID, $slug = '' ) {
		if ( empty( $slug ) ) {
			$slug = get_post_field( 'post_name', $courseID );
		}

		$product_id = Helper::get_post_name2id( $slug, 'product' );

		return $product_id;
	}


}