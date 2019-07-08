<?php
/**
 * @project  Learn Master Plugin
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  10/12/17.
 */


namespace lema\extensions\support;


use lema\core\Client;
use lema\core\Extension;
use lema\core\interfaces\ExtensionInterface;

class SupportExtension extends Extension implements ExtensionInterface
{
    const EXTENSION_ID              =   'support';
    const EXTENSION_VERSION         =   '1.0.0';
    /**
     * @return string
     */
    public function getId()
    {
        return self::EXTENSION_ID;
    }

    /**
     * Start Learn master extension
     * @return mixed
     */
    public function run()
    {
        lema()->hook->listenFilter('lema_admin_setting_tabs', [$this, 'registerAdminTab']);
        add_action('wp_ajax_send_ticket', [$this, 'sendTicket']);
    }

    /**
     * @param $tabs
     * @return mixed
     */
    public function registerAdminTab($tabs) {
        $tabs['support'] = [
            'label' => __('Support', 'lema'),
            'renderer' => [$this, 'supportScreen']
        ];
        return $tabs;
    }

    /**
     * @return mixed|string
     */
    public function supportScreen()
    {
        wp_enqueue_script('lema-support-script', lema()->pluginManager->getUrl($this) . '/assets/scripts/lema.support.js');
        return $this->render('support', [

        ]);
    }

    public function sendTicket()
    {
        $response = [
            'code' => 500,
            'message' => 'Send ticket failed. Please try again later'
        ];
        if (isset($_POST['Support'])) {
            $support = $_POST['Support'];
            if ($support['log']) {
                $support['log'] = (LEMA_WR_DIR .'/logs/error.log');
            } else {
                $support['log'] = '';
            }
            /*$client = Client::create(LEMA_HOME . '/ticket/submit', 'POST', [
                'json' => true,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);*/
            try {
                $support['home_url'] = site_url();
                $support['ip'] = @$_SERVER['SERVER_ADDR'];
                $support['lema_version'] = LEMA_VERSION;
                $support = apply_filters('lema_support_data_ticket', $support);
                //$result = $client->withJson($support)->send()->getResponseBody();
                $content = $this->render('email', $support, true);
                $result = wp_mail(LEMA_SUPPORT_EMAIL, 'Request support mail', $content, [
                    'Content-Type: text/html; charset=UTF-8'
                ], !empty($support['log']) ? [$support['log']] : []);
                if ($result) {
                    $response = [
                        'code' => 200,
                        'message' => 'Sent ticket succeed. Thank for contacting us, we will response you ASAP'
                    ];
                }
            } catch (\Exception $e) {

            }
        }
        wp_send_json($response);
        exit;
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
        return self::EXTENSION_VERSION;
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