<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\course\shortcodes;


use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;
use lema\models\CourseCategoryModel;

class CourseCategoryShortcode extends Shortcode
{
    const SHORTCODE_ID          =  'lema_coursecard_category';
    public $contentView         = 'category';


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
            'limit'         => ''
        ];
    }

    /**
     * @param array $data
     * @return string
     */
    public function getShortcodeContent($data = [], $params = [], $key = '' )
    {
        $data = $this->getData($data);
        return $this->render($this->contentView, $data, true);
    }

    public function getFormatHtml($key = '') {
        $format = array(
                        'block'         => '<div class="lema-button-category">%s</div>',     
                        'category_item' => '<a href="%2$s" class="button course">%1$s</a>',
                        'default'   => '%1$s',
                    );
        $format = lema()->wp->apply_filters('lema_shortcode_custom_format_html', $format, $this->getId());
        if ( array_key_exists($key, $format) ) {
            return $format[$key];
        }
        return $format['default'];
    }

    public function getCategory($post_id)
    {
        $model = CourseCategoryModel::getInstance();
        return $model->getCategoryCourse($post_id);
    }
}