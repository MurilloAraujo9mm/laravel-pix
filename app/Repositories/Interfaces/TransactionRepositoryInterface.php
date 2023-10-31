<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface
{
    /**
     * Fetch all transactions.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all():  \Illuminate\Contracts\Pagination\LengthAwarePaginator;


    /**
     * Create a new transaction record.
     * @param array $data
     * @return \App\Models\Transaction
     */
    public function create(array $data);

    /**
     * Find a transaction by its primary key.
     * @param int $id
     * @return \App\Models\Transaction|null
     */
    public function findById(int $id);

    /**
     * Find transactions by sender's ID.
     * @param int $senderId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findBySenderId(int $senderId);

    /**
     * Find transactions by recipient's ID.
     * @param int $recipientId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findByRecipientId(int $recipientId);

    /**
     * Fetch the most recent transaction.
     * @return \App\Models\Transaction|null
     */
    public function findLatest();
}
