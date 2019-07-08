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


use lema\core\Controller;
use lema\core\interfaces\ControllerInterface;

class CourseListShortcode extends Shortcode {

	const SHORTCODE_ID = 'lema_course_list';
	public $contentView = 'course-list';


	/**
	 * Get Id of shortcode
	 * @return string
	 */
	public function getId() {
		return self::SHORTCODE_ID;
	}

	public function getStatic() {
		return [
			[
				'type'         => 'script',
				'id'           => 'lema-course-list',
				'isInline'     => false,
				'url'          => 'assets/scripts/lema-course-list.js',
				'dependencies' => [ 'lema', 'lema.shortcode' ]
			],
			[
				'type'         => 'style',
				'id'           => 'lema-course-list-style',
				'isInline'     => false,
				'url'          => 'assets/styles/lema-course-list.css',
				'dependencies' => [ 'lema-shortcode-style' ]
			]
		];
	}

	/**
	 * return html for include content
	 *
	 * @param string $key
	 *
	 * @return mixed
	 *
	 */
	public function getFormatHtml( $key = '' ) {
		$format = array(
			'block'     => '<div {{data}}>%s</div>',
			'slide'     => '<div class="block-slide">%s</div>',
			'grid'      => '<div class="lema-row"><div class= "lema-course-all lema-columns lema-column-%2$s">%1$s</div></div>',
			'title'     => '<h3 class="course_list_title">%s</h3>',
			'paging'    => '<div class="clear"></div><br /><div class="center">%s</div>',
			'link_page' => '<button class="link_page" data-shortcode_ajax=' . $this->getId() . ' %2$s type="button">%1$s</button>',
			'default'   => '%1$s',
		);
		$format = apply_filters( 'lema_shortcode_custom_format_html', $format, $this->getId() );
		if ( array_key_exists( $key, $format ) ) {
			return $format[ $key ];
		}
		$format = lema()->wp->apply_filters( 'lema_shortcode_custom_format_html', $format, $this->getId() );

		return $format['default'];
	}

	/**
	 * Get array of the shortcode attributes
	 *
	 * @return array
	 */
	public function getAttributes() {
		return [
			'title'                  => '',
			'layout'                 => 'grid',
			'orderBy'                => '',
			'order'                  => '',
			'cols_on_row'            => 1,
			'posts_per_page'         => 10,
			'paging'                 => 1,
			'page_url'               => 0,
			'search_term'            => '',
			'filter_by_cat'          => '',
			'filter_levels'          => '',
			'filter_topics'          => '',
			'filter_languages'       => '',
			'show_pagination'        => 1,
			'show_course_list_title' => 1,
			'show_summary'           => 0,
			'data'                   => ''
		];
	}

	/**
	 * list action post ajax
	 * @return array
	 */
	public function actions() {
		return [
			'ajax' => [
				'ajax_get_shortcode' => [ $this, 'getShortcode' ]
			]
		];
	}

	/**
	 *  echo shortcode by params $_POST
	 */
	public function getShortcode() {
		$shortcode = '[' . $_POST['shortcode_ajax'];

		foreach ( $_POST as $key => $attr ) {
			$shortcode .= " " . $key . "='" . $attr . "'";
		}

		$shortcode   .= " ]";
		$res['html'] = lema_do_shortcode( $shortcode );
		echo json_encode( $res );
		die;
	}


	/**
	 * return attributes data for link
	 *
	 * @param object $data list params
	 * @param number $page number current page
	 *
	 * @return string
	 */
	public function getLinkPage( $data, $page ) {
		$link = '';
		foreach ( $data as $key => $attr ) {
			if ( $key == 'paging' ) {
				$attr = $page;
			}
			$link .= "data-" . $key . "='" . $attr . "' ";
		}
		if ( $data->paging == $page ) {
			$link .= "disabled ";
		}

		return $link;
	}

	/**
	 * return html pagination
	 *
	 * @param number $page current page
	 * @param number $total_pages total pages
	 * @param object $data list data params
	 *
	 * @return string
	 */
	public function listLink( $page, $total_pages, $data ) {
		$paging_html = '';

		switch ( $page ) {
			case 1:
				break;

			case $total_pages:
				$page = $page - 2;
				break;

			default:
				$page = $page - 1;
				break;
		}

		if ( $page > 1 ) {
			$link        = $this->getLinkPage( $data, 1 );
			$paging_html .= sprintf( $this->getFormatHtml( 'link_page' ), 'first', $link );
		}

		for ( $i = 0; $i < 3; $i ++ ) {
			$link        = $this->getLinkPage( $data, $page );
			$paging_html .= sprintf( $this->getFormatHtml( 'link_page' ), $page, $link );
			$page ++;
		}

		if ( $page > 1 && ( $page <= $total_pages ) ) {
			$link        = $this->getLinkPage( $data, $total_pages );
			$paging_html .= sprintf( $this->getFormatHtml( 'link_page' ), 'last', $link );
		}

		return $paging_html;
	}

	/**
	 * Return array of arguments for WP_Query usage
	 *
	 * @param Object $data attributes of the shortcode
	 *
	 * @return array
	 */
	public function getQuery( $data ) {
		return array( 'post_type' => 'course' );
	}


