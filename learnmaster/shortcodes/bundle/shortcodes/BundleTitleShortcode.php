<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/31/17.
 */


namespace lema\shortcodes\bundle\shortcodes;


use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;

class BundleTitleShortcode extends Shortcode
{
    const SHORTCODE_ID          =  'lema_bundlecard_title';
    public $contentView         = 'title';


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
            'post_id'       => '',
            'has_link'      => true,
        ];
    }

    /**
     * @param array $data
     * @return string
     */
    public function getShortcodeContent($data = [], $params = [], $key='')
    {
        $data = $this->getData($data);
        return $this->render($this->contentView, $data, true);
    }

    public function getFormatHtml($key = '') {
        $format = array(
                        'block'     => '<div class="title-bundle">%s</div>',
                        'title'     => '<a class="lema-link" href="%2$s">%1$s</a>',
                        'default'   => '%1$s',
                    );
        $format = lema()->wp->apply_filters('lema_shortcode_custom_format_html', $format, $this->getId());
        if ( array_key_exists($key, $format) ) {
            return $format[$key];
        }
        return $format['default'];
    }
}