<?php
/**
 * @project  eduall
 * @copyright © 2017 by Chuke Hill Co.,LTD
 * @author ivoglent
 * @time  11/14/17.
 */


namespace lema\shortcodes\course;


use lema\core\RuntimeException;
use lema\core\Shortcode;
use lema\models\CourseModel;
use lema\models\FieldModel;

class CustomAttributeShortcode extends Shortcode
{
    const SHORTCODE_ID          = 'lema_course_custom_attributes';

    public function getAttributes()
    {
        return [
            'title' => __('Extra information', 'lema'),
            'attributes' => '',
            'css_class' => '',
            'course_id' => ''
        ];
    }

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
     * @param array $data
     * @param array $params
     * @param string $key
     * @return string
     */
    public function getShortcodeContent($data = [], $params = [], $key = '')
    {
        $viewPath = apply_filters('lema-course-customfield_view', dirname(__FILE__) . '/views/custom.php');
        $data = $this->getData($data);
        if (empty($data['data']['course_id'])) {
            throw new RuntimeException(__('Invalid course ID', 'lema'));
        }
        $course = CourseModel::findOne($data['data']['course_id']);
        if (empty($course->post)) {
            throw new RuntimeException(__('Invalid course ID', 'lema'));
        }
        $fields = FieldModel::getAllFields();
        $data['course'] = $course;
        $data['fields'] =  $fields;
        return $this->render($viewPath, $data);
    }
}