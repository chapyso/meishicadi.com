<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_amount',
        'request_user_id',
        'status',
        'date',
    ];
}
