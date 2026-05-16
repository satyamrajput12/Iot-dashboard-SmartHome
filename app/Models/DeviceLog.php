<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DeviceLog Model
 * Records all device events, errors, and control actions for troubleshooting
 */
class DeviceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'user_id',
        'log_type',
        'action',
        'message',
        'ip_address',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    /**
     * Get the device this log belongs to.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the user who triggered this log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // =============================================
    // SCOPES
    // =============================================

    /**
     * Filter logs by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('log_type', $type);
    }

    /**
     * Get only error logs.
     */
    public function scopeErrors($query)
    {
        return $query->where('log_type', 'error');
    }

    // =============================================
    // HELPER METHODS
    // =============================================

    /**
     * Get Bootstrap badge class for log type.
     */
    public function getBadgeClass(): string
    {
        return match ($this->log_type) {
            'info'    => 'info',
            'warning' => 'warning',
            'error'   => 'danger',
            'control' => 'primary',
            default   => 'secondary',
        };
    }

    /**
     * Get icon for log type.
     */
    public function getIcon(): string
    {
        return match ($this->log_type) {
            'info'    => 'bi-info-circle',
            'warning' => 'bi-exclamation-triangle',
            'error'   => 'bi-x-circle',
            'control' => 'bi-toggle-on',
            default   => 'bi-dot',
        };
    }

    /**
     * Static helper: Create a log entry easily.
     */
    public static function createLog(
        int $deviceId,
        ?int $userId,
        string $logType,
        string $action,
        string $message,
        ?array $metadata = null
    ): self {
        return self::create([
            'device_id'  => $deviceId,
            'user_id'    => $userId,
            'log_type'   => $logType,
            'action'     => $action,
            'message'    => $message,
            'ip_address' => request()->ip(),
            'metadata'   => $metadata,
        ]);
    }
}
