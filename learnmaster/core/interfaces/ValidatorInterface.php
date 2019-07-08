<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/24/17.
 */


namespace lema\core\interfaces;


interface ValidatorInterface
{
    /**
     * Do validate action
     * @return boolean
     */
    public function validate();

    /**
     * Get regex string of validator
     * @return string
     */
    public function getValidatorRegex();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setTarget($name, $value);
}