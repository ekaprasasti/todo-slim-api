<?php

namespace App\Core\Exceptions;

class HttpException extends \RuntimeException
{
    protected $statusCode = 400;

    function __construct($statusCode, $message) {
        $this->statusCode = $statusCode;
        parent::__construct($message, 1000, null);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
