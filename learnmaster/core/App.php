<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */



namespace lema\core;


use lema\core\components\Cache;
use lema\core\components\cart\Cart;
use lema\core\components\ControllerManager;
use lema\core\components\Cookie;
use lema\core\components\Hook;
use lema\core\components\Logger;
use lema\core\components\Mailer;
use lema\core\components\Migration;
use lema\core\components\ModelManager;
use lema\core\components\Page;
use lema\core\components\ResourceManager;
use lema\core\components\PluginManager;
use lema\core\components\RoleManager;
use lema\core\components\Session;
use lema\core\components\ShortcodeManager;
use lema\core\components\WP;
use lema\helpers\FileHelper;
use lema\helpers\GeneralHelper;
use lema\helpers\Helper;
use lema\helpers\WordpressHelper;


/**
 * @package lema\core
 * @project  Learn Master
 *
 *
 * @property Session $session
 * Session manager
 *
 * @property Cookie $cookie
 * Cookie manager
 *
 *
 * @property Cache $cache
 * The cache manager
 * By default, all caches of app stored in wp-content/uploads/lema/caches
 * Each object can be cache if implemented \lema\core\interfaces\CacheableInterface
 * @see Shortcode
 *
 * @property PluginManager $pluginManager
 * App owner plugin manager
 * Learn master may have many children plugins
 * This component can add/remove, enable/disable a plugin
 *
 * @property ShortcodeManager $shortcodeManager
 * Shortcode manager which managed all shortcodes from plugin and themes
 *
 * @property ResourceManager $resourceManager
 * Static resource management like styles and javascripts
 *
 * @property WP $wp
 * Magic class contains magic function __call
 * which used to call wordpress global function
 * don't worry about the message error "Call undefined function ..."
 * all called function will return false if not exists without trigger any errors
 *
 * @property Hook $hook
 * Hook manager
 * Register default hooks and allow other component to register or listen new hook
 *
 * @property Helper $helpers
 * The app supper man
 * It contains all you need to keep you don't waste your time
 * Helper contains :
 * - File helper @see FileHelper which contains all useful functions related to file system
 * - Wordpress helper @see WordpressHelper which contains unseful functions related to wordpress
 * - General helper @see GeneralHelper which contains...any things
 *
 * @property Page $page
 * Manage lema single page
 *
 *
 * @property ControllerManager $controller
 * Collect and register hook action for all controller classes
 * Each class need to be implemented \lama\core\interfaces\ControllerInterface
 *
 * @property ModelManager $model
 * Like controller manager
 * but model need to implements \lema\core\interfaces\ModelInterface
 *
 * @property RoleManager $role
 * Role management system
 * Simple role manager which manage who can access to a feature
 *
 *
 * @property Mailer $mailer
 * Learn master mailer component
 *
 *
 * @property Logger $logger
 * Lema logger based on Monolog
 *
 *
 * @property Migration $migration
 * Manage code and database version
 *
 *
 *
 *
 *
 *
 */


class App extends BaseObject
{
    /**
     * Singleton
     * Instance of application
     * @var App
     */
    private static $instance = null;

    /** @var  Client */
    public $request;

    /**
     * Current app configs
     * It was passed from config.php in Learn master plugin directory
     * @var  Config
     */
    public $config;

    private $ready = false;

    /**
     * App components
     * @var array
     */
    private $_components = [
        'session'           => '\lema\core\components\Session',
        'cookie'            => '\lema\core\components\Cookie',
        'wp'                => '\lema\core\components\WP',
        'helpers'           => '\lema\helpers\Helper',
        'migration'         => '\lema\core\components\Migration',
        'hook'              => '\lema\core\components\Hook',
        'page'              => '\lema\core\components\Page',
        'cache'             => '\lema\core\components\Cache',
        'role'              => '\lema\core\components\RoleManager',
        'model'             => '\lema\core\components\ModelManager',
        'pluginManager'     => '\lema\core\components\PluginManager',
        'shortcodeManager'  => '\lema\core\components\ShortcodeManager',
        'resourceManager'   => '\lema\core\components\ResourceManager',
        'controller'        => '\lema\core\components\ControllerManager',
        'logger'            => '\lema\core\components\Logger',
        'mailer'            => '\lema\core\components\Mailer',

    ];



