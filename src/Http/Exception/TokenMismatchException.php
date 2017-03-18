<?php

namespace Siphon\Http\Exception;

class TokenMismatchException extends HttpException
{
    /**
     * @param int    $statusCode
     * @param string $message
     */
    public function __construct($statusCode = 422, $message = 'Csrf token validation failed')
    {
        parent::__construct($statusCode, $message);
    }
}