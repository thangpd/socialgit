<?php
/**
 * @project  eduall
 * @copyright © 2017 by Chuke Hill Co.,LTD
 * @author ivoglent
 * @time  11/9/17.
 */


namespace lema\extensions\cache;


use lema\core\Extension;
use lema\core\interfaces\ExtensionInterface;
use lema\libs\CssMinifier;
use MatthiasMullie\Minify\JS;

class CacheExtension extends Extension implements ExtensionInterface
{
    private $minifyEnable = false;
    /**
     * Init the page cache
     * - Generate an unique key of the cache
     * - Check is current request is cached or start caching progress
     * @return mixed|null
     */
    public function lema_page_cache_init()
    {
        $this->minifyEnable = get_option('lema_page_cache_enabled', false);
        if ($this->minifyEnable && !is_admin() && !WP_DEBUG && !defined('DOING_AJAX')) {
            ob_start([$this, "lema_page_cache_end"]);
        }
    }



    /**
     * At the end of the page
     * Get all outputed html and set to cache
     * It will be used for next request
     */
    public function lema_page_cache_end($result)
    {
        return  $this->lema_page_cache_minify($result);
    }


    /**
     * @param $result
     * @return mixed
     */
    public function lema_page_cache_minify($result) {
        if (preg_match_all("/<link(.*?) href='(.*?)'(.*?)>/i", $result, $styles)) {
            //print_r($styles);exit;
            $detected = $styles[1];
            $all_tags = $styles[0];
            $all_styles = $styles[2];
            $css = [];
            for ($i = 0; $i < count($detected); $i++) {
                if (preg_match('/stylesheet/', $detected[$i]) && preg_match('/wp-content/', $all_styles[$i])) {
                    $css[] = $all_styles[$i];
                    $result = str_replace($all_tags[$i], '', $result);
                }
            }

            //Minify
            $key = md5(implode($css));
            $_css = [];
            foreach ($css as $cs) {
                $_css[] =  $this->lema_page_cache_asset_path($cs);
            }
            $minified_css = $this->lema_minify_css($_css, $key);

            //Replace
            $result = str_replace('</head>', "<link rel='stylesheet' href='{$minified_css}' /></head>", $result);

        }
        //Inline script
        $skips = [' type="application/ld+json"'];
        if (preg_match_all('/<script((?:(?!src=).)*?)>(.*?)<\/script>/smix', $result, $matches)){
            $tags = $matches[0];
            $inlines = $matches[2];
            $lines = [];
            foreach ($inlines as $line) {
                $index = array_search($line, $inlines);
                if (in_array($matches[1][$index], $skips)) {
                    continue;
                }
                $lines[] = preg_replace('/(\/\* \]\]> \*\/|\/\* <\[\[ \*\/)/', '', $line);
                $result = str_replace($tags[$index], '', $result);
            }
            $lines = implode(PHP_EOL .';', $lines);
            $js = new JS();
            $js->add($lines);
            $lines = $js->minify();
            $result = str_replace('</body>', '<script type="text/javascript">'. $lines .'</script></body>', $result);
        }



        $result = preg_replace('/src="(.*?)"/i', "src='$1'", $result);
        if (preg_match_all('/<script(.*?) src=\'(.*?)\'>(.*)?<\/script>/i', $result, $scripts)) {
            $all_tags = $scripts[0];
            $all_scripts = $scripts[2];
            //Minify
            $key = md5(implode($all_scripts));
            $jss = [];
            foreach ($all_scripts as $js) {
                $index = array_search($js, $all_scripts);
                if (preg_match('/jquery\.js/', $js)) {
                    continue;
                }
                $jss[] = [
                    $this->lema_page_cache_asset_path($js),
                    $js
                ];
                $result = str_replace($all_tags[$index], '', $result);
            }
            $minified_js = $this->lema_minify_js($jss, $key);

            $result = str_replace('</body>', '<script type="text/javascript" src="' . $minified_js .'"></script></body>', $result);


        }

        return preg_replace('/^\s+/m', '', $result);
    }

    public function lema_cache_remove_asset_version( $src ) {
        if ( strpos( $src, 'ver=' ) )
            $src = remove_query_arg( 'ver', $src );
        return $src;
    }

