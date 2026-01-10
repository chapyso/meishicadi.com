<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'request_date',
    ];
}
