<?php

namespace Siphon\Http\Exception;

class NotFoundException extends HttpException
{
    /**
     * @param string $message
     */
    public function __construct($message = '')
    {
        parent::__construct(404, $message);
    }
}