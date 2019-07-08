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

class NumberValidator extends Validator
{
    /**
     * @var int
     */
    public $max = PHP_INT_MAX;
    /**
     * @var int
     */
    public $min;

    public function init()
    {
        $this->min = (-1) * PHP_INT_MAX;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if ($this->requireValidate()) {
            if (is_numeric($this->content)) {
                if ($this->min != PHP_INT_MIN) {
                    if ($this->content < $this->min) {
                        return false;
                    }
                }
                if ($this->max != PHP_INT_MAX) {
                    if ($this->content > $this->max) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }


}