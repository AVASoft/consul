<?php


namespace Avasoft\Component\Consul\Exception;

use Avasoft\Component\Consul\Annotation\Pure;
use \Throwable;
use \Exception;

/**
 * Class ConsulException
 * @package Avasoft\Component\Consul\Exception
 */
class ConsulException extends Exception
{
    #[Pure] public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        parent::__toString();
    }
}