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

use lema\extensions\data\DataExtension;
use lema\core\components\Style;
use lema\core\components\Script;


class BundleCatController extends FrontController implements ControllerInterface
{

    public function renderCategoryDetail()
    {
        global $term;
        if (!is_object($term)) {
            $term = get_queried_object();
        }
        return $this->render('index', [
            'term' => $term
        ]);
    }

	public function getBundleOfCat(\WP_Term $term){
		$args = array(
			'post_type' => 'lema_bundle',
			'tax_query' => array(
				array(
					'taxonomy' => 'cat_bundle',
					'field' => 'term_id',
					'terms' => $term->term_id,
				)
			)
		);
		$query = new \WP_Query( $args );
		return $query->posts;
	}



    public static function registerAction()
    {
        return [
            'actions' => [

            ],
            'assets' => [
	            'css' => [
		            [
			            'id' => 'lema-bundle-detail',
			            'url' => '/front/assets/css/bundle-detail.css',
			            'dependencies' => []
		            ]
	            ],
	            'js' => [
		            [
			            'id' => 'lema-bundle-detail-script',
			            'url' => '/front/assets/js/bundle-detail.js',
			            'dependencies' => ['sticky']
		            ]
	            ]
            ]
        ];
    }

}