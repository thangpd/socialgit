<?php
/**
 * @project  Learn Master Plugin
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  9/7/17.
 */


namespace lema\extensions\manager;


use lema\core\Extension;
use lema\core\interfaces\ExtensionInterface;

class ManagerExtension extends Extension implements ExtensionInterface
{
    const EXTENSION_ID          = 'manager';
    const VERSION               = '1.0.0';

    private $config = [];

    private $edu_type = 'default';
    private $edu_version = '1.0.0';
    /**
     * Start Learn master extension
     * @return mixed
     */
    public function run()
    {
        $this->edu_type = get_option('setup_demo_id', 'default');
        $this->edu_version = get_option('education_version', '1.0.0');
        lema()->hook->listenFilter('lema_admin_setting_tabs', [$this, 'registerAdminTab']);
        $this->config = require_once (dirname(__FILE__) . '/config.php');
    }

    /**
     * @param $tabs
     * @return mixed
     */
    public function registerAdminTab($tabs) {
        $tabs['extension'] = [
            'label' => __('Extension', 'lema'),
            'renderer' => [$this, 'extensionManager']
        ];
        return $tabs;
    }

    /**
     * @return mixed|string
     */
    public function extensionManager()
    {
        wp_enqueue_style('lema-extension-manager', lema()->pluginManager->getUrl($this) . '/assets/styles/lema-extension-manager.css');
        /** @var ExtensionRepository $repository */
        $repository = ExtensionRepository::getInstance($this->config);
        $extensions = $repository->getAvailableExtensions("plugins/all?type={$this->edu_type}&version={$this->edu_version}");
        return $this->render('extension-list', [
            'extensions' => $extensions
        ]);
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
    }

    /**
     * Get current version of extension
     * @return mixed
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Automatic check update version
     * @return mixed
     */
    public function checkVersion()
    {
        // TODO: Implement checkVersion() method.
    }

    /**
     * Run this function when plugin was activated
     * We need create something like data table, data roles, caps etc..
     * @return mixed
     */
    public function onActivate()
    {
        // TODO: Implement onActivate() method.
    }

    /**
     * Run this function when plugin was deactivated
     * We need clear all things when we leave.
     * Please be a polite man!
     * @return mixed
     */
    public function onDeactivate()
    {
        // TODO: Implement onDeactivate() method.
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
        // TODO: Implement onUninstall() method.
    }

    /**
     * @return string
     */
    public function getId()
    {
        return self::EXTENSION_ID;
    }


    /**
     * @param $plugin
     * @return bool
     */
    public function checkPluginLicense($plugin)
    {
        $license = get_option("plugin_license_{$plugin}", false);
        if (!empty($license)) {
            /** @var ExtensionRepository $repository */
            $repository = ExtensionRepository::getInstance($this->config);
            $result = $repository->getPluginLicenseInfo($plugin, $license);
            if ($result && $result->code == 200) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $plugin
     * @param $version
     * @return bool
     */
    public function checkPluginUpdate($plugin, $version)
    {
        $license = get_option("plugin_license_{$plugin}", false);
        /** @var ExtensionRepository $repository */
        $repository = ExtensionRepository::getInstance($this->config);
        return $repository->checkPluginUpdate($plugin, $license, $version);
    }
}