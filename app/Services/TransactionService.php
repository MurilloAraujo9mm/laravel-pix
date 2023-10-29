<?php

namespace App\Services;

use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Handles transaction-related operations.
 */
class TransactionService
{
    /** @var TransactionRepositoryInterface */
    protected TransactionRepositoryInterface $transactionRepository;

    /** @var AccountRepositoryInterface */
    protected AccountRepositoryInterface $accountRepository;

    /**
     * TransactionService constructor.
     * @param TransactionRepositoryInterface $transactionRepository
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(TransactionRepositoryInterface $transactionRepository, AccountRepositoryInterface $accountRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Retrieve all transactions.
     *
     * @return Collection
     */
    public function listAllTransactions(): Collection
    {
        return $this->transactionRepository->all();
    }

    /**
     * Process a transfer using a given Pix key.
     * @param string $pixKey The Pix key of the recipient.
     * @param float $amount The amount to be transferred.
     * @throws \Exception If the recipient is not found, or if trying to transfer to oneself, or if there's insufficient balance.
     * @return void
     */
    public function processTransfer(string $pixKey, float $amount, string $transactionType): void
    {
        DB::transaction(function () use ($pixKey, $amount, $transactionType) {
            $recipientAccount = Account::where('pix_key', $pixKey)->first();

            if (!$recipientAccount) {
                throw new \Exception('Destinatário não encontrado pela chave Pix.');
            }

            $senderAccount = $this->accountRepository->getLoggedInUserAccount();

            if ($senderAccount->id === $recipientAccount->id) {
                throw new \Exception('Transações para a mesma conta não são permitidas.');
            }

            if ($senderAccount->balance < $amount) {
                throw new \Exception('Saldo insuficiente.');
            }

            $this->transferFromSender($senderAccount->id, $amount);
            $this->transferToRecipient($recipientAccount->id, $amount);

            $data = [
                'sender_id' => $senderAccount->user_id,
                'sender_account_id' => $senderAccount->id,
                'recipient_id' => $recipientAccount->user_id,
                'recipient_account_id' => $recipientAccount->id,
                'amount' => $amount,
                'description' => $this->validateTransactionType($transactionType),
                'status' => 'pending',
                'transaction_date' => now(),
            ];

            $this->recordTransaction($data);
        });
    }

    /**
     * Deducts amount from a given account's balance.
     * @param int $accountId The account's ID.
     * @param float $amount The amount to be deducted.
     * @return void
     */
    protected function transferFromSender(int $accountId, float $amount): void
    {
        Account::find($accountId)->decrement('balance', $amount);
    }

    /**
     * Is Valid o type transaction.
     *
     * @param  string $transactionType Type transaction validate
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function validateTransactionType(string $transactionType): void
    {
        $allowedTypes = ['pix', 'boleto', 'TED'];

        if (!in_array(Str::lower($transactionType), $allowedTypes, true)) {
            throw new \InvalidArgumentException("Tipo de transação inválido: {$transactionType}");
        }
    }


    /**
     * Adds amount to a given account's balance.
     * @param int $accountId The account's ID.
     * @param float $amount The amount to be added.
     * @return void
     */
    protected function transferToRecipient(int $accountId, float $amount): void
    {
        Account::find($accountId)->increment('balance', $amount);
    }

    /**
     * Records a transaction with the provided data.
     * @param array $data The transaction data.
     * 
     * @return void
     */
    protected function recordTransaction(array $data): void
    {
        $this->transactionRepository->create($data);
    }
}
