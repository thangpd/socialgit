<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://rubikthemes.com
 * @since             1.0.18
 * @package           Learnmaster
 *
 * @wordpress-plugin
 * Plugin Name:       Learn Master
 * Plugin URI:        http://rubikthemes.com/plugins/lema
 * Description:       Core of Learn Master plugin. The best solution to build an online learning system.
 * Version:           1.0.18
 * Author:            RubikThemes
 * Author URI:        http://rubikthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lema
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
defined('LEMA_VERSION') or define('LEMA_VERSION', '1.0.18');

/**
 * Require composer autoload file
 */
require_once (__DIR__ .'/vendor/autoload.php');

/**
 * Get plugin default configs
 */
$config = require (__DIR__ .'/config.php');
/**
 * Require plugin bootstrap
 */


require_once (__DIR__ .'/functions.php');
require_once (__DIR__ .'/core/Bootstrap.php');
/**
 * Run Learn master plugin
 */

\lema\core\Bootstrap::boot($config);

/**
 * Register the hook run when this plugin activated
 */
register_activation_hook( __FILE__, [\lema\core\App::getInstance(), 'activate']);
/**
 * Register the hook run when this plugin deactivated
 */
register_deactivation_hook( __FILE__, [\lema\core\App::getInstance(),'deactivate']);






/*function detect_plugin_deactivation(  $plugin, $network_activation ) {
    if ($plugin == 'learnmaster/learnmaster.php') {
        global $wp;
        if (!isset($_GET['lema_confirm'])) {
            $current_url ="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            wp_redirect('admin.php?page=confirm-deactivate&redirect=' . urlencode($current_url));
            exit;
        }


    }

}
add_action( 'deactivated_plugin', 'detect_plugin_deactivation', 10, 2 );*/
