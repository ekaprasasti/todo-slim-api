<?php

namespace App\Modules\Auth\Exceptions;

class InvalidTokenException extends \Exception
{
    function __construct()
    {
        $code = 1002;
        $message = "Invalid Token";
        parent::__construct($message, $code, null);
    }
}
