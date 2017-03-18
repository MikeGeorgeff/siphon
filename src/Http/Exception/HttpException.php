<?php

namespace Siphon\Http\Exception;

use Exception;

class HttpException extends Exception
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @param int    $statusCode
     * @param string $message
     */
    public function __construct($statusCode, $message = '')
    {
        parent::__construct($message);
    }
}