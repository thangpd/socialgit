<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/20/17.
 */


namespace lema\admin\controllers;


use lema\core\Controller;
use lema\core\interfaces\ControllerInterface;

class CourseLanguageController extends AdminController implements ControllerInterface
{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        add_filter('manage_edit-language_course_columns' , [self::getInstance(), 'customColumnManage']);
	    return [
            'actions' => [
                'language_course_edit_form' => [self::getInstance(), 'changTextJSSlug'],
                'language_course_add_form' => [self::getInstance(), 'changTextJSSlug'],
            ],
            'ajax' => [],
        ];
    }

     public     function customColumnManage( $columns )
        {
            $columns['slug'] = esc_html__('Language - Location', LEMA_NAMSPACE);
            return $columns;
        }
    /**
     * get option data
     * @return [object] 
     */
    public function getOptions() {
        $model = \lema\models\CourseLanguageModel::getInstance();
        return $model->getOptions();
    }

    /**
     * change slug text
     */
    public function changTextJSSlug() {
     ?>
        <script type="text/javascript">
            ;(function($, lema){
                $('.form-field.term-slug-wrap label').html('<?php echo esc_html__('Language - Location', LEMA_NAMSPACE) ?>');
                $('.form-field.term-slug-wrap p').html('ex : vi-VN, en-US, ...');
             })(jQuery, lema);
        </script>
     <?php
    }
     
}