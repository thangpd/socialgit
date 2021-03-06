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

class CourseContentShortcode extends Shortcode
{
    const SHORTCODE_ID          = 'lema_course_content';
    public $contentView         = 'content';
    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        return self::SHORTCODE_ID;
    }

    /**
     * @param array $data
     * @param string $content
     * @return string
     */
    public function getShortcodeContent($data = [], $content = '', $key = '')
    {
        $shortcodeContent = lema_do_shortcode($content);
        return parent::getShortcodeContent($data, ['content' => $shortcodeContent]); // TODO: Change the autogenerated stub
    }

    public function getAttributes()
    {
        return [
            'post_id'           => '',
            'limit_text'        => 30
        ];
    }

}