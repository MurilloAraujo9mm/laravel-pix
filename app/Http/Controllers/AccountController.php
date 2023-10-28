<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest; 
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    /** @var AccountService */
    protected $accountService;

    /**
     * AccountController constructor.
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Store a new account.
     * @param StoreAccountRequest $request
     * @return JsonResponse
     */
    public function store(StoreAccountRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        
        try {
            $account = $this->accountService->createAccount($validatedData);
            return response()->json(['message' => 'Conta criada com sucesso!', 'account' => $account], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
