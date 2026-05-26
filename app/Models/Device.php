<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Device Model
 * Represents an IoT smart home device (thermostat, light, alarm)
 */
class Device extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'location',
        'status',
        'temperature',
        'brightness',
        'mode',
        'target_temperature',
        'stream_url',
        'approval_status',
        'rejection_reason',
        'device_id',
        'description',
        'image',
        'last_seen',
    ];

    /**
     * Type casting for fields
     */
    protected $casts = [
        'temperature' => 'decimal:2',
        'last_seen'   => 'datetime',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    /**
     * Get the user who owns this device.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all logs for this device.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(DeviceLog::class);
    }

    // =============================================
    // SCOPES (for filtering)
    // =============================================

    /**
     * Filter by device type (thermostat, light, alarm).
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Filter by location.
     */
    public function scopeInLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Only return approved devices.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Only return pending devices.
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // =============================================
    // HELPER METHODS
    // =============================================

    /**
     * Check if the device is currently ON.
     */
    public function isOn(): bool
    {
        return $this->status === 'on';
    }

    /**
     * Check if the device is approved by admin.
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if the device is pending approval.
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Get a human-friendly label for the device type.
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'thermostat' => '🌡️ Thermostat',
            'light'      => '💡 Light',
            'alarm'      => '🔔 Alarm',
            'camera'     => '📹 Camera',
            'smart_plug' => '🔌 Smart Plug',
            'door_lock'  => '🔒 Door Lock',
            'motion_sensor'=> '🚶 Motion Sensor',
            'smoke_detector'=> '💨 Smoke Detector',
            'water_sensor'=> '💧 Water Leak Sensor',
            'blinds'     => '🪟 Smart Blinds',
            'speaker'    => '🔊 Smart Speaker',
            'vacuum'     => '🧹 Robot Vacuum',
            'air_purifier'=> '🌬️ Air Purifier',
            'tv'         => '📺 Smart TV',
            'garage_door'=> '🚪 Garage Door',
            'fridge'     => '🧊 Smart Fridge',
            'oven'       => '♨️ Smart Oven',
            'washer'     => '🧺 Smart Washer',
            'dryer'      => '👕 Smart Dryer',
            'kettle'     => '☕ Smart Kettle',
            'fan'        => '🌀 Smart Fan',
            'mirror'     => '🪞 Smart Mirror',
            'pet_feeder' => '🐕 Pet Feeder',
            'plant_monitor'=> '🌱 Plant Monitor',
            default      => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    /**
     * Get Bootstrap badge class based on approval status.
     */
    public function getApprovalBadgeClass(): string
    {
        return match ($this->approval_status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default    => 'warning',
        };
    }

    /**
     * Get icon class for device type.
     */
    public function getTypeIcon(): string
    {
        return match ($this->type) {
            'thermostat' => 'bi-thermometer-half',
            'light'      => 'bi-lightbulb-fill',
            'alarm'      => 'bi-bell-fill',
            'camera'     => 'bi-camera-video-fill',
            'smart_plug' => 'bi-plug-fill',
            'door_lock'  => 'bi-lock-fill',
            'motion_sensor'=> 'bi-person-walking',
            'smoke_detector'=> 'bi-cloud-haze-fill',
            'water_sensor'=> 'bi-water',
            'blinds'     => 'bi-window-sidebar',
            'speaker'    => 'bi-speaker-fill',
            'vacuum'     => 'bi-robot',
            'air_purifier'=> 'bi-wind',
            'tv'         => 'bi-tv-fill',
            'garage_door'=> 'bi-door-open-fill',
            'fridge'     => 'bi-snow',
            'oven'       => 'bi-fire',
            'washer'     => 'bi-moisture',
            'dryer'      => 'bi-wind',
            'kettle'     => 'bi-cup-hot-fill',
            'fan'        => 'bi-fan',
            'mirror'     => 'bi-display',
            'pet_feeder' => 'bi-hearts',
            'plant_monitor'=> 'bi-flower1',
            default      => 'bi-cpu',
        };
    }

    /**
     * Generate a unique simulated device ID.
     */
    public static function generateDeviceId(): string
    {
        return 'IOT-' . strtoupper(substr(md5(uniqid()), 0, 8));
    }
}
