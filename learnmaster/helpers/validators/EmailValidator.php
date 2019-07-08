<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\helpers\validators;


use lema\core\Validator;

class EmailValidator extends Validator
{
    public $regex = '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/  ';

    /**
     * @return bool|int|mixed
     */
    public function validate()
    {
        if ($this->required && empty($this->content)) {
            return false;
        }
        if (function_exists('filter_var') && defined('FILTER_VALIDATE_EMAIL')) {
            return filter_var($this->content, FILTER_VALIDATE_EMAIL);
        } else {
            return preg_match($this->regex, $this->content);
        }
    }
}