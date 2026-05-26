<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class IndustrialModule extends Model {
    use HasFactory;
    protected $fillable = ['name', 'slug', 'category', 'icon', 'short_description', 'long_description'];
    public function devices() {
        return $this->hasMany(IndustrialDevice::class);
    }
}