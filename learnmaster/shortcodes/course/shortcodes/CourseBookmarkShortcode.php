<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/31/17.
 */


namespace lema\shortcodes\course\shortcodes;


use lema\core\Shortcode;

class CourseBookmarkShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_coursecard_bookmark';
	public $contentView = 'bookmark';

	private $userID = '';

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		// TODO: Implement getId() method.
		return self::SHORTCODE_ID;
	}


	public function getStatic() {
		return [
			[
				'type'         => 'script',
				'id'           => 'course_bookmark_shortcode',
				'isInline'     => false,
				'url'          => 'assets/scripts/bookmark.js',
				'dependencies' => [ 'lema' ]
			]
		];
	}

	public function _init() {
		$this->userID = get_current_user_id();
	}

	/**
	 * Array of default value of all shortcode options
	 * @return array
	 */
	public function getAttributes() {
		return [
			'post_id'      => '',
			'layout'       => 'layout-1',
			'label_button' => 'BOOKMARK'
		];
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		$data = $this->getData( $data );

		return $this->render( $this->contentView, $data, true );
	}


	/**
	 * Check a post_id is existed in bookmarks
	 *
	 * @param $posttype_id
	 *
	 * @return bool
	 */
	public function isBookmarked( $postId ) {
		$bookmarked = get_user_meta( get_current_user_id(), $this->bookmarkName(), true );
		if ( empty( $bookmarked ) ) {
			$bookmarked = [];
		}

		return in_array( $postId, $bookmarked );
	}

	/**
	 * Remove bookmarked course ID from user meta
	 *
	 * @param $courseId
	 */
	public function removeStudentBookmark( $courseId ) {
		$bookmarked = get_user_meta( $this->userID, $this->bookmarkName(), true );
		if ( empty( $bookmarked ) ) {
			$bookmarked = [];
		}
		if ( in_array( $courseId, $bookmarked ) ) {
			array_splice( $bookmarked, array_search( $courseId, $bookmarked ), 1 );
		}
		update_user_meta( $this->userID, $this->bookmarkName(), $bookmarked );
	}

	/**
	 * Add courseId to bookmarked user meta
	 *
	 * @param $couseId
	 */
	public function addStudentBookmark( $couseId ) {
		$bookmarked = get_user_meta( $this->userID, $this->bookmarkName(), true );
		if ( empty( $bookmarked ) ) {
			$bookmarked = [];
		}
		if ( ! in_array( $couseId, $bookmarked ) ) {
			$bookmarked[] = $couseId;
			update_user_meta( $this->userID, $this->bookmarkName(), $bookmarked );
		}
	}

	/**
	 * Get all bookmarked course ids
	 * @return array|mixed
	 */
	public function getBookmarkedCourseIds() {
		$bookmarked = get_user_meta( $this->userID, $this->bookmarkName(), true );
		if ( empty( $bookmarked ) ) {
			$bookmarked = [];
		}

		return $bookmarked;
	}

	/**
	 * Get user bookmark name belong to site
	 * @return string
	 */
	public static function bookmarkName() {
		$bookmarkName = 'course_bookmarks';
		if ( is_multisite() ) {
			$siteId       = get_current_blog_id();
			$bookmarkName = "site{$siteId}-{$bookmarkName}";
		}

		return $bookmarkName;
	}

	/**
	 * @return array
	 */
	public function actions() {
		return [
			'ajax' => [
				'ajax_remove_course_bookmark' => [ $this, 'removeBookmarkByAjax' ],
				'ajax_add_course_bookmark'    => [ $this, 'addBookmarkByAjax' ],
				'ajax_check_bookmark'         => [ $this, 'checkIsBookmarked' ]
			]
		];
	}


	/**
	 * remove bookmark by ajax
	 */
	public function removeBookmarkByAjax() {
		$data_post = isset( $_POST ) ? $_POST : array();
		if ( isset( $data_post['post_id'] ) && is_user_logged_in() ) {
			$post_id = $data_post['post_id'];
			$this->removeStudentBookmark( $post_id );
			lema()->wp->do_action( 'lema_shortcode_course_flushcache', $post_id );
		}
		echo 1;
		exit();
	}

	/**
	 * add bookmark by ajax
	 */
	public function addBookmarkByAjax() {
		$data_post = isset( $_POST ) ? $_POST : array();
		if ( isset( $data_post['post_id'] ) && is_user_logged_in() ) {
			$post_id = $data_post['post_id'];
			$this->addStudentBookmark( $post_id );
			lema()->wp->do_action( 'lema_shortcode_course_flushcache', $post_id );
		}
		echo 1;
		exit();
	}

	/**
	 * Check a course is bookmarked by user
	 */
	public function checkIsBookmarked() {
		if ( is_user_logged_in() ) {
			if ( isset( $_GET['post_id'] ) ) {
				$postId = $_GET['post_id'];
				if ( $this->isBookmarked( $postId ) ) {
					return wp_send_json( [
						'code'   => 200,
						'status' => 2 //Bookmarked
					] );
				} else {
					return wp_send_json( [
						'code'   => 200,
						'status' => 1 //Unbookmark
					] );
				}
			}
		}

		return wp_send_json( [
			'code'   => 200,
			'status' => 0 //Not logged in
		] );

	}

	/**
	 * Get num of user's bookmarked.
	 *
	 * @param int $postId
	 *
	 * @return number
	 * */
	public function getBookmarkNumber( $postID = false ) {

		$count = 0;
		if ( $postID ) {
			$meta_user = get_users( array(
				'meta_key'     => $this->bookmarkName(),
				'meta_value'   => $postID,
				'meta_compare' => 'LIKE'
			) );
			$count     = count( $meta_user );

		}

		return $count;
	}

	public function getFormatHtml( $key = '' ) {
		$format = array(
			'layout-3' => '<div class="lema-course-bookmark" data-post_id="%1$s"><i class="%2$s"></i></div>',
			'layout-2' => '<button class="lema-course-bookmark" data-post_id="%1$s">
                                            <i class="%2$s"></i>                                            %3$s
                                        </button>',
			'layout-1' => '<div class="lema-course-bookmark-quantity" data-post_id="%1$s">
                                            <i class="%2$s"></i> %4$s
                                        </div>',
			'default'  => '%1$s',
		);
		$format = lema()->wp->apply_filters( 'lema_shortcode_custom_format_html', $format, $this->getId() );
		if ( array_key_exists( $key, $format ) ) {
			return $format[ $key ];
		}

		return $format['default'];
	}

}