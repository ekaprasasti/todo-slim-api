<?php

namespace App\Modules\Auth\Exceptions;

class EmailAlreadyRegisteredException extends \Exception
{
    function __construct()
    {
        $code = 1001;
        $message = "Email already used";
        parent::__construct($message, $code, null);
    }
}
