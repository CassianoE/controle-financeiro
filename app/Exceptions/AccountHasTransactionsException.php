<?php

namespace App\Exceptions;

use Exception;

class AccountHasTransactionsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Conta não pode ser excluída, pois possui transações associadas.');
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
