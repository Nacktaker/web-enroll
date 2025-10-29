<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentSetting extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the current enrollment period settings
     */
    public static function getCurrentPeriod()
    {
        return static::latest()->first() ?? static::create([
            'start_date' => now()->addWeeks(2),
            'end_date' => now()->addWeeks(4),
        ]);
    }
}