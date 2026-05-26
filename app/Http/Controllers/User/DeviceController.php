<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * DeviceController
 * Handles device registration, editing, deletion, and control (toggle ON/OFF).
 * All actions are scoped to the currently authenticated user.
 */
class DeviceController extends Controller
{
    /**
     * Show all devices for the logged-in user (with search/filter/pagination).
     */
    public function index(Request $request)
    {
        $query = Device::where('user_id', auth()->id());

        // Apply filters
        if ($request->filled('type'))     $query->where('type', $request->type);
        if ($request->filled('status'))   $query->where('approval_status', $request->status);
        if ($request->filled('location')) $query->where('location', 'like', '%' . $request->location . '%');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('device_id', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $devices   = $query->latest()->paginate(8)->appends($request->query());
        $locations = Device::where('user_id', auth()->id())->distinct()->pluck('location');

        return view('user.devices.index', compact('devices', 'locations'));
    }

    /**
     * Show the form to register a new device.
     */
    public function create()
    {
        return view('user.devices.create');
    }

    /**
     * Save the new device to the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:thermostat,light,alarm,camera,smart_plug,door_lock,motion_sensor,smoke_detector,water_sensor,blinds,speaker,vacuum,air_purifier,tv,garage_door,fridge,oven,washer,dryer,kettle,fan,mirror,pet_feeder,plant_monitor',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'temperature' => 'nullable|numeric|min:-50|max:100',
        ]);

        // Create the device - always starts as pending approval
        $device = Device::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'type'        => $request->type,
            'location'    => $request->location,
            'description' => $request->description,
            'temperature' => $request->type === 'thermostat' ? $request->temperature : null,
            'status'      => 'off',
            'approval_status' => 'pending',
            'device_id'   => Device::generateDeviceId(),
        ]);

        // Log this registration event
        DeviceLog::createLog(
            $device->id,
            auth()->id(),
            'info',
            'Device Registered',
            "Device '{$device->name}' was registered and is awaiting admin approval."
        );

        return redirect()->route('user.devices.index')
            ->with('success', "Device '{$device->name}' registered successfully! Awaiting admin approval.");
    }

    /**
     * Show the form to edit an existing device.
     */
    public function edit(Device $device)
    {
        // Ensure the device belongs to the authenticated user
        $this->authorizeDevice($device);

        return view('user.devices.edit', compact('device'));
    }

    /**
     * Update device details in the database.
     */
    public function update(Request $request, Device $device)
    {
        $this->authorizeDevice($device);

        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:thermostat,light,alarm,camera,smart_plug,door_lock,motion_sensor,smoke_detector,water_sensor,blinds,speaker,vacuum,air_purifier,tv,garage_door,fridge,oven,washer,dryer,kettle,fan,mirror,pet_feeder,plant_monitor',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'temperature' => 'nullable|numeric|min:-50|max:100',
        ]);

        $device->update([
            'name'        => $request->name,
            'type'        => $request->type,
            'location'    => $request->location,
            'description' => $request->description,
            'temperature' => $request->type === 'thermostat' ? $request->temperature : null,
        ]);

        DeviceLog::createLog(
            $device->id,
            auth()->id(),
            'info',
            'Device Updated',
            "Device settings were updated by the owner."
        );