    /**
     * @param $url
     * @return string
     */
    public function lema_page_cache_asset_path($url) {
        $url = preg_replace('/\?ver(.*?)$/i', '', $url);
        if (strpos(get_bloginfo('wpurl'), $url) >= 0) {
            return ABSPATH . DIRECTORY_SEPARATOR . str_replace(get_bloginfo('wpurl'), '', $url);
        }
        return $url;
    }
    /**
     * Remove the cache by url
     * @param $url
     */
    public function lema_page_cache_flush_url($url) {
        $pages = lema()->cache->get('lema_page_cache_pages', []);
        if (array_key_exists($url, $pages)) {
            foreach ($pages[$url] as $key) {
                lema()->cache->delete($key);
            }
        }
    }

    /**
     * Get cache key for this request
     * @param string $prefix
     * @return string
     */
    private function __get_current_cache_key($prefix = '')
    {
        $keys = [
            'REQUEST_URI', 'HTTP_HOST', 'HTTP_USER_AGENT', 'HTTP_ACCEPT', 'REQUEST_METHOD' ,'QUERY_STRING', 'HTTP_COOKIE'
        ];
        $dataKey = [];
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $dataKey[] = $_SERVER[$key];
            }
        }
        $dataKey = md5(implode('', $dataKey));
        return $prefix . $dataKey;
    }

    /**
     * Add option to admin setting
     * @param array $options
     * @return array
     */
    public function lema_page_cache_options($options = []) {
        $options['lema_page_cache_enabled'] = 'Enable asset minify';

        return $options;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'cache';
    }

    /**
     * Start Learn master extension
     * @return mixed
     */
    public function run()
    {
        add_action('lema_after_init', [$this, 'lema_page_cache_init']);

        add_filter('lema_cache_options', [$this, 'lema_page_cache_options']);
        // Remove WP Version From Styles
        add_filter( 'style_loader_src', [$this, 'lema_cache_remove_asset_version'], 9999 );
        // Remove WP Version From Scripts
        add_filter( 'script_loader_src', [$this, 'lema_cache_remove_asset_version'], 9999 );
        // Function to remove version numbers
    }

    /**
     * @param $scripts
     * @param $name
     * @return string
     */
    private function lema_minify_js($scripts, $name) {
        $data = '';
        $minPath = '/assets/scripts/' . $name . '.min.js';
        $url = site_url() . '/wp-content/uploads/lema/assets/scripts/' . $name . '.min.js';
        $filePath = LEMA_WR_DIR . $minPath;
        if (file_exists($filePath)) {
            return $url;
        }
        foreach ($scripts as $script) {
            $path = $script[0];
            $data  .= ';'.file_get_contents($path) . PHP_EOL;
        }

        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $f = fopen($filePath, 'w+');
        if ($f) {
            fputs($f, $data);
            fclose($f);
            return $url;
        }
        return false;

    }

    public function lema_minify_css($styles, $key) {
        $cssFile = 'assets' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR .  $key . '.min.css';
        $cssPath = LEMA_WR_DIR . DIRECTORY_SEPARATOR . $cssFile;

        if (!file_exists($cssPath)) {
            $cssMinifier = new CssMinifier();
            $cssMinifier->setImportExtensions([]);
            $cssMinifier->setMaxImportSize(0);
            foreach ($styles as $id => $path) {
                //wp_dequeue_style($id);
                if (file_exists($path)) {
                    $cssMinifier->add($path);
                }

            }
            try {
                $assetDir = dirname($cssPath);
                if (!is_dir($assetDir)) {
                    @mkdir($assetDir, 0777, true);
                }
                $cssMinified = $cssMinifier->minify($cssPath);
            } catch (\Exception $e) {
                lema()->logger->error($e->getMessage());
            }

        }
        if (file_exists($cssPath)) {
            $cssFile = 'assets/styles/' .  $key . '.min.css'; // Now use URI instead of file path
            $cssUrl = site_url() . '/wp-content/uploads/lema/' . $cssFile;
            return $cssUrl;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Get current version of extension
     * @return mixed
     */
    public function getVersion()
    {
        return '1.0.1';
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
}