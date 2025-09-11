<?php

namespace App\Exceptions;

use App\Enums\AccountStatus;
use Exception;

class InvalidAccountStatusException extends Exception
{
    public function __construct()
    {
        parent::__construct('Status inválido. Valores permitidos: ' . implode(", ", AccountStatus::values()));
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422);
    }
}
