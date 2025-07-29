<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Http\Requests\AccountRequest;
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

    public function store(AccountRequest $accountRequest): JsonResponse
    {
        $userId = $accountRequest->user()->id;

        $data = $accountRequest->validated();

        $account = $this->accountService->store($data,$userId);

        return response()->json($account, 201);
    }

    public function show(string $accountId ,AccountRequest $accountRequest): JsonResponse
    {
        $account = $this->accountService->findById($accountId);

        return response()->json($account, 200);
    }

    public function update(AccountRequest $accountRequest ,Account $account): JsonResponse
    {
        $data = $accountRequest->validated();

        $accountUpdated = $this->accountService->update($account ,$data);

        return response()->json($accountUpdated, 200);
    }

    public function destroy(Account $account)
    {
        $this->accountService->destroy($account);

        return response()->json(204);
    }
}
