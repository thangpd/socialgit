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
use lema\core\interfaces\ExtensionInterface;
use lema\core\interfaces\PluginInterface;


class PluginManager extends BaseObject implements ComponentInterface
{
    /**
     * List of built-in extensions
     * @var array
     */
    private $requiredExts = [
            'manager' => 'Extension manager',
            'paypal' => 'Paypal Extension',
            'localization' => 'Localization data',
            'support' => 'Support',
            'cache' => 'Asset cache'
    ];

    /**
     * @var ExtensionInterface
     */
    private $activatedExtensions = [];

    public function __construct($config = [])
    {
        parent::__construct($config);
       /* if (lema()->wp->is_admin()) {
            lema()->hook->listenHook('admin_init', [$this, 'checkRequiredExtensions']);
        } else {

        }*/
        $this->checkRequiredExtensions();
    }

    /**
     * @param ExtensionInterface $extension
     * @return mixed|string
     */
    public function getUrl($extension) {
        $url = LEMA_PATH_PLUGIN . '/extensions/' . $extension->getId();
        $url = lema()->hook->registerFilter('lema_extension_url_' . $extension->getId(), $url);
        return $url;
    }

    /**
     * Check and active required extension
     */
    public function checkRequiredExtensions()
    {
        $this->requiredExts = lema()->hook->registerFilter('lema_builtin_exts', $this->requiredExts);
        foreach ($this->requiredExts as $ext => $label) {
           $this->activateExtension($ext);
        }
    }

    /**
     * Activate an extension
     * @param mixed $ext extension object or extension' name
     * @return bool|mixed
     */
    public function activateExtension($ext)
    {
        /** @var ExtensionInterface $extension */
        $extension = null;
        if (is_object($ext) && $ext instanceof ExtensionInterface) {
            $extension = $ext;
        } else {
            try {
                $extName = "\\lema\\extensions\\$ext\\" . lema()->helpers->general->camelClassName($ext) . 'Extension';
                if (class_exists($extName)) {
                    $extension = new $extName();
                }
            } catch (\Exception $e) {
                //Error -> logs
            }
        }
        if (!empty($extension) && $extension instanceof ExtensionInterface) {
            $this->activatedExtensions[] = $extension;
            return $extension->run();
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|ExtensionInterface
     */
    public function getExtension($id) {
        foreach ($this->activatedExtensions as $extension) {
            /** @var ExtensionInterface $extension */
            if ($extension->getId() == $id) {
                return $extension;
            }
        }
        return  false;
    }
}
