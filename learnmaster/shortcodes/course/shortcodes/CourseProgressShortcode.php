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

class CourseProgressShortcode extends Shortcode
{

    const SHORTCODE_ID          = 'lema_coursecard_progress';
    public $contentView         = 'progress';


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
        ];
    }

    /**
     * @param array $data
     * @return string
     */
    public function getShortcodeContent($data = [], $params = [], $key = '')
    {
        $data = $this->getData($data);
        return $this->render($this->contentView, $data, true);
    }
}