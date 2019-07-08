<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\shortcodes\bundle\shortcodes;


use lema\core\Shortcode;

class BundleDescriptionShortcode extends Shortcode
{
    const SHORTCODE_ID          =  'lema_bundlecard_description';
    public $contentView         = 'description';


    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return self::SHORTCODE_ID;
    }


    /**
     * Array of default value of all shortcode options
     * @return array
     */
    public function getAttributes()
    {
        return [
            'post_id'           => '',
            'limit_text'        =>  '30', 
            'afterString'       =>  '...',
        ];
    }

    /**
     * @param array $data
     * @return string
     */
    public function getShortcodeContent($data = [], $params = [], $key ='')
    {
        $data = $this->getData($data);
        return $this->render($this->contentView, $data, true);
    }

    public function getFormatHtml($key = '') {
        $format = array(
                        'block'         => '<div class="description-bundle">%s</div>',
                        'default'       => '%1$s',
                    );
        $format = lema()->wp->apply_filters('lema_shortcode_custom_format_html', $format, $this->getId());
        if ( array_key_exists($key, $format) ) {
            return $format[$key];
        }
        return $format['default'];
    }
}