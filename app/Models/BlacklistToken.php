<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistToken extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nome_da_sua_tabela';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'blacklisted_at'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token', 
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'blacklisted_at' => 'datetime',
    ];
}

