<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 * Represents a transaction made between two accounts. This could be a transfer, deposit, withdrawal, or payment.
 * @property int $id
 * @property int $from_account_id The sender's account ID.
 * @property int $to_account_id The recipient's account ID.
 * @property float $amount The amount transacted.
 * @property string|null $description A description or note about the transaction.
 * @property string $type The type of transaction: transferência, depósito, retirada, pagamento.
 * @property string $status The status of the transaction: pending, confirmed, canceled, failed.
 * @property \Illuminate\Support\Carbon $transaction_date The date and time when the transaction occurred.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'sender_account_id',
        'recipient_id',
        'recipient_account_id',
        'amount',
        'description',
        'status',
        'transaction_date'
    ];

    public function senderAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
