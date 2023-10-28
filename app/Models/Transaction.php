<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
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
}
