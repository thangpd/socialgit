<?php
/**
 * @project  Learn Master Plugin
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  8/28/17.
 */


namespace lema\libs;


use MatthiasMullie\PathConverter\Converter;

class CssPathConvertor extends Converter
{
    public function convert($path)
    {
        // quit early if conversion makes no sense
        if ($this->from === $this->to) {
            return $path;
        }

        $path = $this->normalize($path);
        // if we're not dealing with a relative path, just return absolute
        if (strpos($path, '/') === 0) {
            return $path;
        }

        // normalize paths
        $path = $this->normalize($this->from.'/'.$path);

        // strip shared ancestor paths
        $shared = $this->shared($path, $this->to);
        $path = mb_substr($path, mb_strlen($shared));
        $to = mb_substr($this->to, mb_strlen($shared));

        // add .. for every directory that needs to be traversed to new path
        //$to = str_repeat('../', mb_substr_count($to, '/'));
        return site_url() . '/' . $path;
        //return $to.ltrim($path, '/');
    }
}