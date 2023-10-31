<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\BalanceHistory;

class AccountObserver
{

    public function created(Account $account): void
    {
        BalanceHistory::create([
            'account_id' => $account->id,
            'amount' => $account->balance,
            'balance' => $account->balance,
            'description' => 'Initial balance'
        ]);
    }


    public function updated(Account $account)
    {
        if ($account->isDirty('balance')) {
            \App\Models\BalanceHistory::create([
                'account_id' => $account->id,
                'amount' => $account->balance - $account->getOriginal('balance'),
                'balance' => $account->balance,
                'recorded_date' => now(),
                'description' => 'Balance updated'
            ]);
        }
    }

    public function deleted(Account $account): void
    {
    }


    public function restored(Account $account): void
    {
    }

    public function forceDeleted(Account $account): void
    {
    }
}
