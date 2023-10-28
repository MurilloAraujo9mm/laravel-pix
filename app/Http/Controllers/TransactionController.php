<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Http\Requests\StoreTransactionRequest;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();

        try {
            $this->transactionService->processTransaction($data);
            return response()->json(['message' => 'Transação realizada com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
