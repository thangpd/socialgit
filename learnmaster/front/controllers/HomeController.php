<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  8/11/17.
 */


namespace lema\front\controllers;


use lema\core\components\Script;
use lema\core\interfaces\FrontControllerInterface;

class HomeController extends FrontController implements FrontControllerInterface
{

    /**
     * Lema home page
     * @return string
     */
    public function lemaHome()
    {
        global $post;
        lema()->wp->wp_enqueue_script('lema');
        lema()->wp->wp_enqueue_script('lema.ui');
        return $this->render('index', [
            'page' => $post,
            'isHome' => $this->checkHomepage($post)
        ]);
    }

    /**
     * Set /lema-home as homepage of this site
     */
    public function setHomepage()
    {
        $pages = get_posts( ['name' => 'lema-home' , 'post_type' => 'page', 'post_status' => 'publish'] );
        if (!empty($pages)) {
            $homepage = array_shift($pages);
            update_option( 'page_on_front', $homepage->ID );
            update_option( 'show_on_front', 'page' );
            $this->responseJson([
                'code' => 200,
                'data' => __('Set your home page completed successfully. Please reload your page to see the change.', 'lema')
            ]);
        }
    }

    /**
     * Check if lema homepage is current home page
     * if not show an option to help user set this page is site home page
     * @param WP_POST $page
     * @return bool
     */
    public function checkHomepage($page)
    {
        if (!empty($page) && get_option('page_on_front') == $page->ID && get_option('show_on_front') == 'page') {
            return true;
        }
        return false;
    }
    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction()
    {
        // TODO: Implement registerAction() method.
        return [
            'pages' => [
                'front' => [
                    lema()->config->getUrlConfigs('lema_home') => ['lemaHome', [
                        'db' => true,
                        'title' => 'Lema Home'
                    ]]
                ]
            ],
            'ajax' => [
                'set_lema_homepage' => [self::getInstance(), 'setHomepage']
            ]
        ];
    }
}