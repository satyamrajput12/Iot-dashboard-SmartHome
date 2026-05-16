<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class EnergyController extends Controller
{
    public function index()
    {
        // Simulated energy data for the module
        $devices = auth()->user()->devices;
        
        $energyChartData = [12, 19, 15, 8, 22, 30, 25]; // Simulated weekly data
        $monthlyCostEst = 45.50;
        
        $deviceUsage = $devices->map(function($d) {
            return (object)[
                'name' => $d->name,
                'location' => $d->location,
                'usage_kwh' => rand(50, 500) / 10, // Simulated 5.0 to 50.0
                'is_on' => $d->isOn()
            ];
        })->sortByDesc('usage_kwh');

        return view('user.energy.index', compact('energyChartData', 'monthlyCostEst', 'deviceUsage'));
    }
}
