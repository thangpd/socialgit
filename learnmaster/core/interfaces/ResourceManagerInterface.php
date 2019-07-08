<?php
/**
 * @project  learnmaster
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/15/17.
 */


namespace lema\core\interfaces;


interface ResourceManagerInterface
{
    /**
     * Register a script to Assetmanager
     * @param ScriptInterface $script
     * @return mixed
     */
    public function registerScript(ScriptInterface $script);

    /**
     * @param StyleInterface $style
     * @return mixed
     */
    public function registerStyle(StyleInterface $style);

    /**
     * @return ScriptInterface[]
     */
    public function getRegisteredScripts();

    /**
     * @return StyleInterface[]
     */
    public function getRegisteredStyles();
}