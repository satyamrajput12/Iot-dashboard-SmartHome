<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * AdminController
 * Full admin panel - manage users, approve/reject devices, view all logs.
 */
class AdminController extends Controller
{
    // =============================================
    // ADMIN DASHBOARD
    // =============================================

    /**
     * Admin overview dashboard with statistics.
     */
    public function dashboard()
    {
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_devices'  => Device::count(),
            'pending'        => Device::where('approval_status', 'pending')->count(),
            'approved'       => Device::where('approval_status', 'approved')->count(),
            'rejected'       => Device::where('approval_status', 'rejected')->count(),
            'online_devices' => Device::where('status', 'on')->count(),
            'total_logs'     => DeviceLog::count(),
            'error_logs'     => DeviceLog::where('log_type', 'error')->count(),
        ];

        // Device type distribution for chart
        $deviceTypes = Device::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        // Recent activity
        $recentLogs = DeviceLog::with(['device', 'user'])
            ->latest()
            ->take(8)
            ->get();

        // Pending devices needing review
        $pendingDevices = Device::with('user')
            ->where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'deviceTypes', 'recentLogs', 'pendingDevices'));
    }

    // =============================================
    // DEVICE MANAGEMENT
    // =============================================

    /**
     * List all devices across all users (admin view).
     */
    public function devices(Request $request)
    {
        $query = Device::with('user');

        // Apply filters
        if ($request->filled('type'))     $query->where('type', $request->type);
        if ($request->filled('approval')) $query->where('approval_status', $request->approval);
        if ($request->filled('user_id'))  $query->where('user_id', $request->user_id);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('device_id', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $devices = $query->latest()->paginate(10)->appends($request->query());
        $users   = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.devices.index', compact('devices', 'users'));
    }

    /**
     * Approve a device registration.
     */
    public function approveDevice(Device $device)
    {
        $device->update([
            'approval_status'  => 'approved',
            'rejection_reason' => null,
        ]);

        DeviceLog::createLog(
            $device->id,
            auth()->id(),
            'info',
            'Device Approved',
            "Device was approved by admin " . auth()->user()->name . "."
        );

        return back()->with('success', "Device '{$device->name}' has been approved.");
    }

    /**
     * Reject a device registration with a reason.
     */
    public function rejectDevice(Request $request, Device $device)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $device->update([
            'approval_status'  => 'rejected',
            'rejection_reason' => $request->reason,
            'status'           => 'off', // Turn off if it was on
        ]);

        DeviceLog::createLog(
            $device->id,
            auth()->id(),
            'warning',
            'Device Rejected',
            "Device was rejected by admin. Reason: {$request->reason}"
        );

        return back()->with('success', "Device '{$device->name}' has been rejected.");
    }

    /**
     * Admin can delete any device.
     */
    public function deleteDevice(Device $device)
    {
        $name = $device->name;
        $device->delete();

        return back()->with('success', "Device '{$name}' has been permanently deleted.");
    }

    // =============================================
    // USER MANAGEMENT
    // =============================================

    /**
     * List all users.
     */
    public function users(Request $request)
    {
        $query = User::withCount('devices');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('is_active', $request->status);

        $users = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle user account active/inactive.
     */
    public function toggleUser(User $user)
    {
        // Prevent disabling your own admin account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User '{$user->name}' has been {$status}.");
    }

    /**
     * Delete a user and all their devices.
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "User '{$name}' and all their devices have been deleted.");
    }

    // =============================================
    // LOGS / TROUBLESHOOTING
    // =============================================

    /**
     * View all device logs (admin-level overview).
     */
    public function logs(Request $request)
    {
        $query = DeviceLog::with(['device', 'user']);

        if ($request->filled('type'))      $query->where('log_type', $request->type);
        if ($request->filled('device_id')) $query->where('device_id', $request->device_id);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $logs    = $query->latest()->paginate(15)->appends($request->query());
        $devices = Device::orderBy('name')->get();

        return view('admin.logs', compact('logs', 'devices'));
    }

    /**
     * Simulate a random error on a device (for demo/testing).
     */
    public function simulateError(Device $device)
    {
        $errors = [
            ['action' => 'Connection Timeout', 'message' => 'Device failed to respond within 5 seconds. Check network connectivity.'],
            ['action' => 'Sensor Failure',     'message' => 'Temperature sensor returned invalid reading. Hardware check required.'],
            ['action' => 'Power Fluctuation',  'message' => 'Irregular power supply detected. Device may behave unexpectedly.'],
            ['action' => 'Memory Overflow',    'message' => 'Device internal buffer overflow. Restart recommended.'],
            ['action' => 'Firmware Error',     'message' => 'Firmware checksum mismatch. Update required.'],
        ];

        $error = $errors[array_rand($errors)];

        DeviceLog::createLog(
            $device->id,
            auth()->id(),
            'error',
            $error['action'],
            '[SIMULATED] ' . $error['message']
        );

        return back()->with('success', "Simulated error generated for '{$device->name}'.");
    }
}
