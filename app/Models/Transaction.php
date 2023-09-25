<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'transaction_type',
        'user_id',
        'by_user_id',
        'account_type_id',
        'advertisement_id',
        'credit',
        'debit',
        'payment_status'	
    ];
}
