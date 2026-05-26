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
        
        $thermostats = $allDevices->whereIn('type', ['thermostat', 'ac']);
        $lights = $allDevices->where('type', 'light');
        $cameras = $allDevices->whereIn('type', ['camera', 'refrigerator', 'tv', 'purifier', 'speaker', 'ac']);
        
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

        // If no data, populate with realistic dummy data for demonstration
        if ($energyData->isEmpty()) {
            $currentHour = Carbon::now()->hour;
            for ($i = 0; $i <= $currentHour; $i++) {
                $base = ($i >= 18 && $i <= 22) ? 2.5 : (($i >= 7 && $i <= 10) ? 1.5 : 0.5);
                $energyChartData[$i] = $base + (rand(0, 100) / 100);
            }
        }

        $dailyTotalEnergy = array_sum($energyChartData);
        $currentLoad = count($energyData) > 0 ? $energyData->last()->kilowatt_hours : 0;
        
        // Estimated Cost (Say ₹8 per kWh)
        $estimatedCost = $dailyTotalEnergy * 8;

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
