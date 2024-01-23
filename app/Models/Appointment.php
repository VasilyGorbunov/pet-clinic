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
        'clinic_id',
        'doctor_id',
        'description',
        'date',
        'status',
    ];

    protected $casts = [
        'status' => AppointmentsStatus::class,
        'date' => 'datetime'
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
