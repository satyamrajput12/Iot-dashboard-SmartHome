<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kilowatt_hours',
        'hour_recorded',
        'date_recorded',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
