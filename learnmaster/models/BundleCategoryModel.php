<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */

namespace lema\models;

use lema\admin\controllers\BundleController;
use lema\core\interfaces\BundleCategoryInterface;
use lema\core\Model;

class BundleCategoryModel extends Model implements BundleCategoryInterface {


	const BUNDLECAT_SLUG = 'cat_bundle';

	/**
	 * Get Custom Meta Data
	 *
	 * @return array
	 */
	public function getAttributes() {
		return array(
			'is_feature'   => [
				'label' => esc_html__( 'Is Feature?', 'lema' ),
				'form'  => [
					'label'       => esc_html__( 'Feature?', LEMA_NAMESPACE ),
					'description' => esc_html__( 'Choose if this category is feature category.', LEMA_NAMESPACE ),
					'type'        => 'checkbox',
					'class'       => 'field-category',
					'name'        => 'is_feature',
					'value'       => true,
					'checked'     => false,
				],
			],
			'icon_class'   => [
				'label' => esc_html__( 'Icon', LEMA_NAMESPACE ),
				'form'  => [
					'label'       => esc_html__( 'Icon Class', LEMA_NAMESPACE ),
					'description' => esc_html__( 'Enter icon class for this category. Ex: \'fa fa-user\'', LEMA_NAMESPACE ),
					'type'        => 'text',
					'class'       => 'field-category',
					'name'        => 'icon_class',
				],
			],
			'attach_image' => [
				'label' => esc_html__( 'Attach Image', LEMA_NAMESPACE ),
				'form'  => [
					'label' => esc_html__( 'Attach Image', LEMA_NAMESPACE ),
					'type'  => 'custom',
//					'renderer' => [ BundleController::getInstance(), 'renderImageField' ],
					'class' => 'field-category',
					'name'  => 'attach_image',
				]
			]
		);
	}

	/**
	 * Abstract function get name of table/model
	 * @return mixed
	 */
	public function getName() {
		return self::BUNDLECAT_SLUG;
	}

	/**
	 * Save object properties to database
	 * @return boolean
	 */
	public function save() {
	}

	/**
	 * Delete a object by primary key
	 *
	 *
	 * @return boolean
	 */
	public function delete() {
		// TODO: Implement delete() method.
	}


	/**
	 * @return mixed
	 */
	public static function getPosttypeConfig() {
		//taxonomy Cat
		$labels = array(
			'name'              => _x( 'Categories', 'taxonomy general name', 'lema' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'lema' ),
			'search_items'      => __( 'Search Bundle Category', 'lema' ),
			'all_items'         => __( 'All Categories', 'lema' ),
			'parent_item'       => __( 'Parent Category', 'lema' ),
			'parent_item_colon' => __( 'Parent Category :', 'lema' ),
			'edit_item'         => __( 'Edit Category', 'lema' ),
			'update_item'       => __( 'Update Category', 'lema' ),
			'add_new_item'      => __( 'Add New Category', 'lema' ),
			'new_item_name'     => __( 'New Category Name', 'lema' ),
			'menu_name'         => __( 'Categories', 'lema' ),
		);

		return [
			'taxonomy' => [
				'name'        => 'cat_bundle',
				'object_type' => [ 'lema_bundle' ],
				'args'        => [
					'labels'            => $labels,
					'hierarchical'      => true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_menu'      => true,
					'show_in_nav_menus' => true,
					'query_var'         => true,
				]
			],

		];
	}

	/**
	 * Delete a object by primary key
	 *
	 *
	 * @return boolean
	 */

	/**
	 * Get Data
	 *
	 * @param array $attrs
	 *
	 * @return object
	 */
	public function getData( $attrs = [] ) {
		$data = [];
		if ( ! empty( $this->post ) ) {
			if ( empty( $attrs ) ) {
//				$attrs = $this->attributes;
				$attrs = $this->getAttributes();
			}
			foreach ( $attrs as $attr => $params ) {
				$vAttr = get_term_meta( $this->post->term_id, $attr, true );
				if ( ! is_string( $vAttr ) ) {
					$vAttr = json_encode( $vAttr );
				}
				$this->{$attr} = $vAttr;
			}
		}

		return (object) $data;
	}

