<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Alert;
use App\Models\EnergyUsage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Devices (Grouped by type for the new UI widgets)
        $allDevices = Device::where('user_id', $user->id)->get();
        
        $thermostats = $allDevices->where('type', 'thermostat');
        $lights = $allDevices->where('type', 'light');
        $cameras = $allDevices->where('type', 'camera');
        
        // Count Active Devices
        $activeDevicesCount = $allDevices->where('status', 'on')->count();

        // 2. Alerts (Unread only)
        $alerts = Alert::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        // 3. Energy Usage (Today's hourly data)
        $today = Carbon::today()->toDateString();
        $energyData = EnergyUsage::where('user_id', $user->id)
            ->where('date_recorded', $today)
            ->orderBy('hour_recorded')
            ->get();

        // Prepare chart array
        $energyChartData = array_fill(0, 24, 0); // Default 0 for 24 hours
        foreach ($energyData as $usage) {
            $energyChartData[$usage->hour_recorded] = $usage->kilowatt_hours;
        }

        $dailyTotalEnergy = array_sum($energyChartData);
        $currentLoad = count($energyData) > 0 ? $energyData->last()->kilowatt_hours : 0;
        
        // Estimated Cost (Say $0.24 per kWh)
        $estimatedCost = $dailyTotalEnergy * 0.24;

        // Ensure we pass everything to the view
        return view('user.dashboard', compact(
            'allDevices',
            'thermostats',
            'lights',
            'cameras',
            'activeDevicesCount',
            'alerts',
            'energyChartData',
            'dailyTotalEnergy',
            'currentLoad',
            'estimatedCost'
        ));
    }
}
