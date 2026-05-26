<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class IndustrialTelemetry extends Model {
    use HasFactory;
    protected $fillable = ['industrial_device_id', 'metric_name', 'value', 'unit', 'recorded_at'];
    protected $casts = [
        'recorded_at' => 'datetime',
    ];
    public function device() {
        return $this->belongsTo(IndustrialDevice::class, 'industrial_device_id');
    }
}