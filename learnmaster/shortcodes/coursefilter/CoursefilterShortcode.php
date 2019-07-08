<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/17/17.
 */


namespace lema\shortcodes\coursefilter;


use lema\core\Shortcode;
use lema\models\BundleCategoryModel;
use lema\models\BundleModel;
use lema\models\CourseModel;

class CoursefilterShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_course_filter';

	public $contentView = 'coursefilter';

	const MAX_ITEMS_SHOW = 5;
	/**
	 * @var array
	 */
	public $sortables = [];
	/**
	 * @var array
	 */
	public $sorttypes = [];
	/**
	 * @var array
	 */
	public $filters = [];
	/**
	 * @var array
	 */
	public $course_type = [];
	/**
	 * @var array
	 */
	public $filter_type = [];
	/**
	 * @var array
	 */
	public $defaultTerms = [];


	/**
	 * Get static resources of shortcode
	 *
	 * @return [];
	 *
	 */
	public function getStatic() {
		return [
			[
				'type' => 'style',

				'id'       => 'course-filter',
				'isInline' => false,
				'url'      => '/assets/styles/course-filter.css'
			],
			[
				'id'   => 'tab-filter',
				'type' => 'style',

				'isInline' => false,
				'url'      => '/assets/styles/tab-filter.css'
			],
			[
				'id'   => 'advanced-search',
				'type' => 'style',

				'isInline' => false,
				'url'      => '/assets/styles/advanced-search.css'
			],
			[
				'id'   => 'course-autocomplete-search',
				'type' => 'style',

				'isInline' => false,
				'url'      => '/assets/styles/course-autocomplete-search.css'
			],
			[
				'type'         => 'script',
				'id'           => 'course-filter-sc',
				'isInline'     => false,
				'url'          => '/assets/scripts/lema-course-filter.js',
				'dependencies' => [ 'lema', 'lema.ui' ]
			]
		];
	}

	/**
	 * _init function
	 */
	public function _init() {
		$this->setDefaultdata();
	}

	/**
	 * set default data
	 * */
	public function setDefaultdata() {
		$this->sortables    = [
			'date_desc' => __( 'Newest - Oldest', 'lema' ),
			'date_asc'  => __( 'Oldest - Newest', 'lema' ),

			'review_desc' => __( 'Most Reviewed', 'lema' ),

			// 'relevant_desc' => __('Most Relevent', 'lema'),

			'rate_desc' => __( 'Rate: Hight - Low', 'lema' ),
			'rate_asc'  => __( 'Rate: Low - Hight', 'lema' ),

			'__course_price_desc' => __( 'Price: Hight - Low', 'lema' ),
			'__course_price_asc'  => __( 'Price: Low - Hight', 'lema' ),

			'title_asc'  => __( 'Title: A - Z', 'lema' ),
			'title_desc' => __( 'Title: Z - A', 'lema' ),
		];
		$this->course_type  = [
			CourseModel::COURSE_SLUG => __( 'Course', 'lema' ),
			BundleModel::POST_TYPE   => __( 'Bundle', 'lema' ),
		];
		$this->filter_type  = array(
			'all'  => __( 'All', 'lema' ),
			'paid' => __( 'Paid', 'lema' ),
			'sale' => __( 'Sale', 'lema' ),
			'free' => __( 'Free', 'lema' ),
		);
		$this->defaultTerms = $this->getDefautTermFilters();

		$this->filters = $this->getFilters();


	}

	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		return self::SHORTCODE_ID;
	}

	/**
	 * Array of default value of all shortcode options
	 * @return array
	 */
	public function getAttributes() {
		return [
			'course_vc'     => '1',
			'template'      => 'template_1',
			'template_1'    => 'style_1',
			'cols_on_row'   => '3',
			'cptype'        => CourseModel::COURSE_SLUG,
			'subfilter'     => 'all',
			'filter_layout' => 'list',  // attr this sc
			'layout'        => 'grid',  // layout attr course
		];
	}

	/**
	 * @return boolean
	 */
	public function isEnabled() {
		// TODO: Implement isEnabled() method.
	}


	/**
	 * Choose posttype
	 */

	public function handlePostType() {
		$data = isset( $_GET['cptype'] ) ? $_GET['cptype'] : [];
		switch ( $data ) {

			case BundleModel::POST_TYPE:
				$posttype = BundleModel::POST_TYPE;
				break;
			default:
				$posttype = CourseModel::COURSE_SLUG;
				break;
		}

		return $posttype;
	}

	/**
	 * Get default term filter
	 * @return array
	 */
	private function getDefautTermFilters() {
		$arr = [
			BundleModel::POST_TYPE   => [
				BundleCategoryModel::BUNDLECAT_SLUG => __( 'Category', 'lema' ),
			],
			CourseModel::COURSE_SLUG => [
				'cat_course'      => __( 'Category', 'lema' ),
				'tag_course'      => __( 'Topic', 'lema' ),
				'level_course'    => __( 'Level', 'lema' ),
				'language_course' => __( 'Language', 'lema' )
			],
		];

		return apply_filters( 'default_term_filter_searchpage', $arr[ $this->handlePostType() ] );
	}


	/**
	 * @param $name
	 */
	private function filteredTerm( $name ) {
		$cacheName = 'lema_sc_filter_tax_' . $name;
		$terms     = lema()->cache->cache( $cacheName, function () use ( $name ) {
			return get_terms( $name );
		} );
		$change    = isset( $_GET['change'] ) ? $_GET['change'] : '';
		if ( preg_match( '/^courseFilter\[(.*?)\]/', $change, $match ) ) {
			$change = $match[1];

		}
		$termIds = [];
		$taxs    = [];

		foreach ( $_GET as $key => $value ) {
			if ( array_key_exists( $key, $this->defaultTerms ) ) {
				$taxs[] = $key;

				if ( ! isset( $termIds[ $key ] ) ) {
					$termIds[ $key ] = [];
				}

				$list_value = explode( ',', $value );
				if ( $list_value ) {
					foreach ( $list_value as $v ) {
						$termIds[ $key ][] = $v;
					}
				}
			}
		}
		foreach ( $terms as &$term ) {
			$_termIds = $termIds;
			/** @var \WP_Term $term */

			$args              = [
				'post_type' => $this->handlePostType(),
			];
			$args['tax_query'] = [ 'relation' => 'AND' ];
			if ( $term->taxonomy == $change ) {
				continue;
			} else {
				$_termIds[ $term->taxonomy ]   = [];
				$_termIds[ $term->taxonomy ][] = $term->term_id;
			}

			if ( ! empty( $_termIds ) ) {
				foreach ( $_termIds as $tax => $ids ) {
					$query               = array(
						'taxonomy'         => $tax,
						'field'            => 'term_id',
						'terms'            => $ids,
						'include_children' => false,
						'operator'         => 'IN'
					);
					$args['tax_query'][] = $query;
				}
				if ( isset( $_GET['q'] ) && ! empty( $_GET['q'] ) ) {
					$args['s'] = $_GET['q'];
				}
				$args['post_status'] = 'publish';
				$result              = new \WP_Query( $args );
				$term->count         = $result->found_posts;
			}
		}
		lema()->cache->set( $cacheName, $terms );

		return $terms;
	}

	/**
	 * @return array|mixed
	 */
	public function getFilters() {
		$filters = [];

		foreach ( $this->defaultTerms as $term => $label ) {
			$filters[ $term ] = [
				'label'   => $label,
				'type'    => 'term',
				'options' => $this->filteredTerm( $term )
			];
		}
		$this->filters = lema()->hook->registerFilter( 'lema_search_filters', $filters );

		return $this->filters;
	}

	/**
	 * do shortcode filter*/
	public function doShortcodeFilter( $data ) {
		$attrs = [];
		foreach ( $data as $key => $value ) {
			$attrs[] = " {$key}=\"" . ( is_array( $value ) ? implode( ',', array_keys( $value ) ) : $value ) . "\"";
		}
		$attrs  = str_replace( array( '[', ']' ), '', implode( ' ', $attrs ) );
		$config = "summary='1' cols_on_row='3' posts_per_page='6' layout='grid' ";
		switch ( $this->handlePostType() ) {
			case BundleModel::POST_TYPE:
				echo lema_do_shortcode( "[lema_bundlelist_filtered {$config} {$attrs}]" );
				if ( ! is_ajax() ) {
					echo '<div style="display:none">
				' . lema_do_shortcode( "[lema_courselist_filtered]" ) . '</div>';
				}
				break;
			default:
				echo lema_do_shortcode( "[lema_courselist_filtered {$config} {$attrs}]" );
				if ( ! is_ajax() ) {
					echo '<div style="display:none">
				' . lema_do_shortcode( "[lema_bundlelist_filtered]" ) . '</div>';
				}
				break;
		}
	}

	/**
	 * Get full content of this shortcode
	 * @return string
	 */
	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		if ( ! is_array( $data ) ) {
			$data = [];
		}
		$data = array_merge( $this->getAttributes(), $data, $_GET );

		return $this->render( $this->contentView, array(
			'maxItems' => self::MAX_ITEMS_SHOW,
			'data'     => $data,
		), true, $key );
	}

	/**
	 * Apply filters and response list of course results
	 */
	public function applyFilter() {

		$data     = [
			'data'     => array_merge( $this->getAttributes(), $_GET ),
			'maxItems' => self::MAX_ITEMS_SHOW,
		];
		$res_html = $this->render( '_result', $data, true );

		$filterHtml = $this->render( '_filter', $data, true );

		$this->responseJson( [
			'data' => $filterHtml . $res_html,

		] );

	}

	/**
	 * get list action register
	 * @return array
	 */
	public function actions() {
		return [
			'ajax' => [
				'lema_apply_course_filter_sc' => [ $this, 'applyFilter' ],
			],
		];
	}
}