<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentStatistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'date',
        'total_appointments',
        'confirmed_appointments',
        'pending_appointments',
        'cancelled_appointments',
        'completed_appointments',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get or create statistics for a specific date and user/business
     */
    public static function getOrCreateStats($userId, $businessId = null, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return static::firstOrCreate(
            [
                'user_id' => $userId,
                'business_id' => $businessId,
                'date' => $date,
            ],
            [
                'total_appointments' => 0,
                'confirmed_appointments' => 0,
                'pending_appointments' => 0,
                'cancelled_appointments' => 0,
                'completed_appointments' => 0,
            ]
        );
    }

    /**
     * Update statistics when appointment status changes
     */
    public static function updateStats($userId, $businessId = null, $date = null)
    {
        $date = $date ?? now()->toDateString();
        $stats = static::getOrCreateStats($userId, $businessId, $date);

        $query = Appointment_deatail::where('created_by', $userId)
            ->whereDate('date', $date);

        if ($businessId) {
            $query->where('business_id', $businessId);
        }

        $appointments = $query->get();

        $stats->update([
            'total_appointments' => $appointments->count(),
            'confirmed_appointments' => $appointments->where('status', 'confirmed')->count(),
            'pending_appointments' => $appointments->where('status', 'pending')->count(),
            'cancelled_appointments' => $appointments->where('status', 'cancelled')->count(),
            'completed_appointments' => $appointments->where('status', 'completed')->count(),
        ]);

        return $stats;
    }
} 