    /**
     * App constructor.
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        /**
         * Register exception handler
         */
        set_exception_handler([$this, 'onException']);
        set_error_handler([$this, 'onError'], E_ERROR | E_CORE_ERROR | E_CORE_WARNING);
        $this->config = new Config($values);
        parent::__construct();
    }

    /**
     * Setup lema app components
     */
    public function __init()
    {
        do_action('lema_before_init');
        $this->setupComponents();
        $this->ready = true;
        do_action('lema_after_init');
        //$this->run();
    }

    /**
     * Setup app components
     *
     * @return void
     */
    protected function setupComponents()
    {
        foreach ($this->_components as $name => $handlerClass) {
            if (empty($this->$name)) {
                $this->$name =$handlerClass::getInstance([
                    'config' => $this->config
                ]);
            }
        }
    }

    /**
     * Start to run this app (plugin)
     */
    public function run()
    {

        lema()->hook->registerHook(Hook::LEMA_RUN, $this);
        Cart::restore();

        //Set .htaccess for lema (cache, log) folder
        $protectedDirs = ['caches', 'logs'];
        foreach ($protectedDirs as $dir) {
            $dirPath = LEMA_WR_DIR . DIRECTORY_SEPARATOR . $dir;
            if (is_dir($dirPath) && !file_exists($dirPath . DIRECTORY_SEPARATOR . '.htaccess')) {
                file_put_contents($dirPath . DIRECTORY_SEPARATOR . '.htaccess', 'deny from all');
            }
        }
        $this->checkVersion();

        /**
         * Unregister exception handler
         */
        restore_error_handler();

        define('LEMA_LOADED', true);
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        if (empty($this->logger)) {
            $this->logger = new Logger();
        }
        return $this->logger;
    }
    /**
     * @return Helper
     */
    public function getHelper()
    {
        if (empty($this->helpers)) {
            $this->helpers = new Helper();
        }
        return $this->helpers;
    }

    /**
     * @param $e
     * @throws \Exception
     */
    public function onException($e)
    {
        if ($e instanceof RuntimeException && LEMA_DEBUG == false) {
            try {
                $this->getLogger()->error($e->getMessage(),(array) $e->getTrace());
	            $errorViewPath = apply_filters( 'lema_error_view_path', LEMA_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'error.php' );
	            echo lema()->getHelper()->getHelper('general')->renderPhpFile( $errorViewPath, array( 'exception' => $e, 'admin' => is_admin() ) );
            } catch (\Exception $e) {
                //throw new \Exception($e);
                die($e->getMessage());
            }
        } else {
           /* if (WP_DEBUG) {
                var_dump($e);
            }
            echo '<div id="message" class="error"><p>' . $e->getMessage() . '</p></div>';*/
           throw new \Exception($e);
        }
    }

    /**
     * @param $no
     * @param $str
     * @param $file
     * @param $line
     * @throws RuntimeException
     */
    public function onError($no, $str, $file, $line)
    {
        throw new RuntimeException("PHP Error : {$str} on file : {$file} at line : {$line}");
    }

    /**
     * This method only run when plugin was activated
     */
    public function activate()
    {
        $this->setupComponents();
        do_action('learnmaster_activated');
        do_action('lema_run', $this);
        $this->migration->up();
    }

    /**
     * Check current version of lema
     * if new version installed, run migration upgrade
     */
    private function checkVersion()
    {
        if (!defined('DOING_AJAX') && is_admin()) {
            $version = lema()->config->lema_version;
            if (empty($version)) {
                lema()->config->lema_version = LEMA_VERSION;
            } else {
                if (version_compare($version, LEMA_VERSION, '<')) {
                    lema()->cache->flushCache();
                    lema()->migration->upgrade();
                }
            }
        }
    }
    /**
     * This method only run when plugin was deactivated
     */
    public function deactivate()
    {
        lema()->cache->flushCache();
        $this->migration->down();
    }

    /**
     * Uninstall all components resources
     * Run when Learn master was uninstall
     */
    public function uninstall()
    {
        $this->setupComponents();
        lema()->cache->flushCache();
        $this->migration->uninstall();

    }

    /**
     * Get instance of App (reference)
     *
     * @return App
     */
    public static function getInstance($config = [])
    {
        if (empty(self::$instance)) {
            self::$instance = new App($config);
        }
        $app =  &self::$instance;
        return $app;
    }


    /**
     * @return bool
     */
    public function isReady()
    {
        return $this->ready;
    }


}

