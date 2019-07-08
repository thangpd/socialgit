<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 * @since 1.0
 */


namespace lema\core\interfaces;


interface PluginInterface
{
    /**
     * Return id of plugin
     * @return mixed
     */
    public function getId();

    /**
     * Start run plugin
     * @return mixed
     */
    public function run();

    /**
     * Get version of plugin
     * @return mixed
     */
    public function getVersion();
}