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
    protected $table = 'nome_da_sua_tabela'; // Por exemplo: 'blacklist_tokens'

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token', 'blacklisted_at' // Adicione quaisquer outros campos que deseja tornar "mass assignable"
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',  // Você pode querer ocultar o token quando converter o modelo para uma array ou JSON
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

