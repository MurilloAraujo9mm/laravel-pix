<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * App\Models\Account
 * An Eloquent model representing a user's account.
 * @property int $id
 * @property int $user_id
 * @property float $balance
 * @property string $account_number
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $sentTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $receivedTransactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BalanceHistory[] $balanceHistories
 */
class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'balance',
        'account_number'
    ];

    /**
     * Get the transactions sent from this account.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    

    /**
     * Get the transactions received by this account.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    /**
     * Get the balance histories for this account.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balanceHistories(): HasMany
    {
        return $this->hasMany(BalanceHistory::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->pix_key = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
