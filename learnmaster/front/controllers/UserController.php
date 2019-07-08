<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\front\controllers;

use lema\core\RuntimeException;
use lema\models\Instructor;
use lema\models\UserModel;
use lema\core\components\Style;
use lema\core\components\Script;
use lema\core\interfaces\FrontControllerInterface;


class UserController extends FrontController implements FrontControllerInterface {
	public function profilePage() {
		return $this->checkLogin( 'user-profile' );
	}

	public function checkLogin( $template ) {
		if ( lema()->wp->is_user_logged_in() ) {
			$user = wp_get_current_user();

			return $this->render( $template, [ 'user' => $user ] );
		} else {
			if ( class_exists( 'WooCommerce' ) && get_option( 'woocommerce_myaccount_page_id' ) ) {
				wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
			} else {
				echo '<div class="container"><div class="user-login-wrapper"><a class="lema-btn lema-btn-primary" href="' . wp_login_url() . '">' . __( "Login", "lema" ) . '</a></div></div>';
			}

			return '';
		}
	}

	/**
	 * Edit user info
	 */
	public function editProfilePage() {
		return $this->checkLogin( 'edit-profile' );
	}

	/**
	 * Update user profile
	 * @return bool
	 */
	public function updateProfile() {
		$user_id = wp_get_current_user()->ID;
		$res     = [];
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( count( $_POST ) ) {
			$list_posts = [];

			//check if user have changed password
			if ( isset( $_POST['password'] ) ) {
				$user = get_userdata( $user_id );
				if ( $user && wp_check_password( $_POST['password']['old'], $user->user_pass, $user_id ) ) {
					if ( $_POST['password']['new'] == $_POST['password']['confirm'] ) {
						//$res['message'] = 'Success change password !';
						$new_pass = $_POST['password']['new'];
						wp_set_password( $new_pass, $user_id );

					} else {
						$res['message'] = 'Password confirm invalid !';
					}
				} else {
					$res['message'] = 'Old password invalid !';
				}
				unset( $_POST['password'] );
			}

			// edit meta user
			if ( isset( $_POST['meta'] ) && count( $_POST['meta'] ) ) {
				foreach ( $_POST['meta'] as $key => $meta ) {
					update_user_meta( $user_id, $key, $meta );
				}
				unset( $_POST['meta'] );
			}

			if ( isset( $_POST ) ) {

				if ( isset( $_POST['first_name'] ) && $_POST['first_name'] !== '' ) {
					$list_posts['display_name'] = $_POST['first_name'];
				}

				if ( isset( $_POST['last_name'] ) && $_POST['last_name'] !== '' ) {
					$list_posts['display_name'] .= $_POST['last_name'];
				}

				foreach ( $_POST as $key => $post ) {
					$list_posts[ $key ] = esc_attr( $post );
				}
				$list_posts['ID'] = $user_id;
				wp_update_user( $list_posts );
			}

			return $this->responseJson( $res );
		}
	}

	public function listInfo() {
		return [
			'first_name' => 'First Name',
			'last_name'  => 'Last Name',
		];
	}

	public function showList() {
		return [
			[
				'title' => 'Enrolled',
				'name'  => 'enrolled',
			],
			[
				'title' => 'Bookmarked',
				'name'  => 'bookmarked',
			],
		];
	}

	/**
	 * Register all actions that controller want to hook
	 * @return mixed
	 */

	public static function registerAction() {
		return [
			'pages'  => [
				'front' => [
					lema()->config->getUrlConfigs( 'lema_user_profile' )      => [
						'profilePage',
						[ 'title' => __( 'Learn master - User Profile', 'lema' ) ]
					],
					lema()->config->getUrlConfigs( 'lema_user_edit_profile' ) => [
						'editProfilePage',
						[ 'title' => __( 'Learn master - Edit User Profile', 'lema' ) ]
					],
				]
			],
			'assets' => [
				'css' => [
					[
						'id'           => 'lema-user-profile',
						'isInline'     => false,
						'url'          => '/front/assets/css/lema-user-profile.css',
						'dependencies' => [ 'lema-style', 'font-awesome' ]
					],
					[
						'id'       => 'category',
						'isInline' => false,
						'url'      => '/front/assets/css/category.css'
					],
					/* [
						 'id' => 'temp',
						 'isInline' => false,
						 'url'   => '/front/assets/css/temp.css'
					 ]*/
				],
				'js'  => [
					[
						'id'       => 'lema-main-js',
						'isInline' => false,
						'url'      => '/front/assets/js/main.js',
					]
				]
			],
			'ajax'   => [
				'ajax_update_profile' => [ self::getInstance(), 'updateProfile' ],
			]
		];
	}

}