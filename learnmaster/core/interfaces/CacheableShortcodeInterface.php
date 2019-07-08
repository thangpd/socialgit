<?php
/**
 * @project  Learn Master Plugin
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  8/29/17.
 */


namespace lema\core\interfaces;


interface CacheableShortcodeInterface extends CacheableInterface
{
    /**
     * @return ShortcodeInterface[] | string[]
     */
    public function getChildren();
}