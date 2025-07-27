<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Services\AccountService;      
use Illuminate\Http\JsonResponse;              


class AccountController extends Controller
{
    
    public function __construct(AccountService $accountService){
        $this->accountService = $accountService;
    }


    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $accounts = $this->accountService->list($userId);

        return response()->json($accounts, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
