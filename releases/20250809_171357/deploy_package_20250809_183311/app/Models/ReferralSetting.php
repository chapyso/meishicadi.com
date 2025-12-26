<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'commision',
        'threshold_amount',
        'guidelines',
        'is_enable',
        'created_by',
    ];
}
