<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\bundle;


use lema\core\Shortcode;


class BundleShortcode extends Shortcode {
	/**
	 * Id if shortcode
	 */
	const SHORTCODE_ID = 'lema_bundlecard';
	public $contentView = 'card';


	public function _init() {
	}


	/**
	 * @return array
	 */
	public function getStatic() {
		return [
			[
				'type'         => 'script',
				'id'           => 'lema-shortcode-bundlecard-script',
				'isInline'     => false,
				'url'          => 'assets/scripts/lema-shortcode-bundlecard.js',
				'dependencies' => [ 'lema', 'lema.shortcode' ]
			],
			[
				'type'         => 'style',
				'id'           => 'lema-shortcode-bundlecard-style',
				'isInline'     => false,
				'url'          => 'assets/styles/lema-shortcode-bundlecard.css',
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
	 * NOTE: add attribute of "shortcode render" has_{shortcode}, attr_{shortcode}
	 * @return array
	 */
	public function getAttributes() {

		$return = [
			'layout'  => 'layout-1',
			'post_id' => '',
		];

		return $return;
	}

	/**
	 * @param array $data
	 * @param string $content
	 *
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $content = '', $key = '' ) {

		$data = $this->getData( $data );


		return $this->render( $this->contentView, $data, true );
	}



	// ========AJAX========

	/**
	 * get list action register
	 * @return array
	 */
	public function actions() {

	}


}