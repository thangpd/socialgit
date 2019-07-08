<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\bundle\shortcodes;


use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;

class BundleImageShortcode extends Shortcode {
	const SHORTCODE_ID = 'lema_bundlecard_image';
	public $contentView = 'image';
	public $html_option = array();

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		// TODO: Implement getId() method.
		return self::SHORTCODE_ID;
	}


	/**
	 * Array of default value of all shortcode options
	 * @return array
	 */
	public function getAttributes() {
		return [
			'class'             => '',
			'post_id'           => '',
			'has_link_post'     => true,
			'has_view_button'   => true,
			'text_view_button'  => 'View details',
			'has_like_button'   => true,
			'text_like_button'  => 'Like',
			'has_add_button'    => true,
			'text_add_button'   => 'Add to cart',
			'has_label'         => true,
			'text_label'        => 'Best Selling',
			'has_note_devices'  => true,
			'text_note_devices' => 'Avaiable on devices',
			'class_label_block' => 'lema-best-selling',
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
	 * get format html field
	 *
	 * @param  string $key
	 *
	 * @return string
	 */
	public function getFormatHtml( $key = '' ) {
		$format = $this->html_option;
		if ( array_key_exists( $key, $format ) ) {
			return $format[ $key ];
		}

		return $format['default'];
	}

	/**
	 * set default option for $html_option
	 */
	function setHtmlOptionDefault() {
		/* block
		* %1$s: class
		* %2$s: image
		* %3$s: label
		* %4$s: hours
		* %5$s: button
		*/
		$option            = array(
			'block'        => '<div class="bundle-image-wrapper %1$s">
                                                %2$s
                                                %3$s
                                                %4$s
                                                %5$s
                                            </div>',
			'image-full'   => '<a href="%2$s" class="link">
                                            <img src="%1$s" alt="" class="img-responsive img-full">
                                        </a>',
			'image'        => '<img src="%1$s" alt="" class="img-responsive img-full">',
			'block_button' => '<div class="button lema-view-add">
                                                %1$s %2$s %3$s
                                            </div>',
			'view_button'  => '<a href="%2$s" class="view-button">%1$s</a>',
			'add_button'   => '<a href="%2$s" class="add-button">%1$s</a>',
			'note_devices' => '<div class="note-devices">%1$s</div>',
			'label'        => '<div class="lema-label %2$s">%1$s</div>',
			'default'      => '%1$s',
		);
		$this->html_option = $option;
	}

	/**
	 *set value for $html_option
	 *
	 * @param: array $html_option
	 *
	 * @return: not return
	 */
	function setHtmlOption( $html_option = array() ) {
		$this->html_option = $html_option;
	}
}