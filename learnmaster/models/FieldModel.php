<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace  lema\models;


use lema\admin\controllers\CourseController;
use lema\admin\controllers\FieldController;
use lema\core\BaseObject;
use lema\core\interfaces\ModelInterface;
use lema\core\Model;

class FieldModel extends BaseObject
{
    public static $fieldAttributes = [
        'name' => '',
        'label' => '',
        'type' => 'text',
        'default' => '',
        'primary' => false,
        'filterable' => false
    ];

    private static $fields = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);

    }

    /**
     * Get option name
     * @return string
     */
    private static function getOptionName()
    {
        $default = 'lema_course_customfields';
       /* if (is_multisite()) {
            $default = 'site_' . get_current_site()->site_id . '_' . $default;
        }*/
        return $default;
    }

    /**
     * Get option name
     * @return string
     */
    private static function getOptionLastUsedName()
    {
        $default = 'lema_course_last_used';
        /*if (is_multisite()) {
            $default = 'site_' . get_current_site()->site_id . '_' . $default;
        }*/
        return $default;
    }

    /**
     * Get all defined fields
     * @return mixed
     */
    public static function getAllFields()
    {

        if (empty(self::$fields)) {
            self::$fields = get_option(self::getOptionName());
            if (empty(self::$fields)) {
                self::$fields = [];
            }
            self::$fields = apply_filters('lema_course_custom_fields', self::$fields);
        }
        return self::$fields;
    }

    /**
     * @param array $fields
     */
    public static function updateFields($fields = []) {
        self::$fields = $fields;
        update_option(self::getOptionName(), $fields);
    }

    /**
     * @param $name
     * @param $label
     * @param $type
     * @param $default
     * @param bool $primary
     * @param bool $filterable
     */
    public static function addField($name, $label, $type, $default, $primary = false, $filterable = false) {
        $fields = self::getAllFields();
        switch ($type) {
            case 'list' :
            case 'select' :
            case 'radiolist' :
            case 'checklist' :
                $default = explode("\n", trim($default));
                foreach ($default as &$d) {
                    $d = trim($d);
                }
                break;
        }
        $fields[$name] = self::$fieldAttributes;
        $fields[$name] = [
            'name' => $name,
            'label' => $label,
            'type' => $type,
            'default' => $default,
            'primary' => $primary,
            'filterable' => $filterable
        ];
        self::updateFields($fields);
    }

    /**
     * @param $name
     */
    public static function deleteField($name) {
        $fields = self::getAllFields();
        if (array_key_exists($name, $fields)) {
            unset($fields[$name]);
            self::updateFields($fields);
        }
    }

    /**
     * @param $name
     * @return bool | array
     */
    public static function getField($name) {
        $fields = self::getAllFields();
        if (array_key_exists($name, $fields)) {
            return $fields[$name];
        }
        return false;
    }

    /**
     * @param array $fields
     */
    public static function updateLastUsed($fields = []) {
        update_option(self::getOptionLastUsedName(), $fields);
    }

    /**
     * @return mixed
     */
    public static function getLastUsed()
    {
        return get_option(self::getOptionLastUsedName(), self::getAllFields());
    }


    /**
     * Get list of field type
     * @return mixed
     */
    public static function getFieldTypes() {
        $defaultTypes = [
            'text' => __('Text field', 'lema'),
            'textarea' => __('Text block', 'lema'),
            'editor' => __('Text editor', 'lema'),
            'list' => __('Text list', 'lema'),
            'select' => __('List options', 'lema'),
            /*'radio' => __('Radio', 'lema'),*/
            'checklist' => __('Checkbox List', 'lema'),
            'checkbox' => __('Checkbox', 'lema'),
            'radiolist' => __('Radio buttons', 'lema')
        ];

        return apply_filters('lema_course_field_types', $defaultTypes);
    }

    /**
     * @param $attributes
     */
    public static function applyCourseCustomFields($attributes)
    {
        $fields = self::getLastUsed();
        $postType = 'Course';
        foreach ($fields as $field) {
            $form = [
                'type' => $field['type'],
                'class' => 'la_form_control',
                'name' => $postType . "[{$field['name']}]",
                'label' => $field['label'],
                'value'  => '',
                'template' => '<div class="col-100"><div class="la-form-group"><label>{label}</label>{input}</div><span class="remove-attrbute hide" data-attribute="' . $field['name'] . '"><i class="fa fa-close"></i></span> </div>',
            ];
            switch ($field['type']) {
                case 'list' :
                    $form['type'] = 'custom';
                    $form['name'] = $postType . "[{$field['name']}][]";
                    $form['renderer'] = [CourseController::getInstance(), 'renderMultiRowField'];
                    $form['value'] = json_encode($field['default']);
                    break;
                case 'select' :
                case 'radiolist' :
                case 'checklist' :
                    $options = [];
                    foreach ($field['default'] as $value) {
                        $options[$value] = $value;
                    }
                    $form['options'] = $options;
                    if ($form['type'] == 'checklist') {
                        $form['name'] = $postType . "[{$field['name']}][]";
                    }
                    break;

            }
            $attributes[$field['name']] = [
                'label' => $field['label'],
                'form' => $form,
                'tab' => (isset($field['primary']) &&  $field['primary']) ? '' : 'other'
            ];
        }

        //This is a future feature
        /*$custom = [];
        $custom['type'] = 'custom';
        $custom['renderer'] = [FieldController::getInstance(), 'selectCustomField'];
        $attributes['customfield'] = [
            'label' => 'Custom',
            'form' => $custom,
            'tab' => 'other'
        ];*/
        return apply_filters('lema_course_customfield_applied', $attributes);
    }
}