<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\helpers;


use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use lema\core\BaseObject;
use lema\core\interfaces\HelperInterface;

class SecurityHelper extends BaseObject implements HelperInterface
{

    /**
     * Return safe string key
     */
    public function getSafeKeyString()
    {
        $key = $this->getSafeKey();
        return $key->saveToAsciiSafeString();
    }
    /**
     * @return Key
     */
    public function getSafeKey()
    {
        return Key::createNewRandomKey();
    }
    /**
     * @param $input_string
     * @param $key
     * @return string
     */
    public function encrypt($data, $key){
        $key = Key::loadFromAsciiSafeString($key, true);
        return Crypto::encrypt($data, $key);
    }

    /**
     * @param $encrypted_input_string
     * @param $key
     * @return string
     */
    public function decrypt($data, $key){
        $key = Key::loadFromAsciiSafeString($key, true);
        return Crypto::decrypt($data, $key);
    }
}