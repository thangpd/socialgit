<?php
/**
 * @project  edu
 * @copyright © 2017 by ivoglent
 * @author ivoglent
 * @time  7/21/17.
 */


namespace lema\front\controllers;


use lema\core\Controller;
use lema\core\interfaces\ControllerInterface;

class FrontController extends Controller
{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

    }

    public function getSupportPages()
    {
        return [];
    }

    public function render($view = '', $data = [], $return = true)
    {
        return parent::render($view, $data, $return); // TODO: Change the autogenerated stub
    }
}