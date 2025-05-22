<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\RegisterService;


class RegisterController extends Controller
{
    public function __construct(
        protected RegisterService $registerService
    ){}

     
    public function register(RegisterRequest $request): JsonResponse{
        
        $user = $this->registerService->handle($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }



}


