<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plan_request extends Model
{
    use HasFactory;
    
    protected $table = 'plan_requests';
    
    protected $fillable = [
        'user_id',
        'plan_id',
        'duration',
        'notes',
        'status',
        'request_date',
    ];

    public function plan()
    {
        return $this->hasOne('App\Models\Plan', 'id', 'plan_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Check if this plan request is orphaned (missing user or plan)
     *
     * @return bool
     */
    public function isOrphaned()
    {
        return !$this->user || !$this->plan;
    }

    /**
     * Get the orphaned reason
     *
     * @return string|null
     */
    public function getOrphanedReason()
    {
        if (!$this->user && !$this->plan) {
            return 'User and Plan missing';
        } elseif (!$this->user) {
            return 'User missing';
        } elseif (!$this->plan) {
            return 'Plan missing';
        }
        
        return null;
    }
}
