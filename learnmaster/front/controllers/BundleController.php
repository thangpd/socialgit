<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\front\controllers;


use lema\core\interfaces\ControllerInterface;

use lema\models\BundleModel;
use lema\models\ChapterModel;
use lema\models\CourseModel;


class BundleController extends FrontController implements ControllerInterface {

	public function __construct( array $config = [] ) {
		parent::__construct( $config );
	}


	/**
	 * Show details of bundle
	 */
	public function bundleDetail() {
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				global $post;
				/** @var BundleModel $model */
				$model = BundleModel::findOne( $post->ID );

				/*$postModified = $post->post_modified;
				$dateFormat = get_option('date_format');
				$postModified = date($dateFormat, strtotime($postModified));
				$category = $model->getCategory('name');

				$dataAuthor = get_userdata($post->post_author);
				$urlVideo = $model->getVideoUrl();

				$listCurriculum = $model->getCurriculum();
				*/

				return $this->render( 'detail', [
					'model' => $model,
					'post'  => $post
				] );
			}
		}
		wp_reset_query();

	}

	/**
	 * Register all actions that controller want to hook
	 * @return mixed
	 */
	public static function registerAction() {
		return [
			/*'actions' => [
				'template_redirect'  =>  [self::getInstance(), 'setup'],
			],*/
			'assets' => [
				'css' => [
					[
						'id'           => 'lema-course-style',
						'url'          => '/front/assets/css/style.css',
						'dependencies' => [ 'lema-custom' ]
					],
					[ 'id' => 'lema-custom' ],
					[
						'id'           => 'lema-bundle-detail',
						'url'          => '/front/assets/css/bundle-detail.css',
						'dependencies' => []
					]
				],
				'js'  => [
					[
						'id'           => 'lema-bundle-detail-script',
						'url'          => '/front/assets/js/bundle-detail.js',
						'dependencies' => []
					],
					[
						'id'           => 'sticky',
						'url'          => '/front/assets/libs/sticky/jquery.sticky.js',
						'dependencies' => []
					]
				]
			]
		];
	}

}