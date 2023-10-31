<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Services\Transaction\TransactionService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel API",
 *      description="Laravel API description",
 *      @OA\Contact(
 *          email="your-email@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 */

class TransactionController extends Controller
{
    /**
     * The TransactionService instance.
     * @var \App\Services\Transaction\TransactionService
     */
    protected TransactionService $transactionService;

    /**
     * Create a new controller instance.
     * @param \App\Services\Transaction\TransactionService $transactionService
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

    /**
     * @OA\Post(
     *      path="/api/v1/transaction/create",
     *      summary="Store a new transaction",
     *      description="Create a new transaction and store it in the database",
     *      tags={"Transaction"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"pix_key", "amount", "description"},
     *              @OA\Property(property="pix_key", type="string", description="Pix key of the receiver"),
     *              @OA\Property(property="amount", type="number", format="float", description="Amount to transfer"),
     *              @OA\Property(property="description", type="string", description="Description of the transaction")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Transaction successfully stored",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Success message")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Error message")
     *          )
     *      )
     * )
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $currentBalance = (float) $this->transactionService
                ->processTransfer($data['pix_key'], $data['amount'], $data['description']);

            return response()->json([
                'message' => 'Transação realizada com sucesso!',
                'balance' => number_format($currentBalance, 2, '.', ',')
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }



    /**
     * @OA\Get(
     *      path="/api/v1/transaction/list",
     *      summary="List all transactions",
     *      description="Retrieve and return a list of all transactions from the database",
     *     security={
     *          {"bearerAuth": {}}
     *      },
     *      tags={"Transaction"},
     *          @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token for authorization",
     *          required=true,
     *          @OA\Schema(type="string", default="Bearer your_token_here")
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *  @OA\SecurityScheme(
     *     type="http",
     *     description="Bearer Token Authentication",
     *     name="Bearer",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="bearerAuth"
     *  ),
     *      @OA\Response(
     *          response=200,
     *          description="List of transactions",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(type="object",
     *                  @OA\Property(property="id", type="integer", description="Transaction ID"),
     *                  @OA\Property(property="sender_id", type="integer", description="Sender ID"),
     *                  @OA\Property(property="sender_account_id", type="integer", description="Sender's Account ID"),
     *                  @OA\Property(property="recipient_id", type="integer", description="Recipient ID"),
     *                  @OA\Property(property="recipient_account_id", type="integer", description="Recipient's Account ID"),
     *                  @OA\Property(property="amount", type="string", description="Amount of the transaction"),
     *                  @OA\Property(property="description", type="string", description="Description of the transaction"),
     *                  @OA\Property(property="status", type="string", description="Transaction status"),
     *                  @OA\Property(property="transaction_date", type="string", description="Date of the transaction"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
     *                  @OA\Property(property="sender_account", type="object", description="Sender account details", nullable=true),
     *                  @OA\Property(
     *                      property="sender",
     *                      type="object",
     *                      description="Details of the sender",
     *                      @OA\Property(property="id", type="integer", description="Sender ID"),
     *                      @OA\Property(property="name", type="string", description="Sender Name"),
     *                      @OA\Property(property="email", type="string", description="Sender Email"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp of sender"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp of sender")
     *                  ),
     *                  @OA\Property(
     *                      property="recipient",
     *                      type="object",
     *                      description="Details of the recipient",
     *                      @OA\Property(property="id", type="integer", description="Recipient ID"),
     *                      @OA\Property(property="name", type="string", description="Recipient Name"),
     *                      @OA\Property(property="email", type="string", description="Recipient Email"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp of recipient"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp of recipient")
     *                  )
     *              )
     *          )
     *      )
     * )
     */

    public function findTransaction(int $userId, string $type, TransactionService $transactionService)
    {
        try {
            $transactions = $transactionService->findTransaction($userId, $type);
            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function listTransactions(): JsonResponse
    {
        $transactions = $this->transactionService
            ->listAllTransactions();
        return response()
            ->json($transactions);
    }
}
