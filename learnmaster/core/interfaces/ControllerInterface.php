<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/20/17.
 */


namespace lema\core\interfaces;


interface ControllerInterface
{

    /**
     * Register all actions that controller want to hook
     * @return mixed
     */
    public static function registerAction();
}