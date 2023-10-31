<?php

namespace App\Services\Transaction;

use App\Models\Account;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\AMQP\RabbitMQ;
use Illuminate\Support\Facades\DB;
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

    protected $ballance = null;

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


    public function listAllTransactions()
    {
        return $this->transactionRepository->all();
    }

    public function findTransaction(int $userId, string $type)
    {
        if ($type === 'sender') {
            return $this->transactionRepository->findBySenderId($userId);
        }

        if ($type === 'recipient') {
            return $this->transactionRepository->findByRecipientId($userId);
        }

        throw new \Exception('Tipo de busca inválido.');
    }



    /**
     * Process a transfer using a given Pix key.
     * @param string $pixKey The Pix key of the recipient.
     * @param float $amount The amount to be transferred.
     * @throws \Exception If the recipient is not found, or if trying to transfer to oneself, or if there's insufficient balance.
     * @return void
     */
    public function processTransfer(string $pixKey, float $amount, string $transactionType)
    {


        DB::transaction(function () use ($pixKey, $amount, $transactionType, &$balanceAfterTransaction) {

            $recipientAccount = Account::where('pix_key', $pixKey)->first();

            if (!$recipientAccount) {
                throw new \Exception('Destinatário não encontrado pela chave Pix.');
            }

            $senderAccount = $this->accountRepository->getLoggedInUserAccount();
            $this->ballance = $senderAccount->balance;

            if ($senderAccount->id === $recipientAccount->id) {
                throw new \Exception('Transações para a mesma conta não são permitidas.');
            }

            if ($senderAccount->balance < $amount) {
                throw new \Exception(
                    'Saldo insuficiente. Você tem apenas ' . number_format($senderAccount->balance, 2, '.', ',') . " reais em sua conta"
                );
            }

            $this->transferFromSender($senderAccount->id, $amount);
            $this->transferToRecipient($recipientAccount->id, $amount);
            $this->recordTransaction([
                'sender_id' => $senderAccount->user_id,
                'sender_account_id' => $senderAccount->id,
                'recipient_id' => $recipientAccount->user_id,
                'recipient_account_id' => $recipientAccount->id,
                'amount' => $amount,
                'description' => $this->validateTransactionType($transactionType),
                'status' => 'pending',
                'transaction_date' => now(),
            ]);

            (new RabbitMQ())->producer(
                env('RABBITMQ_QUEUE', 'default_queue_name'),
                [
                    'amount' => number_format($amount, 2, '.', ''),
                    "key_pix" => $recipientAccount->pix_key,
                    'transaction_date' => now(),
                    'description' => $transactionType,
                    'status' => 'pending'
                ],
                'amq.direct'
            );

        });

        return $this->ballance;
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
