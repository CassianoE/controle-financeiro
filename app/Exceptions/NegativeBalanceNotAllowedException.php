<?php

namespace App\Exceptions;

use Exception;

class NegativeBalanceNotAllowedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Saldo negativo só é permitido para contas do tipo crédito.');
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
