<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 *
 * @since 1.0
 *
 */


namespace lema\libs;


use MatthiasMullie\Minify\CSS;

class CssMinifier extends CSS
{
    public $importExtensions = [];
    public $maxImportSize  = 0;
    public function __construct()
    {
        parent::__construct();
       // $this->setMaxImportSize(1);
       // $this->setImportExtensions([]);
    }

    protected function getPathConverter($source, $target)
    {
        return new  CssPathConvertor($source, $target);
    }
}