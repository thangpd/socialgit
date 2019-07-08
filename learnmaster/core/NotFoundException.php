<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 * @since 1.0
 */


namespace lema\core;


use Throwable;

class NotfoundException extends \Exception
{
    /**
     * NotfoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        if (lema()->logger) {
            lema()->logger->error($message,(array) $this->getTrace());
        }
    }
}