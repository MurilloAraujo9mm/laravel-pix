<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    const ACCOUNT_NUMBER_LENGTH = 12;
    const ALLOWED_CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected $fillable = [
        'user_id',
        'balance',
        'account_number',
    ];

    /**
     * Generate a random account number.
     * @return string
     */
    public function generateRandomAccountNumber(): string {
        $accountNumber = '';
        for ($i = 0; $i < self::ACCOUNT_NUMBER_LENGTH; $i++) {
            $accountNumber .= $this->getRandomCharacter();
        }
        return $accountNumber;
    }

     /**
     * Get a random character from the allowed characters string.
     * @return string
     */
    private function getRandomCharacter() {
        $index = rand(0, strlen(self::ALLOWED_CHARACTERS) - 1);
        return self::ALLOWED_CHARACTERS[$index];
    }
    
}
