<?php
namespace lema\admin\controllers;


use lema\core\interfaces\AdminControllerInterface;

class PermissionController extends \lema\core\Controller implements AdminControllerInterface {
	public static function registerAction() {
		add_action( 'admin_init', [ self::getInstance(), 'redirect_lemaUser' ] );

		return [ ];
	}


	public static function redirect_lemaUser() {
		if ( is_user_logged_in() && ! wp_doing_ajax() ) {

			$userID        = _wp_get_current_user()->ID;
			$userRole      = _wp_get_current_user()->roles;
			$listLemaRoles = lema()->config->roleManager['roles'];
			foreach ( $userRole as $key => $value ) {
				if ( isset( $listLemaRoles[ $value ] ) ) {

					$func = $value . '_profile_url';
					wp_redirect( $func( $userID ) );
					die;
				}
			}
		}
	}
}