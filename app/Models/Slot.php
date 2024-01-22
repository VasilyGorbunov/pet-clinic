<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $formatted_time
 */
class Slot extends Model
{
    use HasFactory;

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];


    /**
     * @return Attribute
     */
    protected function formattedTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) =>
                Carbon::parse($attributes['start'])->format("H:i") . ' - ' .
                Carbon::parse($attributes['end'])->format("H:i")
        );
    }

    public function appointment(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}
