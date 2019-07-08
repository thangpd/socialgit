<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\course;


use lema\core\Shortcode;
use lema\models\OrderModel;
use lema\shortcodes\course\CourseListShortcode;
use lema\models\OrderItemModel;

class CourseListEnrolledShortcode extends CourseListShortcode
{
    const SHORTCODE_ID              = 'lema_course_list_enrolled';

    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return self::SHORTCODE_ID;
    }

    /**
     * Array of default value of all shortcode options
     * @return array
     */
    public function getAttributes()
    {
        return parent::getAttributes();
    }

    /**
     * Return array of arguments for WP_Query usage
     * @param Object $data attributes of the shortcode
     * @return Array
     */
    public function getQuery($data) {
        global $wpdb;

	    $query = "select meta_value as course_id from {$wpdb->prefix}postmeta 
where post_id in (select iom.meta_value from {$wpdb->prefix}woocommerce_order_itemmeta iom
 join {$wpdb->prefix}woocommerce_order_items woi on woi.order_item_id = iom.order_item_id 
 where iom.order_item_id in (select woi.order_item_id from {$wpdb->prefix}postmeta pm JOIN {$wpdb->prefix}woocommerce_order_items woi 
 on woi.order_id = pm.post_id where pm.meta_key='_customer_user' and pm.meta_value=%d  and pm.post_id in (select ID from {$wpdb->prefix}posts 
 where post_status in ('%s','%s'))) and iom.meta_key='_product_id') and meta_key='_course'";
/*
 * select meta_value from wp_postmeta where post_id in (select iom.meta_value from wp_woocommerce_order_itemmeta iom join wp_woocommerce_order_items woi on woi.order_item_id = iom.order_item_id where iom.order_item_id in (select woi.order_item_id from wp_postmeta pm JOIN wp_woocommerce_order_items woi on woi.order_id = pm.post_id where pm.meta_key='_customer_user' and pm.meta_value=5 and pm.post_id in (select ID from wp_posts where post_status in ('wc-processing','wc-completed'))
) and iom.meta_key='_product_id') and meta_key='_course'
 *
 *
 * */
//        $query = "SELECT course_id FROM $table WHERE order_id IN (SELECT post_id as order_id FROM $wpdb->postmeta WHERE post_id IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'lema_order_user_id' AND meta_value = %d) AND meta_key = 'order_status' AND meta_value = %d);";
        //die($query);
	//$list_id = $wpdb->get_results($wpdb->prepare("SELECT lema_order.course_id FROM $wpdb->postmeta LEFT JOIN {$table} lema_order ON $wpdb->postmeta.post_id = lema_order.order_id WHERE $wpdb->postmeta.meta_key = 'lema_order_user_id' AND $wpdb->postmeta.meta_value = %d GROUP BY course_id",get_current_user_id()));
	    $query=$wpdb->prepare($query, get_current_user_id(), 'wc-completed','wc-processing');
        $list_id = $wpdb->get_results($query);

        $list_id = apply_filters('lema_courselist_enrolled',$list_id );
        $ids = [];
        if($list_id){
            foreach($list_id as $key => $item){
                $ids[] = $item->course_id;
            }

            $args = array(
                'post_type' => 'course',
                'post__in' => $ids,
            );
        }else{
            $args = null;
        }
	//print_r($args);
        return $args;
    }
}
