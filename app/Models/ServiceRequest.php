<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_name',
        'address',
        'amount',
        'razorpay_payment_id',
        'payment_status',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
