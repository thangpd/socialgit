<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\shortcodes\course;



use lema\core\interfaces\CacheableShortcodeInterface;
use lema\core\interfaces\ShortcodeInterface;
use lema\core\Shortcode;


class CourseShortcode extends Shortcode implements CacheableShortcodeInterface
{
    /**
     * Id if shortcode
     */
    const SHORTCODE_ID              = 'lema_course';
    public $contentView             = 'card';

    private $supportedLayouts       = ['layout-1', 'layout-2'];

    private $headerShortcodes       = ['lema_coursecard_image'];
    private $contentShortcodes       = [
        'lema_coursecard_category' ,
        'lema_coursecard_title',
        'lema_coursecard_description',
        'lema_coursecard_instructor'
    ];
    private $footerShortcodes       = [
        'lema_coursecard_bookmark',
	    'lema_coursecard_numview'
    ];

    private $otherShortcodes       = [
        'lema_course_content',
    ];


    private $supportedShortcodes = [
        'lema_rating', 'lema_coursecard_progress', 'lema_add_cart'
    ];

    private $templates = [];

    public function _init() {
        add_action('lema-shortcode-ready', [$this, 'collectChild']);
    }

    public function collectChild()
    {
        //add action for shortcode
        $this->headerShortcodes = lema()->wp->apply_filters('lema_course_header', $this->headerShortcodes);
        $this->contentShortcodes = lema()->wp->apply_filters('lema_course_content', $this->contentShortcodes);
        $this->footerShortcodes = lema()->wp->apply_filters('lema_course_footer', $this->footerShortcodes);
        $this->otherShortcodes = lema()->wp->apply_filters('lema_course_other', $this->otherShortcodes);
        $this->supportedShortcodes = array_merge(
            $this->supportedShortcodes,
            $this->headerShortcodes,
            $this->contentShortcodes,
            $this->footerShortcodes,
            $this->otherShortcodes
        );

    }

