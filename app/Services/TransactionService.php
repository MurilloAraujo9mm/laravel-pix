<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function processTransaction(array $data)
    {
        $senderAccount = Account::find($data['sender_account_id']);
        if ($senderAccount->balance < $data['amount']) {
            throw new \Exception('Saldo insuficiente.');
        }

        DB::transaction(function () use ($data, $senderAccount) {
            $senderAccount->decrement('balance', $data['amount']);
            $recipientAccount = Account::find($data['recipient_account_id']);
            $recipientAccount->increment('balance', $data['amount']);

            $this->transactionRepository->create([
                'sender_id' => $data['sender_id'],
                'sender_account_id' => $data['sender_account_id'],
                'recipient_id' => $data['recipient_id'],
                'recipient_account_id' => $data['recipient_account_id'],
                'amount' => $data['amount'],
                'description' => $data['description'] ?? null,
                'status' => 'confirmed',
                'transaction_date' => now(),
            ]);
        });
    }
}
    