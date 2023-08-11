<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    use HasFactory;

    protected $fillable = [  
         
        'email_protocol',
        'email_encryption',
        'smtp_host',
        'smtp_port',
        'smtp_email',
        'smtp_username',
        'smtp_password',
        'from_address',
        'from_name',
        'status',

    ];

}