    /**
     * @return array
     */
    public function getStatic()
    {
        return [
           [
                'type'          => 'script',
                'id'            => 'lema-shortcode-course-script',
                'isInline'      => false,
                'url'           => 'assets/scripts/lema-shortcode-course.js',
                'dependencies'  => ['lema', 'lema.shortcode']
            ],
            [
                'type'          => 'style',
                'id'            => 'lema-shortcode-course-style',
                'isInline'      => false,
                'url'           => 'assets/styles/lema-shortcode-course.css',
                'dependencies'  => ['lema-shortcode-style']
            ]
        ];
    }

    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        return self::SHORTCODE_ID;
    }

    /**
     * Array of default value of all shortcode options
     * NOTE: add attribute of "shortcode render" has_{shortcode}, attr_{shortcode}
     * @return array
     */
    public function getAttributes()
    {
        $supports = [];
        foreach ($this->getShortcodeSupport() as $shortcode) {
            $supports[str_replace('-', '_', $shortcode)] = true ;//By default this shortcode is enabled
            $supports[str_replace('-', '_', 'attr_'.$shortcode)] = "" ;//value default
        }
        $return=array_merge([
	        'layout'    => 'layout-1',
	        'post_id'   => '',
	        'template'  => 'default'

        ], $supports);
        return $return;
    }

    /**
     * @param array $data
     * @param string $content
     * @return string
     */
    public function getShortcodeContent($data = [], $content = '', $key = '')
    {
        $shortcodeContent = lema_do_shortcode($content);
        $params = [];
        $data = $this->getData($data);
        $data['content'] = $shortcodeContent;
        $key = $this->getId() . $data['data']['post_id'] . $data['data']['layout'];
        return $this->render($this->contentView, array_merge($data, $params), true, $key);
    }

    /**
     * All element shortcode support
     * @return array
     */
    public function getShortcodeSupport()
    {
        $this->supportedShortcodes = apply_filters('lema_course_shortcode_supported', $this->supportedShortcodes);
        return $this->supportedShortcodes;
    }

    /**
     * get html format field
     * @param  string $key 
     * @return string
     */
    public function getFormatHtml($key = '') {
        $format = array(
                        'layout-1'     => '
                        <div class="course-item">
                            <div class="course-wrapper">
                                <div class="top-wrapper">
                                    {header}
                                </div>
                                <div class="middle-wrapper course-content-wrapper">
                                    {content}
                                </div>
                                <div class="bottom-wrapper">
                                    {footer}
                                </div>
                                [sc_course_content]
                            </div>
                        </div>
                        {other}
                        '
                        ,
                        'layout-2' => '<div class="item lema-course layout-2 course-item">
                                        <div class="course-wrapper">
                                            <div class="item-block lema-item-left">
                                                [lema_coursecard_image]
                                            </div>
                                            <div class="item-block lema-item-right">
                                                [lema_coursecard_category]
                                               [lema_coursecard_title]
                                               [lema_coursecard_description]
                                               <div class="lema-row">
                                                    <div class="instructor-block">[lema_coursecard_instructor]</div>
                                                    <div class="rating-block">[lema_rating]</div>
                                               </div>
                                               [lema_coursecard_price]
                                               [lema_coursecard_bookmark]
                                               [lema_coursecard_progress]
                                            </div>
                                        </div>
                                    </div>',     
                        'default'     => '%1$s', 
                    );
        $format = lema()->wp->apply_filters('lema_shortcode_custom_format_html', $format, $this->getId());
        $header = '';
        foreach ($this->headerShortcodes as $name) {
            $header .= "[$name]\n";
        }
        $content = '';
        foreach ($this->contentShortcodes as $name) {
            $content .= "[$name]\n";
        }
        $footer = '';
        foreach ($this->footerShortcodes as $name) {
            $footer .= "[$name]\n";
        }

        $other = '';
        foreach ($this->otherShortcodes as $name) {
            $other .= "[$name]\n";
        }
        $shortcodes = [
            '{header}' => $header,
            '{content}' => $content,
            '{footer}'  => $footer,
            '{other}'  => $other
        ];
        foreach ($format as $layout => &$layoutContent) {
            $layoutContent = lema()->wp->apply_filters('lema_course_layout_' . str_replace('-', '_', $layout), $layoutContent);
            $layoutContent = str_replace(array_keys($shortcodes) , array_values($shortcodes), $layoutContent);
        }
        if ( array_key_exists($key, $format) ) {
            return $format[$key];
        }
        return $format['default'];
    }



    /**
     * render hmtl child shortcode follow data
     * @param  array $data
     * @return string
     */
    public function renderHtmlChildShortcode($data) {

        $format_block = '';
        if ( !empty($data['post_id']) && !empty($data['layout']) ) {
            $post_id = $data['post_id'];
            $layout = $data['layout'];
            $format_block = $this->getFormatHtml($data['layout']);

            // render shortcode
            /**
             * By default, all children shortcodes will be shown
             * @var array $list_support
             */
            $list_support = $this->getShortcodeSupport();
            $allShortcodes = lema()->shortcodeManager->getRegisteredShortcodes();
            $disabledShortcdes = lema()->shortcodeManager->getDisabledShortcode();
            foreach ($disabledShortcdes as $s) {
                $allShortcodes[$s] = [];
            }
            foreach ($allShortcodes as $id => $shortcode) {
                $html = '';
                if (in_array($id, $list_support) && !in_array($id, $disabledShortcdes)) {
                    $affter_fix = str_replace('-', '_', $id);

                    //take attribute of child shortcode
                    $attr = '';
                    $key_attr = 'attr_' . $affter_fix;
                    if ( !empty($data[$key_attr]) ) {
                        $attr = $data[$key_attr];
                        $attr = str_replace(',', ' ', $attr);
                    }

                    $key_attr = $affter_fix;
                    if (isset($data[$key_attr]) && $data[$key_attr]) {
                        if (false === $data[$key_attr]) {
                            continue;
                        }
                        $html = sprintf('[%1$s post_id="%2$s" object_id="%2$s" %3$s]', $id, $post_id, $attr);
                        $html = lema_do_shortcode($html);
                    }
                } else {
                    $html = '';
                }
                $reg = '/(\['. $id .' (.*?)\])|(\['. $id .'\])/i';
                $format_block = preg_replace($reg, $html, $format_block);

            }
        }
        return $format_block;
    }

    // ========AJAX========

     /**
     * get list action register
     * @return array 
     */
    public function actions()
    {
        add_filter('lema_card_custom_data', [$this, 'customData']);
        add_action('lema_shortcode_course_flushcache', [$this, 'removeCache']);
        return [

        ];
    }

    /**
     * Remove course cached by ID
     * @param $courseId
     */
    public function removeCache($courseId)
    {
        $defaults = ['default'];
        $this->templates = apply_filters('lema_shortcode_course_templates', $this->templates);
        $this->templates = array_merge($defaults, $this->supportedLayouts , $this->templates);
        foreach ($this->templates as $template) {
            lema()->cache->delete($this->getId() . $courseId . $template);
        }
    }

    /**
     * custom input data
     * @param  [] $data 
     * @return []
     */
    public function customData($data) {
        if ( isset($data['layout']) ) {
            switch ($data['layout']) {
                case 'layout-2':
                    if ( isset($data['attr_lema_coursecard_category']) && empty($data['attr_lema_coursecard_category']) ) {
                        $data['attr_lema_coursecard_category'] = "limit=3";
                    }

                    if ( isset($data['attr_lema_rating']) && empty($data['attr_lema_rating']) ) {
                        $data['attr_lema_rating'] = "style=simple";
                    }

                    if ( isset($data['attr_lema_coursecard_instructor']) && empty($data['attr_lema_coursecard_instructor']) ) {
                        $data['attr_lema_coursecard_instructor'] = "limit=2";
                    }
                    break;
            }
        }
        return $data;
    }

    /**
     * @return ShortcodeInterface[] | string[]
     */
    public function getChildren()
    {
        return $this->supportedShortcodes;
    }

    /**
     * Find shortcode content in cache
     * if it exists just return result
     *
     * @param array $data
     * @param array $param
     * @return mixed|null|string
     */
    public function findInCache($data = [], $param = [], $key = '')
    {
        $data = $this->getData($data);
        $data = $data ['data'];
        $key = $this->getId() . $data['post_id'] . $data['layout'];
        return parent::findInCache($data, $param, $key);
    }
}