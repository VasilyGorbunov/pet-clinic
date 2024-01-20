<?php

namespace App\Models;

use App\Enums\AppointmentsStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected static $unguarded = false;

    protected $fillable = [
        'pet_id',
        'slot_id',
        'description',
    ];

    protected $casts = [
        'status' => AppointmentsStatus::class,
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }
}
