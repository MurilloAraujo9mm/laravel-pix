<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

/**
 * Class TransactionRepository
 * Handles the data access logic for the Transaction model.
 * @package App\Repositories
 */
class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Fetch all transactions.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Transaction::all();
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
}
