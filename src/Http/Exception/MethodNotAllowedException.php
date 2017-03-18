<?php

namespace Siphon\Http\Exception;

class MethodNotAllowedException extends HttpException
{
    /**
     * @param string $message
     */
    public function __construct($message = '')
    {
        parent::__construct(405, $message);
    }
}