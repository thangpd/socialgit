<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/19/17.
 */


namespace lema\helpers;


use lema\core\BaseObject;
use lema\core\interfaces\HelperInterface;
use lema\core\interfaces\ValidatorInterface;

class ValidatorHelper extends BaseObject implements HelperInterface
{
    /**
     * @param $type
     * @return ValidatorInterface
     */
    public function getValidator($type, $params = []) {
        $className = "lema\\helpers\\validators\\" . ucfirst($type) . 'Validator';
        if (class_exists($className)) {
            return new $className($params);
        }
    }
}