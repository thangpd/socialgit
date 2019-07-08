<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/17/17.
 */


namespace lema\core\interfaces;


interface CacheableInterface
{
    /**
     * If this object able to cache, it needs provider owner cache name
     * @return mixed
     */
    public function getCahename();

    /**
     * Flush owner cache to refresh data
     * @return mixed
     */
    public function flushCache();
}