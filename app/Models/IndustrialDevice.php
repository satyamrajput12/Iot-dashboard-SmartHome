<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class IndustrialDevice extends Model {
    use HasFactory;
    protected $fillable = ['industrial_module_id', 'name', 'location', 'status'];
    public function module() {
        return $this->belongsTo(IndustrialModule::class, 'industrial_module_id');
    }
    public function telemetry() {
        return $this->hasMany(IndustrialTelemetry::class);
    }
}