        return redirect()->route('user.devices.index')
            ->with('success', "Device '{$device->name}' updated successfully.");
    }

    /**
     * Delete a device and all its logs.
     */
    public function destroy(Device $device)
    {
        $this->authorizeDevice($device);

        $deviceName = $device->name;
        $device->delete(); // Cascade deletes logs too

        return redirect()->route('user.devices.index')
            ->with('success', "Device '{$deviceName}' has been removed.");
    }

    /**
     * Show the device details and its log history.
     */
    public function show(Device $device)
    {
        $this->authorizeDevice($device);

        $logs = $device->logs()->latest()->paginate(10);

        return view('user.devices.show', compact('device', 'logs'));
    }

    // =============================================
    // DEVICE CONTROL (Toggle ON/OFF)
    // =============================================

    /**
     * Toggle the device ON or OFF via AJAX.
     * Returns JSON response for real-time update without page refresh.
     */
    public function toggle(Device $device)
    {
        $this->authorizeDevice($device);

        // Only approved devices can be controlled
        if (!$device->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'Device must be approved before it can be controlled.',
            ], 403);
        }

        // Toggle the status
        $newStatus = $device->status === 'on' ? 'off' : 'on';
        $device->update([
            'status'    => $newStatus,
            'last_seen' => now(),
        ]);

        // Log the control action
        DeviceLog::createLog(
            $device->id,
            auth()->id(),
            'control',
            'Device Toggled',
            "Device was turned {$newStatus} by user.",
            ['previous_status' => $device->status, 'new_status' => $newStatus]
        );

        // Simulate occasional random error for demo purposes (10% chance)
        if (rand(1, 10) === 1) {
            DeviceLog::createLog(
                $device->id,
                auth()->id(),
                'warning',
                'Connection Warning',
                'Simulated: Brief connectivity issue detected during state change. Recovered automatically.'
            );
        }

        return response()->json([
            'success'   => true,
            'status'    => $newStatus,
            'message'   => "Device turned {$newStatus} successfully.",
            'last_seen' => now()->diffForHumans(),
        ]);
    }

    /**
     * Update thermostat temperature via AJAX.
     */
    public function updateTemperature(Request $request, Device $device)
    {
        $this->authorizeDevice($device);

        if ($device->type !== 'thermostat') {
            return response()->json(['success' => false, 'message' => 'Only thermostats support temperature control.'], 400);
        }

        $request->validate(['temperature' => 'required|numeric|min:10|max:35']);

        $oldTemp = $device->temperature;
        $device->update([
            'temperature' => $request->temperature,
            'last_seen'   => now(),
        ]);

        DeviceLog::createLog(
            $device->id,
            auth()->id(),
            'control',
            'Temperature Updated',
            "Temperature changed from {$oldTemp}°C to {$request->temperature}°C.",
            ['old_temperature' => $oldTemp, 'new_temperature' => $request->temperature]
        );

        return response()->json([
            'success'     => true,
            'temperature' => $device->temperature,
            'message'     => "Temperature set to {$request->temperature}°C.",
        ]);
    }

    /**
     * Update device brightness via AJAX.
     */
    public function updateBrightness(Request $request, Device $device)
    {
        $this->authorizeDevice($device);

        if ($device->type !== 'light') {
            return response()->json(['success' => false, 'message' => 'Only lights support brightness control.'], 400);
        }

        $request->validate(['brightness' => 'required|integer|min:0|max:100']);

        $device->update([
            'brightness' => $request->brightness,
            'last_seen'  => now(),
        ]);

        return response()->json([
            'success'    => true,
            'brightness' => $device->brightness,
            'message'    => "Brightness set to {$request->brightness}%.",
        ]);
    }

    /**
     * Update device mode via AJAX.
     */
    public function updateMode(Request $request, Device $device)
    {
        $this->authorizeDevice($device);

        $request->validate(['mode' => 'required|string|max:50']);

        $device->update([
            'mode'      => $request->mode,
            'last_seen' => now(),
        ]);

        return response()->json([
            'success' => true,
            'mode'    => $device->mode,
            'message' => "Mode set to {$request->mode}.",
        ]);
    }

    // =============================================
    // REAL-TIME STATUS (AJAX polling)
    // =============================================

    /**
     * Return the current status of all user devices as JSON.
     * Used by the frontend to poll for real-time updates.
     */
    public function getStatuses()
    {
        $devices = Device::where('user_id', auth()->id())
            ->select('id', 'name', 'status', 'temperature', 'last_seen', 'approval_status')
            ->get()
            ->keyBy('id');

        return response()->json($devices);
    }

    // =============================================
    // PRIVATE HELPERS
    // =============================================

    /**
     * Ensure the device belongs to the currently authenticated user.
     */
    private function authorizeDevice(Device $device): void
    {
        if ($device->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to access this device.');
        }
    }
}
