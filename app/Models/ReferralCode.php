<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'user_id', 'used', 'used_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
