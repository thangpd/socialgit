<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\core\components;

use lema\core\BaseObject;
use lema\core\interfaces\ComponentInterface;
use lema\core\interfaces\MigrableInterface;


class RoleManager extends BaseObject implements ComponentInterface, MigrableInterface
{
    const LEMA_ROLE_DEFAULT = 'lema_role_default';
    const LEMA_CAP_DEFAULT = 'lema_cap_default';
    const LEMA_CAP_COURSE = 'lema_course';
	const LEMA_CAP_SETTING='lema_setting';
	const LEMA_CAP_ORDER='lema_order';

	/**
     * List of available capabilities
     * Currently load from config file
     * @var array
     */

    protected $capabilities = [];
    /**
     * List of available learn master roles
     * @var array
     */
    protected $roles = [];



    /**
     * RoleManager constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->capabilities = lema()->config->get('roleManager/capabilities', []);
        $this->capabilities = lema()->hook->registerFilter(Hook::LEMA_AUTH_CAPS, $this->capabilities);
        $this->roles = lema()->config->get('roleManager/roles', []);
        $this->roles = lema()->hook->registerFilter(Hook::LEMA_AUTH_ROLES, $this->roles);
    }

    /**
     * Check current user access right
     * @return bool
     */
    public function checkAccessRight()
    {
        $user = lema()->wp->wp_get_current_user();
        if ( in_array( self::LEMA_ROLE_DEFAULT, (array) $user->roles ) ) {
            return true;
        }
        return false;
    }

    /**
     * Run this function when plugin was activated
     * We need create something like data table, data roles, caps etc..
     * @return mixed
     */
    public function onActivate()
    {
        foreach ($this->roles as $name => $role) {
            if (get_role($name) == null) {
                lema()->wp->add_role($name, $role['label'], $role['capabilities']);
            }
        }

        $admin_role = get_role( 'administrator' );
	    foreach ( $this->capabilities as $cap ) {
		    if ( !$admin_role->has_cap( $cap ) ) {
			    $admin_role->add_cap( $cap );
		    }
	    }
    }

    /**
     * Run this function when plugin was deactivated
     * We need clear all things when we leave.
     * Please be a polite man!
     * @return mixed
     */
    public function onDeactivate()
    {
        // TODO: Implement onDeactive() method.
        foreach ($this->roles as $name => $role) {
            lema()->wp->remove_role($name);
        }

	    $adminrole = get_role( 'administrator' );
	    foreach ( $this->capabilities as $cap ) {
		    if ( $adminrole->has_cap( $cap ) ) {
			    $adminrole->remove_cap( $cap );
		    }
	    }
    }

    /**
     * Run if current version need to be upgraded
     * @param string $currentVersion
     * @return mixed
     */
    public function onUpgrade($currentVersion)
    {
        // TODO: Implement onUpgrade() method.
    }

    /**
     * Run when learn master was uninstalled
     * @return mixed
     */
    public function onUninstall()
    {

    }
}

