<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\front\controllers;

use lema\admin\controllers\PermissionController;
use lema\core\RuntimeException;
use lema\models\UserModel;
use lema\core\components\Style;
use lema\core\components\Script;
use lema\core\interfaces\FrontControllerInterface;


class LoginController extends FrontController implements FrontControllerInterface {
	public function loginPage() {
		return $this->checkLogin( 'login' );
	}

	public function redirectUserLoggedIn() {
		$page_name = get_query_var( 'name' );
		if ( lema()->wp->is_user_logged_in() && $page_name == lema()->config->getUrlConfigs( 'lema_login' ) ) {
			PermissionController::redirect_lemaUser();
			if ( current_user_can( 'administrator' ) ) {
				wp_redirect( admin_url() );
				exit;
			} else {
				wp_redirect( home_url() );
				exit;
			}
		}
	}

	public function checkLogin( $template ) {
		if ( ! lema()->wp->is_user_logged_in() ) {
			if ( isset( $_GET['redirect_to'] ) && ! empty( $_GET['redirect_to'] ) ) {
				$redirect = $_GET['redirect_to'];
			} else {
				$redirect = home_url();
			}

			return $this->render( $template, [ 'redirect_to' => $redirect ] );
		}
	}


	/**
	 * Login - Register
	 */
// wp-admin/admin-ajax.php?action=lema_login
// admin_url('admin-ajax.php');
	public function lema_login() {
		$success     = false;
		$message     = esc_html__( 'Sorry we could not log you in. The credentials supplied were not recognised.', 'educef' );
		$redirect_to = '';
		if ( ! empty( $_POST ) ) {
			$creds = array(
				'user_login'    => $_POST['email'],
				'user_password' => $_POST['password'],
				'remember'      => false,
			);
			$redirect_to=$_POST['redirect_to'];
			$user  = wp_signon( $creds );
			if ( $user && is_user_member_of_blog( $user->ID, get_current_blog_id() ) && ! is_wp_error( $user ) ) {
				$success = true;
				$message = 'Login successful!';
			} else {
				$message = esc_html__( 'Your account is not correct !', 'educef' );
				wp_destroy_current_session();
				wp_clear_auth_cookie();
			}
		}
		header( 'Content-Type: application/json' );
		echo json_encode( compact( 'success', 'message', 'redirect_to' ) );
		die;
	}

// wp-admin/admin-ajax.php?action=lema_register
// admin_url('admin-ajax.php');
	public function lema_register() {
		$success = true;
		$message = $redirect_to = '';

		// Validation
		if ( empty( $_POST['email'] ) || empty( $_POST['display_name'] ) ) {
			$success = false;
			$message = esc_html__( 'Email and Display name are required !', 'educef' );
		}

		$enable_recaptcha         = slz_get_db_settings_option( 'recaptcha-picker/enable-recaptcha', 'disable' );
		$recaptcha_api_secret_key = slz_get_db_settings_option( 'recaptcha-picker/enable/recaptcha-api-secret-key', '' );
		if ( $success && $enable_recaptcha == 'enable' ) {
			if ( empty( $_POST['g-recaptcha-response'] ) ) {
				$success = false;
				$message = esc_html__( "Captcha not found !", 'educef' );
			} else {
				$res = wp_remote_get( "https://www.google.com/recaptcha/api/siteverify?secret=" . esc_attr( $recaptcha_api_secret_key ) . "&response=" . esc_attr( $_POST['g-recaptcha-response'] ) . "&remoteip=" . $_SERVER['REMOTE_ADDR'] );
				$res = json_decode( $res, true );
				if ( ! $res['success'] ) {
					$success = false;
					$message = $res['error-codes'][0];
				}
			}
		}

		if ( $success ) {
			$user_email   = esc_sql( $_POST['email'] );
			$display_name = esc_sql( $_POST['display_name'] );

			if ( email_exists( $user_email ) ) {
				$success = false;
				$message = esc_html__( 'Email already exists !', 'educef' );
			} else {
				$user_id = register_new_user( $user_email, $user_email );
				if ( $user_id && ! is_wp_error( $user_id ) ) {
					$user_data = array(
						'ID'           => $user_id,
						'role'         => 'lema_student',
						'display_name' => $display_name,
						'nickname'     => $display_name,
					);
					wp_update_user( $user_data );
					$message = esc_html__( "Your account was created ! Please confirm at email ", 'educef' ) . $user_email;
				} else {
					$success = false;
					$message = esc_html__( 'Can not create User !', 'educef' );
				}
			}
		}

		header( 'Content-Type: application/json' );
		echo json_encode( compact( 'success', 'message', 'redirect_to' ) );
		die;
	}


