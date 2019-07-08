<?php
/**
 * @project  learnmaster
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/14/17.
 */


namespace lema\core\interfaces;


interface ExtensionInterface extends MigrableInterface
{
    /**
     * @return string
     */
    public function getId();
    /**
     * Start Learn master extension
     * @return mixed
     */
    public function run();

    /**
     * @return boolean
     */
    public function isEnabled();

    /**
     * Get current version of extension
     * @return mixed
     */
    public function getVersion();

    /**
     * Automatic check update version
     * @return mixed
     */
    public function checkVersion();
}