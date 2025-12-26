<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    protected $fillable = [
        'business_id',
        'name',
        'email',
        'phone',
        'message',
        'created_by'
    ];
}
