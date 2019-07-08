<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/31/17.
 */


namespace lema\shortcodes\course\shortcodes;


use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;
use lema\models\CourseModel;
use lema\models\Student;

class CourseNumViewShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_coursecard_numview';
	public $contentView = 'numview';


	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		// TODO: Implement getId() method.
		return self::SHORTCODE_ID;
	}

	public function getStatic() {

	}

	/**
	 * Array of default value of all shortcode options
	 * @return array
	 */
	public function getAttributes() {
		return [
			'post_id' => '',
			'layout'  => 'layout-1',
		];
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		$data    = $this->getData( $data );
		$post_id = $data['data']['post_id'];
		$model   = CourseModel::findOne( $post_id );
		if ( ! empty( $model ) ) {
			$data['model'] = $model;
		}

		return $this->render( $this->contentView, $data );
	}

	/**
	 * @return array
	 */
	public function actions() {
	}


	public function getFormatHtml( $key = '' ) {
		$format = array(
			'layout-1' => '<div class="lema-course-num-view" data-post_id="%1$s">
                                            <i class="%2$s"></i> %3$s
                                        </div>',
		);
		$format = lema()->wp->apply_filters( 'lema_shortcode_custom_format_html', $format, $this->getId() );
		if ( array_key_exists( $key, $format ) ) {
			return $format[ $key ];
		}

		return $format['default'];
	}

}