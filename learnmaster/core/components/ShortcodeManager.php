<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 * @since 1.0
 *
 */


namespace lema\core\components;

use lema\core\BaseObject;
use lema\core\interfaces\CacheableInterface;
use lema\core\interfaces\ComponentInterface;
use lema\core\interfaces\ResourceInterface;
use lema\core\interfaces\ShortcodeInterface;

use lema\core\RuntimeException;
use lema\core\Shortcode;
use lema\helpers\FileHelper;
use lema\helpers\GeneralHelper;
use function PHPSTORM_META\type;

class ShortcodeManager extends BaseObject implements ComponentInterface, CacheableInterface
{
    /**
     * @var  ShortcodeInterface[]
     */
    private $shortcodes = [];


    /**
     * Lazy load shortcodes
     * @var bool
     */
    public $lazyLoad = false;


    /**
     * ShortcodeManager constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        add_action('wp_enqueue_scripts', function(){
            wp_enqueue_script( 'lema.shortcode.footer',
                    plugins_url('learnmaster/assets/scripts/lema.shortcode.footer.js'),
                    [],
                    false,
                    true);
        }, PHP_INT_MAX);
    }

    /**
     * Initilial
     */
    public function init()
    {
        $this->lazyLoad = lema()->config->lazyShortcode;
        $this->collectShortcodes();
        $this->shortcodes = lema()->hook->registerFilter(Hook::LEMA_SHORTCODE_REGISTER, $this->shortcodes);
        foreach ($this->shortcodes as $shortcode) {
            $shortcode = lema()->hook->registerFilter(Hook::LEMA_SHORTCODE_EXTENDS, $shortcode);
            $shortcode = lema()->hook->registerFilter(Hook::LEMA_SHORTCODE_EXTENDS . '_' . $shortcode->getId(), $shortcode);
            $this->registerShortcode($shortcode);
        }
        $this->prepareAssetRelease();
        if ($this->lazyLoad) {
            lema()->wp->add_action('wp_ajax_get_shortcode_content', [$this, 'getShortcodeContent']);
            lema()->wp->add_action('wp_ajax_nopriv_get_shortcode_content', [$this, 'getShortcodeContent']);
        }
        $path = LEMA_PATH_PLUGIN . '/assets/styles/lema.shortcode.css';
        $path = str_replace('\\', '/', $path);
        wp_register_style('lema-shortcode-style', $path, ['lema-style']);
        lema()->hook->registerHook('lema-shortcode-ready', $this->shortcodes);
    }

    /**
     * @return mixed|void
     */
    public function getDisabledShortcode()
    {
        return apply_filters('lema_shortcodes_disabled', []);
    }

    /**
     * @ajax
     * Get shortcode content
     * @return string
     */
    public function getShortcodeContent()
    {
        $shortcodeId = $_POST['shortcode_id'];
        $shortcode = $this->getShortcodeById($shortcodeId);
        $data = [];
        if (isset($_POST['data']) && !empty($_POST['data'])) {
            $data = $_POST['data'];
        }
        if (!empty($shortcode)) {
            $html = $shortcode->getShortcodeContent($data);
            header('Content-Type: application/json');
            print json_encode([
                'html' => $html,
                'resources' => $shortcode->getResources()
            ]);
            exit;
        }
    }
    /**
     * @param $id
     * @return bool
     */
    public function isExists($id) {
        return isset($this->shortcodes[$id]);
    }

    /**
     * @param $id
     * @return ShortcodeInterface
     */
    public function getShortcodeById($id)
    {
        if ($this->isExists($id)) {
            return $this->shortcodes[$id];
        }
        return null;
    }