	/**
	 * @param $id
	 *
	 * @return bool|Model
	 */
	public static function findOne( $id ) {
		$post = get_term( $id, 'cat_bundle' );
		if ( ! empty( $post ) ) {
			$modelClass = self::className();
			/** @var Model $model */
			$model       = new $modelClass();
			$model->post = $post;
			$model->getData();

			return $model;
		}

		return false;
	}

	/**
	 * Get Category by Bundle ID
	 *
	 * @param $post_id
	 *
	 * @return array|false|\WP_Error
	 */
	public function getCategoryBundle( $post_id ) {
		$cat_name   = self::getName();
		$categories = get_the_terms( $post_id, $cat_name );

		return $categories;
	}

	/**
	 * Get Bundle Category By ID
	 *
	 * @param $id
	 *
	 * @return bool|ModelInterface|Model|null
	 */
	public function get( $id ) {
		$category = null;
		$term     = get_term( $id );
		if ( isset( $term ) && ! is_wp_error( $term ) ) {
			$category = self::findOne( $term );
		}

		return $category;
	}

	/**
	 * Get all Bundle Category
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public function getAll( $args = array() ) {
		$args = array_merge(
			array(
				'taxonomy'   => $this->getName(),
				'hide_empty' => true,
			),
			$args
		);

		$categories = array();
		$terms      = get_terms( $args );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories[] = self::findOne( $term->term_id );
			}
		}

		return $categories;
	}

	/**
	 * Get all Children Category
	 *
	 * @param null $parent_id
	 *
	 * @return array
	 */
	public function getChildren( $parent_id = null ) {
		if ( ! isset( $parent_id ) || ! is_int( $parent_id ) ) {
			$parent_id = $this->term_id;
		}

		return $this->getAll( array( 'parent' => $parent_id ) );
	}

	/**
	 * Check if category has child
	 *
	 * @param null $parent_id
	 *
	 * @return bool
	 */
	public function hasChildren( $parent_id = null ) {
		if ( ! isset( $parent_id ) || ! is_int( $parent_id ) ) {
			$parent_id = $this->term_id;
		}
		$childs = $this->getChildren( $parent_id );

		return ! empty( $childs );
	}

	/**
	 * Get bundle category permalink
	 *
	 * @return string|\WP_Error
	 */
	public function permalink() {
		$link = get_term_link( $this->term_id );
		if ( is_wp_error( $link ) ) {
			$link = null;
		}

		return $link;
	}

	/**
	 * Get Bundle Category Icon
	 *
	 * @param null $default
	 * @param bool $html
	 *
	 * @return mixed|null|string
	 */
	public function icon( $default = null, $html = false ) {
		$icon_class = $this->icon_class;
		$icon_class = apply_filters( 'lema_bundle_category_get_icon_class', $this->term_id, $icon_class );
		if ( empty( $icon_class ) && ! empty( $default ) ) {
			$icon_class = $default;
		}
		$icon = $icon_class;
		if ( ! empty( $icon_class ) && $html ) {
			$icon = sprintf( '<i class="%s"></i>', $icon_class );
		}

		return $icon;
	}

	/**
	 * Check Bundle Category is featured
	 *
	 * @return bool
	 */
	public function is_feature() {
		$is_feature = apply_filters( 'lema_bundle_category_is_feature', $this->term_id, $this->is_feature );

		return ! empty( $is_feature );
	}

	/**
	 * Get list of bundle related to this category
	 *
	 * @return bool
	 */
	public function getBundles() {
		$bundles = false;
		if ( isset( $this->term_id ) && is_int( $this->term_id ) ) {
			$bundle_model = BundleModel::getInstance();
			$bundles      = $bundle_model->getAll( array(
				'tax_query' => array(
					array(
						'taxonomy'         => $this->getName(),
						'field'            => 'id',
						'terms'            => $this->term_id,
						'include_children' => true, // get child post
					)
				),
			) );
		}

		return $bundles;
	}

	/**
	 * After Save
	 *
	 * @param $postId
	 *
	 * @return mixed
	 */
	public function afterSave( $term_id, $post = null, $update = false ) {
		parent::afterSave( $term_id );
		if ( $term_id ) {
			lema()->session->currentBundleCategory = $term_id;
			lema()->wp->do_action( 'after_bundle_category_save', $term_id );
		}
	}

	public function attach_image() {
		$attachment_id = apply_filters( 'lema_bundle_category_attach_image', $this->attach_image );

		return $attachment_id;
	}


}