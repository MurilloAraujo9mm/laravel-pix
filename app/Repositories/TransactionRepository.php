<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionRepository
 * Handles the data access logic for the Transaction model.
 * @package App\Repositories
 */
class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Fetch paginated transactions.
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Transaction::with(['senderAccount', 'sender', 'recipient'])->paginate(10);
    }

    /**
     * Create a new transaction record.
     * @param array $data
     * @return \App\Models\Transaction
     */
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    /**
     * Find a transaction by its primary key.
     * @param int $id
     * @return \App\Models\Transaction|null
     */
    public function findById(int $id): ?Transaction
    {
        return Transaction::find($id);
    }

    /**
     * Find transactions by sender's ID.
     * @param int $senderId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findBySenderId(int $senderId)
    {
        return Transaction::where('sender_id', $senderId)->get();
    }

    /**
     * Find transactions by recipient's ID.
     * @param int $recipientId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findByRecipientId(int $recipientId)
    {
        return Transaction::where('recipient_id', $recipientId)->get();
    }

    /**
     * Fetch the most recent transaction.
     * @return \App\Models\Transaction|null
     */
    public function findLatest(): ?Transaction
    {
        return Transaction::latest()->first();
    }

    public function processTransfer(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->transferFromSender($data['sender_account_id'], $data['amount']);
            $this->transferToRecipient($data['recipient_account_id'], $data['amount']);
            $this->recordTransaction($data);
        });
    }

    protected function transferFromSender($accountId, $amount)
    {
        Account::find($accountId)->decrement('balance', $amount);
    }

    protected function transferToRecipient($accountId, $amount)
    {

        Account::find($accountId)->increment('balance', $amount);
    }


    protected function recordTransaction($data)
    {
        Transaction::create($data);
    }
}
