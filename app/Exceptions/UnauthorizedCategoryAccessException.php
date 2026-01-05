<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedCategoryAccessException extends Exception
{
    public function __construct(string $message = "Você não tem permissão para acessar esta categoria.")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 403);
    }
}

