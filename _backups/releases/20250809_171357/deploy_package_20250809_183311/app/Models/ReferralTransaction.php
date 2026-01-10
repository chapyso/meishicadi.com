<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'plan_id',
        'plan_price',
        'commission',
        'minimum_threshold_amount',
        'referral_code',
        'status',
        'req_amount',
        'req_user_id'
    ];

    public function getPlan()
    {
        return $this->hasOne('App\Models\Plan', 'id', 'plan_id');
    }

    public function getUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getCompany()
    {
        return $this->hasOne('App\Models\User', 'id', 'request_user_id');
    }

    public function getreferralUser($refrral_code)
    {
        return User::where('referral_code',$refrral_code)->select('name')->first()->name;
    }
}
