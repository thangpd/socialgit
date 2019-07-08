<?php
/**
 * @project  Learn Master Plugin
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  9/7/17.
 */


namespace lema\extensions\manager;


use lema\core\BaseObject;
use lema\core\Client;
use lema\core\interfaces\ExtensionInterface;

class ExtensionRepository extends BaseObject
{
    public $apiUrl = '';
    private $edu_type = 'default';
    private $edu_version = '1.0.0';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return object[]
     */
    public function getAvailableExtensions($path = 'extension/list.json')
    {
        $extensionEndpointUrl = $this->apiUrl .'/' . $path;
        try {
            /** @var Client $client */
            $client = Client::create($extensionEndpointUrl);
            $client->send();
            $result = $client->getResponseBody();
            if (!empty($result)) {
                $result = json_decode($result);
                if ($result && $result->code == 200) {
                    return $result->data;
                }
            }
        } catch (\Exception $e) {
            lema()->getLogger()->error($e->getMessage(), $e->getTrace());
        }

        return [];

    }

    public function checkExtensionVersion(ExtensionInterface $extension)
    {

    }

    /**
     * @param $plugin
     * @param $license
     * @return bool
     */
    public function getPluginLicenseInfo($plugin, $license) {
        $extensionEndpointUrl = $this->apiUrl .'/plugin/check?plugin=' . $plugin . '&license=' . urlencode($license) . '&domain=' . @$_SERVER['HTTP_HOST']. '&type=' . $this->edu_type . '&edu_version=' . $this->edu_version;
        try {
            /** @var Client $client */
            $client = Client::create($extensionEndpointUrl);
            $client->send();
            $result = $client->getResponseBody();
            if (!empty($result)) {
                $result = json_decode($result);
                if ($result && $result->code == 200) {
                    return $result->data;
                }
            }
        } catch (\Exception $e) {
            lema()->getLogger()->error($e->getMessage(), $e->getTrace());
        }

        return false;
    }

    /**
     * @param $plugin
     * @param string $license
     * @param string $version
     * @return bool
     */
    public function checkPluginUpdate($plugin, $license ='', $version = '') {
        $extensionEndpointUrl = $this->apiUrl .'/plugins/update?plugin=' . $plugin . '&license=' . urlencode($license) . '&domain=' . @$_SERVER['HTTP_HOST'] . '&version=' . urlencode($version) . '&type=' . $this->edu_type . '&edu_version=' . $this->edu_version;
        try {
            /** @var Client $client */
            $client = Client::create($extensionEndpointUrl);
            $client->send();
            $result = $client->getResponseBody();
            if (!empty($result)) {
                $result = json_decode($result);
                if ($result && $result->code == 200) {
                    return $result->data;
                }
            }
        } catch (\Exception $e) {
            lema()->getLogger()->error($e->getMessage(), $e->getTrace());
        }

        return false;
    }
}