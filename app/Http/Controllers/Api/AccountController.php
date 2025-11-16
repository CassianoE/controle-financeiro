<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountCreateRequest;
use App\Http\Requests\AccountUpdateRequest;


class AccountController extends Controller
{
    public function __construct(AccountService $accountService){
        $this->accountService = $accountService;
        $this->authorizeResource(Account::class, 'account');
    }


    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $accounts = $this->accountService->list($userId);

        return response()->json($accounts, 200);
    }

    public function store(AccountCreateRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $data = $request->validated();

        $account = $this->accountService->store($data,$userId);

        return response()->json($account, 201);
    }

    public function show(Request $request, Account $account): JsonResponse
    {
    return response()->json($account);
    }

    public function update(AccountUpdateRequest $request ,Account $account): JsonResponse
    {
        $data = $request->validated();

        $accountUpdated = $this->accountService->update($account ,$data);

        return response()->json($accountUpdated, 200);
    }

    public function destroy(Request $request ,Account $account)
    {
        $this->accountService->destroy($account);

        return response()->noContent();
    }
}
