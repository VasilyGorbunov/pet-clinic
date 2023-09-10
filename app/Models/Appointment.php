<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected static $unguarded = true;

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
