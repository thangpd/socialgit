<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\core\components;


use lema\core\BaseObject;
use lema\core\interfaces\ComponentInterface;
use lema\core\RuntimeException;


/**
 * @mute
 * @package lema\core\components
 * @method add_action($name, $callable)
 * @method filter_action($name, $callable)
 * @method do_action($name, $args = '')
 * @method wp_enqueue_script($handle, $src, $depends =[], $footer = false)
 * @method wp_enqueue_style($handle, $src, $depends = [])
 * @method wp_localized_script($handle, mixed $data)
 * @method get_query_var($name, $default = '')
 * @method get_post_var($name, $default = '')
 * @method is_user_logged_in()
 * @method is_admin()
 * @method add_role($role, $display_name, $capabilities = array())
 * @method remove_role($name)
 * @method wp_get_current_user()
 * @method wp_write_post()
 * @method register_post_type( $post_type, $args = array() )
 * @method register_taxonomy( $taxonomy, $object_type, $args = array() )
 * @method update_post_meta( $post_id, $meta_key, $meta_value, $prev_value = '' )
 * @method get_post($post = null, $output = OBJECT, $filter = 'raw');
 * @method shortcode_attrs( $pairs, $atts, $shortcode = '')
 */
class WP extends BaseObject implements ComponentInterface
{

    /**
     * @param $name
     * @param $arguments
     * @return bool|mixed
     */
    public function __call($name, $arguments)
    {
        try {
            if (method_exists($this, $name)) {
                return $this->$name($arguments);
            }
            if (function_exists($name)) {
                return call_user_func_array($name, $arguments);
            }
        } catch (\Exception $e) {
            if (LEMA_DEBUG) {
                throw new RuntimeException($e);
            }

        }
        return false;
    }

}