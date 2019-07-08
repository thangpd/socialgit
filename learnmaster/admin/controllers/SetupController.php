<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/25/17.
 */


namespace lema\admin\controllers;


use lema\core\components\Form;
use lema\core\components\Hook;
use lema\core\interfaces\ControllerInterface;
use lema\core\RuntimeException;

class SetupController extends AdminController implements ControllerInterface
{
    public $viewPath = '';
    /**
     * setup page
     */
    public function setupPage()
    {   

        if ( empty( $_GET['page'] ) || 'lema-setup' !== $_GET['page'] ) {
            return;
        }
        
        $this->render('setup', []);
        die;
    }

    public function registerMenu(){
        add_dashboard_page( '', '', 'manage_options', 'lema-setup', '' );
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {

        // TODO: Implement registerAction() method.
        return [
            'actions' => [
                'admin_init' => [self::getInstance(), 'setupPage'],
                'admin_menu' => [self::getInstance(), 'registerMenu'],
            ]
        ];
    }
}