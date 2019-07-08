<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 * @since 1.0
 *
 *
 * Boot object for lema plugin
 * Create app instance and global components
 *
 *
 */

namespace lema\core;




class Bootstrap extends BaseObject
{

    /**
     * Boot application
     */
    public static function boot($config = [])
    {
        $app = App::getInstance($config);
        add_action('after_setup_theme' , [$app, '__init'], 0);
        add_action('wp_loaded' , [$app, 'run']);
    }
}


