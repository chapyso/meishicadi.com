<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user',
        'category',
        'business',
        'total_days',
        'total_cost',
        'start_date',
        'end_date',
        'payment_method',
        'status',
        'approval',
        'created_by',
    ];

    public function categories()
    {
        return $this->hasOne('App\Models\BusinessCategory', 'id', 'category');
    }

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user');
    }

    public function businesses()
    {
        return $this->hasOne('App\Models\Business', 'id', 'business');
    }
}