	/**
	 * Edit user info
	 */
	public function registerPage() {
		$this->updateProfile();

		return $this->checkLogin( 'register' );
	}

	/**
	 * Update user profile
	 * @return bool
	 */
	public function updateProfile() {
		$user_id = wp_get_current_user()->ID;

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( count( $_POST ) ) {
			$list_posts = [];

			//check if user have changed password
			foreach ( $_POST['password'] as $key => $pass ) {
				if ( $pass == '' ) {
					unset( $_POST['password'] );
					break;
				}
			}

			if ( isset( $_POST['password'] ) ) {
				$user = get_userdata( $user_id );
				if ( $user && wp_check_password( $_POST['password']['old'], $user->user_pass, $user_id ) ) {
					if ( $_POST['password']['new'] == $_POST['password']['confirm'] ) {
						wp_set_password( $_POST['password']['new'], $user_id );
					} else {
						$errorMess = 'Password confirm invalid !';
					}
				} else {
					$errorMess = 'Old password invalid !';
				}
				if ( isset( $errorMess ) ) {
					echo '<center class="text-danger"><h3><b>' . $errorMess . '</b></h3></center>';
				}
			}

			unset( $_POST['password'] );
			// end post password

			// edit meta user
			if ( count( $_POST['meta'] ) ) {
				foreach ( $_POST['meta'] as $key => $meta ) {
					update_user_meta( $user_id, $key, $meta );
				}
			}
			unset( $_POST['meta'] );

			$list_posts['display_name'] = $_POST['first_name'] . ' ' . $_POST['last_name'];

			foreach ( $_POST as $key => $post ) {
				$list_posts[ $key ] = esc_attr( $post );
			}
			$list_posts['ID'] = $user_id;
			wp_update_user( $list_posts );
		}
	}
	

	/**
	 * Register all actions that controller want to hook
	 * @return mixed
	 */
	public static function registerAction() {
		return [
			'actions' => [
				'template_redirect' => [ self::getInstance(), 'redirectUserLoggedIn' ],
			],
			'ajax' => [
				'lema_login' => [self::getInstance(), 'lema_login'],
				'lema_register' => [self::getInstance(), 'lema_register']
			],
			'pages'   => [
				'front' => [
					lema()->config->getUrlConfigs( 'lema_login' )    =>
						[
							'loginPage',
							[
								'title'  => __( 'Learn master - Login', 'lema' ),
								'single' => true
							]
						],
					lema()->config->getUrlConfigs( 'lema_register' ) =>
						[
							'registerPage',
							[ 'title' => __( 'Learn master - Register', 'lema' ) ]
						],
				]
			],
			'assets'  => [
				'css' => [
					[
						'id'           => 'lema-user-profile',
						'isInline'     => false,
						'url'          => '/front/assets/css/lema-user-profile.css',
						'dependencies' => [ 'lema-style', 'font-awesome' ]
					],
				],
				'js'  => [
					[
						'id'       => 'lema-main-js',
						'isInline' => false,
						'url'      => '/front/assets/js/main.js',
					],
					[
						'id'       => 'lema-login-page-js',
						'isInline' => false,
						'url'      => '/front/assets/js/login.js',
					]
				]
			]
		];
	}

}