    /**
     * @return ShortcodeInterface[]
     */
    public function getRegisteredShortcodes()
    {
        return $this->shortcodes;
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @return bool
     */
    protected function checkShortcodeDependency(ShortcodeInterface $shortcode)
    {
        $deps = $shortcode->getDependencies();
        if (!empty($deps)) {
            foreach ($deps as $dep) {
                if (!$this->isExists($dep->getId())) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param ShortcodeInterface $shortcode
     * @throws RuntimeException
     */
    public function registerShortcode(ShortcodeInterface $shortcode) {
        if ($this->checkShortcodeDependency($shortcode)) {
            if ($this->lazyLoad) {
                lema()->wp->add_shortcode($shortcode->getId(), [
                    $shortcode, 'getLayout'
                ]);
            } else {
                if (LEMA_DEBUG || (defined('DOING_AJAX') && DOING_AJAX)) {
                    lema()->wp->add_shortcode($shortcode->getId(), [
                        $shortcode,  'getShortcodeContent'
                    ]);
                } else {
                    lema()->wp->add_shortcode($shortcode->getId(), [
                        $shortcode,  'findInCache'
                    ]);
                }
            }
            if (method_exists($shortcode, 'actions')) {
                $actions = $shortcode->actions();
                if (isset($actions['ajax'])) {
                    foreach ($actions['ajax'] as $action => $callback) {
                        if (lema()->wp->is_user_logged_in()) {
                            lema()->wp->add_action('wp_ajax_'. $action, $callback);
                        } else {
                            lema()->wp->add_action('wp_ajax_nopriv_'. $action, $callback);
                        }
                    }
                }
                if (isset($actions['hooks'])) {
                    foreach ($actions['hooks'] as $hook => $callback) {
                        lema()->wp->add_action($hook, $callback);
                    }
                }
            }
            if (!empty($shortcode->extras)) {
                if (isset($shortcode->extras['vc'])) {
                    if (function_exists('vc_map')) {
                        vc_map($shortcode->extras['vc']);
                    }
                }
            }

        } else {
            throw new RuntimeException(
                __('Can not resolve shortcode dependencies. Please make sure that you required registered shortcode. Rependencies : '
                    . implode(lema()->helpers->general->objectAttributeMap($shortcode->getDependencies(), 'id')))
            );
        }
    }



    /**
     * Collect all shortcode that registered run with learn master plugin
     * @return $this
     */
    protected function collectShortcodes()
    {

        $shortcodes = lema()->cache->get($this->getCahename(), []);
        if (empty($shortcodes) || LEMA_DEBUG) {
            $shortcodes = lema()->helpers->file->findAllClass('lema\\shortcodes', 'Shortcode', ShortcodeInterface::class);
            /**
             * Store shortcode list to cache
             */
            lema()->cache->set($this->getCahename(), $shortcodes, 3600);
        }
        foreach ($shortcodes as $shortcode) {
            /** @var ShortcodeInterface $shortcode */
            $shortcode = $shortcode::getInstance();
            $shortcode = $this->createShortcodeFromConfig($shortcode);
            if ($shortcode) {
                $this->shortcodes[$shortcode->getId()] = $shortcode;
            }
        }

        return $this;

    }

    /**
     * Create new shortcode from config file
     * @param ShortcodeInterface $shortcode
     * @param string $location
     * @return mixed
     * @throws RuntimeException
     */
    public function createShortcodeFromConfig(ShortcodeInterface $shortcode, $location = '') {
        $shortcodeId = $shortcode->getId();
        if (empty($shortcodeId)) {
            throw new RuntimeException(__("Shortcode {$shortcode::className()} missing shortcode id", 'lema'));
        }
        try {

            //$uri =  app()->wp->get_site_url;
            if (!empty($location)) {
               //TBD
            }
            return $shortcode;

        } catch (\Exception $e) {
            throw new RuntimeException(__('Can not create shortcode from config', 'lema'));
        }
    }

    /**
     * @return string
     */
    public function getCahename()
    {
        return "shortcodes";
    }

    /**
     * Flush owner cache to refresh data
     * @return mixed
     */
    public function flushCache()
    {
        lema()->cache->delete($this->getCahename());
    }

    /**
     * Validate config file of the shortcode
     * @param $config
     * @return bool
     */
    private function validateConfig($config) {
        $requiredAttrs = ['id', 'class', 'attributes'];
        foreach ($requiredAttrs as $attr) {
            if (!isset($config[$attr])) return false;
        }
        return true;
    }


    /**
     * Register shortcode's assets
     * if LEMA_DEBUG is true, just register each asset
     * if LEMA_DEBUG is false, that mean plugin running on prod env
     * so we minify all asset resources before register those to wordpress
     */
    private function prepareAssetRelease()
    {
        $scripts = $styles = [];
        foreach ($this->shortcodes as $shortcode) {
            foreach ($shortcode->getResources() as $resource) {
                /** @var ResourceInterface $resource */
                if ($resource instanceof Style) {
                    wp_register_style($resource->getId(), $resource->getUrl(), $resource->getDependencies());
                } else {
                    wp_register_script($resource->getId(), $resource->getUrl(), $resource->getDependencies());
                }
            }
        }
    }

    /**
     * Do shortcode via lema ways
     * @param $str
     * @return string
     */
    public function doShortcode($str)
    {
        $shortcodeIds = lema()->helpers->wp->getShortcodeIds($str);
        foreach ($shortcodeIds as $shortcodeId) {
            /** @var ShortcodeInterface $shortcode */
            $shortcode = $this->getShortcodeById($shortcodeId);
            if (!empty($shortcode)) {
                //lema()->resourceManager->releaseShortcodeAssets($shortcode);
                $resources = $shortcode->getResources();
                foreach ($resources as $resource) {
                    if ($resource instanceof Script) {
                        wp_enqueue_script($resource->getId());
                    } else {
                        wp_enqueue_style($resource->getId());
                    }
                }
            }

        }
        return do_shortcode($str);
    }
}
