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
 * @since             1.0.0
 * @package           Learnmaster
 *
 * @wordpress-plugin
 * Plugin Name:       Learn Setup
 * Plugin URI:        http://rubikthemes.com/LearnMaster
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            RubikThemes
 * Author URI:        http://rubikthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       learnmaster
 * Domain Path:       /languages
 */

define('PATH_LEARN_SETUP', plugin_dir_url( __FILE__ ));
define('DIR_LEARN_SETUP', dirname( __FILE__ ));
require 'learn-setup/hook.php';
