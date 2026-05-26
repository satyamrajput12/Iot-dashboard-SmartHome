<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User Model
 * Handles authentication and role-based access control (RBAC)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // =============================================
    // ROLE HELPER METHODS
    // =============================================

    /**
     * Check if the user has the admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get the user's profile photo URL or a default avatar.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // =============================================
    // RELATIONSHIPS
    // =============================================

    /**
     * Get all devices registered by this user.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Get all device logs created by this user.
     */
    public function deviceLogs(): HasMany
    {
        return $this->hasMany(DeviceLog::class);
    }

    public function energyLogs()
    {
        return $this->hasMany(EnergyLog::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    // =============================================
    // SCOPES
    // =============================================

    /**
     * Scope to get only admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope to get only regular users.
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user');
    }
}
