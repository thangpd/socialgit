<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/17/17.
 */


namespace lema\shortcodes\categorylist;

use lema\core\interfaces\CacheableInterface;
use lema\core\Shortcode;

class CategoryListShortcode extends Shortcode
{

    public $contentView = 'categorylist';


    /**
     * Get static resources of shortcode
     *
     * @return [];
     */

    public function getStatic()
    {
        return [
            [
                'type'          => 'script',
                'id'            => 'cat-list-script',
                'isInline'      => false,
                'url'           => 'assets/scripts/cat-list.js',
                'dependencies'  => ['lema']
            ],
            [
                'type'          => 'style',
                'id'            => 'course-category-block',
                'isInline'      => false,
                'url'           => 'assets/styles/course-category-block.css',
                'dependencies'  => ['lema-shortcode-style', 'font-awesome']
            ],
            [
                'type'          => 'style',
                'id'            => 'cat-list',
                'isInline'      => false,
                'url'           => 'assets/styles/cat-list.css',
                'dependencies'  => ['lema-shortcode-style', 'font-awesome']
            ]
        ];
    }

    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        return 'lema_category_list';
    }

    /**
     * Array of default value of all shortcode options
     * @return array
     *filter: feature, best-selling,... 
     */
    public function getAttributes()
    {
        return [
            'list_name'         => '',
            'limit'             => '',
            'taxonomy'          => 'cat_course',
            'control'           => $this,
            'cols_in_row'       => '3',
            'is_title'          => true,
            'is_description'    => true,
            'is_icon'           => true,
            'limit_description' => '',
            'filter'            => '',
            'hide_empty'        => false,
            'data'              => '',
            'class'
        ];
    }

    /**
     * filter data category by attributes
     * @return [object]
     */
    public function filter($attrs=[]) {
        if ( empty($attrs) ) {
            $attrs = $this->attributes;
        }
        extract($attrs);
        $args = [];
        $categories = [];
        if ( isset($taxonomy) ) {
            $args['taxonomy'] = $taxonomy;
        }

        //custom number terms
        if ( isset($limit) && !empty($limit) ) {
            $args['number'] = intval($limit);            
        }

         //custom hide empty
        if ( isset($hide_empty) ) {
            $args['hide_empty'] = $hide_empty;            
        }

        //filter by list name
        if ( isset($list_name) && !empty($list_name) ) {
            if ( is_string($list_name) ) {
                $list_name = explode(',', $list_name);
            }
            $args['name'] = $list_name;
        } else if ( isset($filter) && !empty($filter) ) {
            $args = array_merge($args, apply_filters('lema_category_list_filter_condition', $filter));
        }
        $categories = get_terms($args);
        return $categories;
    }
    
    /**
     * filter data by condition
     * @param  string $filter: feature, top-enroll, ...
     * @return []
     */
    public function filterCondition($filter='') {
        $args=[];
        switch ($filter) {
            case 'feature':
                $args['meta_key'] = 'is_feature';
                $args['meta_value'] = '1';
                break;
            case 'best-selling':
                global $wpdb;
                $query = 'SELECT DISTINCT `wp_term_relationships`.`term_taxonomy_id` term_id, 
                                            SUM(`wp_postmeta`.`meta_value`) total  
                        FROM `wp_term_relationships` 
                            INNER JOIN `wp_postmeta` 
                            on `wp_term_relationships`.`object_id` = `wp_postmeta`.post_id 
                                and `wp_postmeta`.`meta_key`=%s
                        GROUP BY `wp_term_relationships`.`term_taxonomy_id`
                        ORDER BY SUM(`wp_postmeta`.`meta_value`) DESC;'; 
                    
                    $query = $wpdb->prepare($query, 'course_total_enroll');
                    $listTerm = $wpdb->get_results($query);
                    $listTermID = [];
                    foreach ($listTerm as $i => $term) {
                        $listTermID[] = $term->term_id;
                    }
                    $args['term_id'] = $listTermID;
                break;
            default:
                break;
        }
        return $args;
    }


    /**
     * @return boolean
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
    }

     /**
 * get list action register
 * @return array 
 */
    public function actions()
    {
        lema()->wp->add_filter('lema_category_list_filter_condition', [$this, 'filterCondition']);
        return [];
    }

     public function getFormatHtml($key = '') {
        $format = array(
                        'block'     => '<div class="[CLASS_BLOCK] lema-course-block-list ' . $this->defineShortcodeBlock(false) .'" [DATA_BLOCK]>
                                            <div class="lema-columns [CLASS_COL]">[CONTENT]</div>
                                        </div>',     
                        'item'      => '<div class="item">
                                            <div class="course-block">
                                               [ICON_CAT]
                                                <div class="content-cell">
                                                    <div class="wrapper-info">
                                                        [TITLE]
                                                        [DESCRIPTION]
                                                    </div>
                                                </div>
                                            </div>
                                        </div>',
                        'icon'  => ' <div class="icon-cell">
                                        <a class="wrapper-icon" href="%2$s">
                                            <i class="lema-icon %1$s"></i>
                                        </a>
                                    </div>',    
                        'title' => '<div class="title"><a href="%2$s">%1$s</a></div>',
                        'description' => '<div class="description">%s</div>',            
                        'default'   => '%1$s',
                    );
        $format = lema()->wp->apply_filters('lema_shortcode_custom_format_html', $format, $this->getId());
        if ( array_key_exists($key, $format) ) {
            return $format[$key];
        }
        return $format['default'];
    }

}