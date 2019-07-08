<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\models;


use lema\core\BaseObject;
use lema\core\components\RoleManager;
use lema\core\RuntimeException;

class UserModel extends BaseObject {
	/**
	 * @return string Profile url
	 */
	public function getProfileUrl() {
		return site_url();
	}

	/**
	 * @return array
	 */
	public static function listSocial() {
		$listSocial = [
			'facebook'  => [ 'title' => 'Facebook', 'icon' => 'fa-facebook' ],
			'twitter'   => [ 'title' => 'Twitter', 'icon' => 'fa-twitter' ],
			'instagram' => [ 'title' => 'Instagram', 'icon' => 'fa-instagram' ],
			'google'    => [ 'title' => 'Google', 'icon' => 'fa-google' ],
		];
		$listSocial = lema()->wp->apply_filters( 'lema-pre-get-list-social', $listSocial );

		return $listSocial;
	}

	/**
	 * @return array
	 */
	public static function listSkills() {
		$listSkills = [
			'manager'              => [ 'title' => 'Manager' ],
			'communication'        => [ 'title' => 'Communication' ],
			'personal_brand'       => [ 'title' => 'Build personal brand' ],
			'strategy_development' => [ 'title' => 'Strategy development' ],
			'creative'             => [ 'title' => 'Creative' ],
		];
		$listSkills = lema()->wp->apply_filters( 'lema-pre-get-list-skills', $listSkills );

		return $listSkills;
	}

	public static function listMeta() {
		return [
			'job_title' => 'Job title',
		];
	}

	/**
	 * Get author link
	 * if this is instructor return to instructor profile
	 * either return student profile
	 *
	 * @param $link
	 * @param $authorId
	 *
	 * @return mixed
	 */
	public function authorLink( $link, $authorId ) {
		/** @var \WP_User $user */
		$user  = get_user_by( 'ID', $authorId );
		$roles = ! empty( $user->roles ) ? $user->roles : [];
		if ( in_array( 'lema_instructor', $roles ) ) {
			$instructor = new Instructor( $user );
			$link       = $instructor->getProfileUrl();
		} else if ( in_array( 'lema_student', $roles ) ) {
			$student = new Student( $user );
			$link    = $student->getProfileUrl();
		}

		return $link;
	}

	/**
	 * @param $redirect_to
	 * @param $request
	 * @param $user
	 *
	 * @return bool
	 */
	public function gotoProfile( $redirect_to, $user ) {
		if ( empty( $redirect_to ) || $redirect_to == '/' || get_admin_url() == $redirect_to || $redirect_to == site_url() ) {
			/** @var \WP_User $user */
			if ( isset( $user->roles ) && is_array( $user->roles ) ) {
				if ( in_array( 'lema_instructor', $user->roles ) ) {
					$instructor = new Instructor( $user );

					return $instructor->getProfileUrl();
				} else if ( in_array( 'lema_student', $user->roles ) ) {
					$student = new Student( $user );

					return $student->getProfileUrl();
				}
			}
		}

		return $redirect_to;
	}
	/**
	 * @param $redirect_to
	 * @param $request
	 * @param $user
	 *
	 * @return bool
	 */
	public static function gotoProfileStatic( $redirect_to,\WP_User $user ) {
		if ( empty( $redirect_to ) || $redirect_to == '/' || get_admin_url() == $redirect_to || $redirect_to == site_url() ) {
			/** @var \WP_User $user */
			if ( isset( $user->roles ) && is_array( $user->roles ) ) {
				if ( in_array( 'lema_instructor', $user->roles ) ) {
					$instructor = new Instructor( $user );

					return $instructor->getProfileUrl();
				} else if ( in_array( 'lema_student', $user->roles ) ) {
					$student = new Student( $user );

					return $student->getProfileUrl();
				}
			}
		}

		return $redirect_to;
	}

	public function redirectLoginUrl( $login_url, $redirect, $force_reauth ) {

		$login_url = site_url( lema()->config->getUrlConfigs( 'lema_login' ), 'login' );
		if ( ! empty( $redirect ) ) {
			$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
		}

		if ( $force_reauth ) {
			$login_url = add_query_arg( 'reauth', '1', $login_url );
		}

		return $login_url;
	}

	public function redirectLogout() {
		wp_redirect( home_url() );
		exit();
	}

	public function showAdminBar() {
		$user          = wp_get_current_user();
		$allowed_roles = array( 'lema_instructor', 'lema_student' );
		if ( empty( $user->ID ) || array_intersect( $allowed_roles, $user->roles ) ) {
			return false;
		}

		return true;
	}
}