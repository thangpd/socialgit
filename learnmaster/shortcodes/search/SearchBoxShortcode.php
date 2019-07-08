<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

namespace lema\shortcodes\search;


use lema\core\interfaces\ShortcodeInterface;
use lema\core\Shortcode;

class SearchBoxShortcode extends Shortcode implements ShortcodeInterface
{

    const SHORTCODE_ID              = 'lema_search_box';

    public $contentView             = 'search-box';
    /**
     * Get Id of shortcode
     * @return string
     */
    public function getId()
    {
        return self::SHORTCODE_ID;
    }

    public function getStatic()
    {
        return [
            [
                'type' => 'script',
                'url' => 'assets/scripts/auto-complete.min.js',
                'id' => 'auto-complete',
            ], [
                'type' => 'script',
                'url' => 'assets/scripts/lema-search-box.js',
                'id' => 'lema-search-box-script',
                'dependencies' => ['lema', 'lema.ui', 'auto-complete']
            ],
            [
                'type' => 'style',
                'url' => 'assets/styles/auto-complete.css',
                'id' => 'auto-complete-style',
            ],
            [
                'type' => 'style',
                'url' => 'assets/styles/lema-search-box.css',
                'id' => 'lema-search-box-style',
                'dependencies' => ['lema-style']
            ]
        ];
    }

    public function getAttributes()
    {
        return [
            'name' => '',
        ];
    }

    /**
     * @param array $data
     * @param array $params
     * @return string
     */
    public function getShortcodeContent($data = [], $params = [], $key ='')
    {
        if (!is_array($params)) {
            $params = [];
        }
        $data = $this->getData($data);
        $searchUrl = site_url() . '/lema-search';
        $searchUrl = lema()->hook->registerFilter('lema_search_url', $searchUrl);
        $data['searchUrl'] = $searchUrl;
        $data['q'] = isset($_GET['q']) ? $_GET['q'] : '';
	    $view_path = apply_filters( 'lema_shortcode_search_box_view_path', $this->contentView );
        return $this->render($view_path, array_merge($data, $params), true);
    }

    /**
     * Get search term stored in lema_search_terms option
     */
    public function searchTerm()
    {
        $term = isset($_GET['term']) ?$_GET['term'] : '';
        $terms = lema()->config->lema_search_terms;
        if (!empty($term)) {
            $data = [];
            $queryArgs = [
                's' => $term,
                'post_status' => 'publish',
                'post_type'   => 'course',
                'posts_per_page' => 10
            ];
            $query = new \WP_Query($queryArgs);
            while ( $query->have_posts() ) :$query->the_post();
                $image_id = get_post_thumbnail_id();
                $imagesize="thumbnail";
                $image_url = wp_get_attachment_image_src($image_id, $imagesize, true);
                $data[] = lema()->helpers->general->renderPhpFile(__DIR__. '/views/_item.php', [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'subtitle' => get_post_meta(get_the_ID(), 'course_subtitle', true),
                    'thumb' => $image_url
                ]);
            endwhile;
           /* foreach ($terms as $k => $v) {
                if (preg_match("/{$term}/i", $k)) {
                    $data[] = [
                        'keyword' => $k,
                        'result' => $v
                    ];
                }
            }*/
            return $this->responseJson([
                'data' => $data
            ]);
        }

    }

    /**
     * Register actions
     * @return array
     */
    public function actions()
    {
        return [
            'ajax' => [
                'lema-search-term' => [$this, 'searchTerm']
            ]
        ];
    }
}