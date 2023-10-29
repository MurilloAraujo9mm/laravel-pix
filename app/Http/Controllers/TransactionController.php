<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * The TransactionService instance.
     * @var \App\Services\TransactionService
     */
    protected TransactionService $transactionService;

    /**
     * Create a new controller instance.
     * @param \App\Services\TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Store a new transaction.
     * This method captures the request data, validates it and then processes the transaction.
     * @param \App\Http\Requests\StoreTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $this->transactionService->processTransfer($data['pix_key'], $data['amount'], $data['description']);
            return response()->json(['message' => 'Transação realizada com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }    


    /**
     * List all transactions.
     * Retrieves and returns a list of all transactions from the service.
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTransactions(): JsonResponse
    {
        $transactions = $this->transactionService->listAllTransactions();
        return response()->json($transactions);
    }
}
