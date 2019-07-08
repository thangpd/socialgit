<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 * @since 1.0
 *
 */

namespace lema\core\components;



use lema\core\BaseObject;
use lema\core\interfaces\ComponentInterface;

use lema\core\Template;
use lema\models\CourseModel;
use lema\models\FieldModel;

class Hook extends BaseObject implements ComponentInterface
{
    const LEMA_HOOK_ADMIN_MENU              = 'lema_admin_menu';
    const LEMA_MODEL_READY                  = 'lema_model_ready';
    const LEMA_AFTER_CORE_RESOURCES         = 'lema_after_core_resources';
    const LEMA_AUTH_ROLES                   = 'lema_auth_roles';
    const LEMA_AUTH_CAPS                    = 'lema_auth_caps';
    const LEMA_SHORTCODE_REGISTER           = 'lema_shortcode_register';
    const LEMA_SHORTCODE_EXTENDS            = 'lema_shortcode_extends';
    const LEMA_COMPONENTS                   = 'lema_components';
    const LEMA_RUN                          = 'lema_run';
    const LEMA_COURSE_ATTRS                 = 'lema_course_attributes';
    const LEMA_SEND_MAIL                    = 'lema_send_mail';
    /**
     * Default hooks for Learn Master plugin
     * @var array
     */
    protected $actions = [
        'show_admin_bar' => [
            '\lema\models\UserModel' => 'showAdminBar'
        ],
        'admin_init' => [
            '\lema\core\components\ResourceManager' => 'registerCoreResource',
        ],
        'wp_enqueue_scripts' => [
            '\lema\core\components\ResourceManager' => 'registerCoreResource',
        ],
        'template_redirect' => [
            //'\lema\core\components\ResourceManager' => 'release',
            '\lema\helpers\Helper' => 'checkUserEnroll'
        ],

        'admin_enqueue_scripts' => [
            '\lema\core\components\ResourceManager' => 'registerCoreResource',
        ],
        'flushCache' => [
            '\lema\core\components\Cache' => 'flushCache'
        ],
        'admin_menu' => [
            '\lema\admin\AdminTemplate' => 'addAdminMenu'
        ],
        'wp_logout' => [
            '\lema\models\UserModel' => 'redirectLogout'
        ],
        'save_post' => [

        ],
        'author_link' => [
            '\lema\models\UserModel' => ['authorLink', 10, 2]
        ],
        'the_post' => [
            '\lema\core\components\ResourceManager' => 'registerShortcodeAssets',
        ],
        'lema_send_mail' => [
            '\lema\core\components\Mailer' => ['sendMail', 10, 4] ,
        ],
        'login_redirect' => [
            '\lema\models\UserModel' => ['gotoProfile', 10, 3] ,
        ],
	    'login_url' =>[
	    	'\lema\models\UserModel' => ['redirectLoginUrl',10,3],
	    ]

    ];
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->defaultHooks();
    }

    /**
     * Init Hook
     * Call register all hooks after init
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

    }

    /**
     * Register all default hooks
     */
    public function defaultHooks()
    {
        foreach ($this->actions as $action => $param) {
            foreach ($param as $class => $method) {
                if (!is_object($class)) {
                    /** @var BaseObject $class */
                    $class = $class::getInstance();
                }
                if (is_string($method)) {
                    $this->listenHook($action, [$class, $method]);
                } else {
                    $this->listenFilter($action, [$class, $method[0]], $method[1], $method[2]);
                }
            }
        }
        //Manual action/filter
        //add_filter('lema_course_customfields', [FieldModel::className(), 'getAllFields']);
        add_filter('lema_course_attributes', [FieldModel::className(), 'applyCourseCustomFields']);
        add_action('lema_course_view_detail', [CourseModel::className(), 'viewDetailAction']);
    }

    /**
     * @param $name
     * @param $params
     */
    public function registerHook($name, $params){
        $params = func_get_args();
        return call_user_func_array('do_action', $params);
    }

    /**
     * @param $name
     * @param $param
     * @return mixed
     */
    public function registerFilter($name, $param)
    {
        $params = func_get_args();
        return call_user_func_array('apply_filters', $params );
    }

    /**
     * @param $name
     * @param $callable
     */
    public function listenHook($name, $callable, $priority = 10, $accepted_args = 1) {
        lema()->wp->add_action($name, $callable, $priority, $accepted_args);

    }
    /**
     * @param $name
     * @param $callable
     */
    public function listenFilter($name, $callable, $priority = 10, $accepted_args = 1) {
       return lema()->wp->add_filter($name, $callable, $priority, $accepted_args);
    }



}