	public function getShortcodeContent( $data = [], $params = [], $key = '' ) {
		$block      = '';
		$title_html = '';
		if ( ! is_array( $params ) ) {
			$params = [];
		}
		if ( isset( $data['data'] ) && ! empty( $data['data'] ) ) {
			$lemaParams = json_decode( urldecode( $data['data'] ), true );
		} else {
			$lemaParams = $data;
		}
		$data                 = $this->getData( $data );
		$data['data']['data'] = $lemaParams;
		$data                 = (object) $data['data'];

		$args            = $this->getQuery( $data );
		$dataAttributes  = '';
		$keyAttribute    = '{{data}}';
		$arData['total'] = 0;
		if ( isset( $data->data ) ) {
			$dataAttributes = $data->data;
		}
		if ( $args ) {
			if ( $args === false ) {
				return '';
			}

			if ( ! empty( $data->search_term ) ) {
				$args['s'] = $data->search_term;
			}
			$args['posts_per_page'] = $data->posts_per_page;

			$args['paged'] = $data->paging;

			if ( $data->page_url ) {

				if ( ! empty( $_GET['paging'] ) && is_numeric( $_GET['paging'] ) ) {
					$args['paged'] = $_GET['paging'];
				} else if ( ! empty( $_POST['paging'] ) && is_numeric( $_POST['paging'] ) ) {
					$args['paged'] = $_POST['paging'];
				}
				$data->paging = $args['paged'];
			}
			if ( $data->filter_by_cat !== '' ) {

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'cat_course',
						'field'    => ( is_numeric( $data->filter_by_cat ) ) ? 'term_id' : 'slug',
						'terms'    => $data->filter_by_cat
					)
				);
			}
			$args['post_status'] = 'publish';


			$attr            = $this->getAttributes();
			$paramCourseCard = "";
			if ( ! is_array( $data->data ) ) {
				$data->data = [];
			}

			foreach ( $data->data as $key => $a ) {
				if ( ! isset( $attr[ $key ] ) ) {
					$paramCourseCard .= " {$key}='{$a}' ";
				}
			}

			$args         = apply_filters( 'lema_before_query_course_list', $args, $data->data );
			$query        = new \WP_Query( $args );
			$list_courses = $query->posts;


			$total_posts = $query->found_posts;
			if ( isset( $args['s'] ) && ! empty( $args['s'] ) ) {
				$terms = lema()->config->lema_search_terms;
				if ( empty( $terms ) ) {
					$terms = [];
				}
				$terms[ $args['s'] ]              = $total_posts;
				lema()->config->lema_search_terms = $terms;
			}
			foreach ( $list_courses as $key => $course ) {
				$attr = ' post_id="' . $course->ID . '"';
				if ( $data->layout == 'list' ) {
					$attr .= ' layout="layout-2"';
				}
				$attr  .= " " . $paramCourseCard;
				$block .= lema_do_shortcode( '[lema_course' . $attr . ']' );
			}
			if ( $data->title !== '' ) {
				$title_html = sprintf( $this->getFormatHtml( 'title' ), esc_html( $data->title ) );
			}

			if ( $data->layout == 'grid' OR $data->layout == 'list' ) {

				if ( ! ( $data->cols_on_row < 6 ) ) {
					$data->cols_on_row = 1;
				}

				$block = sprintf( $this->getFormatHtml( $data->layout ), $block, $data->cols_on_row );

				if ( $data->show_pagination == 1 ) {
					$paging_html = '';
					if ( empty( $data->posts_per_page ) ) {
						$data->posts_per_page = 10;
					}
					$total_pages = ceil( $total_posts / $data->posts_per_page );
					if ( $total_pages > 1 ) {
						if ( $total_pages < 6 ) {
							//add link to pagination
							for ( $i = 1; $i <= $total_pages; $i ++ ) {

								$list_attr = '';
								foreach ( $data as $key => $attr ) {

									if ( $key == 'paging' ) {
										$attr = $i;
									}
									if ( is_array( $attr ) ) {
										$attr = urlencode( json_encode( $attr ) );
									}
									$list_attr .= "data-" . $key . "='" . $attr . "' ";
								}

								// disable button current page
								if ( $data->paging == $i ) {
									$list_attr .= "disabled ";
								}

								$paging_html .= sprintf( $this->getFormatHtml( 'link_page' ), $i, $list_attr );
							}
						} else {
							$paging_html = $this->listLink( $data->paging, $total_pages, $data );
						}
					}
					$block .= sprintf( $this->getFormatHtml( 'paging' ), $paging_html );
				}

			} else {
				$block = sprintf( $this->getFormatHtml( $data->layout ), $block );
			}
			$block = sprintf( $this->getFormatHtml( 'block' ), $title_html . $block );

			if ( is_array( $dataAttributes ) ) {
				$dataAttributes = implode( ' ', $dataAttributes );
			}
			$block = str_replace( $keyAttribute, $dataAttributes, $block );
			$block = lema()->hook->registerFilter( 'lema_course_list_content', $block );
			if ( empty( $block ) ) {
				$block = __( 'No course found', 'lema' );
			}
			$arData['block'] = $block;
			$arData['total'] = $total_posts;

			return $this->render( $this->contentView, array_merge( $arData, $params ), true );
		}

		$block           = sprintf( $this->getFormatHtml( 'block' ), $title_html . $block );
		$block           = str_replace( $keyAttribute, $dataAttributes, $block );
		$block           = lema()->hook->registerFilter( 'lema_course_list_content', $block );
		$arData['block'] = $block;

		return $this->render( $this->contentView, array_merge( $arData, $params ), true );
	}
}