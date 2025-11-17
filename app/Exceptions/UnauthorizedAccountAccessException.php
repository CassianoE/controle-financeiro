<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedAccountAccessException extends Exception
{
    public function __construct(string $message = "Você não tem permissão para acessar esta conta.")
    {
        parent::__construct($message);
    }
}
