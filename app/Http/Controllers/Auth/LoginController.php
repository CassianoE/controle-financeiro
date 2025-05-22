<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Services\Auth\LoginService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   
    public function __construct(
        protected LoginService $loginService
    ){}

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->loginService->login($request->validated());

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token' => $data['token'],
            'user' => $data['user']
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        // Delete all tokens for the authenticated user
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }
}
