<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/21/17.
 */


namespace lema\admin\controllers;


use lema\core\interfaces\ControllerInterface;


class DeactivateController extends AdminController implements ControllerInterface
{

    /**
     * Action when plugin was deactivated
     * @return string
     */
    public function deactivation(){
        $redirect = '';
        if (isset($_GET['redirect'])) {
            $redirect = urldecode($_GET['redirect']);
        }
        $redirect .= '&lema_confirm=true';
        if (isset($_POST['keep'])) {
            lema()->wp->wp_redirect($redirect);
            exit;
        }
        if (isset($_POST['delete'])) {
            //Delete all plugin data
            lema()->deactivate();
            lema()->wp->wp_redirect($redirect);
            exit;
        }
        return $this->render('deactivation', [
            'redirect' => $redirect
        ]);
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        return [
            'pages' => [
                'confirm-deactivate' => [
                    'title' => 'Deactivation confirmation',
                    'capability' => 'activate_plugins',
                    'action' => [self::getInstance(), 'deactivation'],
                    'menu' => 'deactivation-menu'
                ]
            ]
        ];
    }
}