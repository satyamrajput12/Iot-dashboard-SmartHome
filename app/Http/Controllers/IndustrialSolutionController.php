<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\IndustrialModule;
use App\Models\IndustrialDevice;
use App\Models\IndustrialTelemetry;

class IndustrialSolutionController extends Controller
{
    public function show($slug)
    {
        $module = IndustrialModule::where('slug', $slug)->with('devices')->firstOrFail();
        
        $totalDevices = $module->devices->count();
        $onlineDevices = $module->devices->where('status', 'online')->count();
        
        return view('solutions.show', compact('module', 'totalDevices', 'onlineDevices'));
    }

    public function telemetryApi($slug)
    {
        $module = IndustrialModule::where('slug', $slug)->firstOrFail();
        
        // Fetch last 20 telemetry points for the first device of this module for the chart
        $device = $module->devices()->first();
        if(!$device) {
            return response()->json(['labels' => [], 'data' => []]);
        }

        $telemetry = $device->telemetry()->orderBy('recorded_at', 'asc')->take(20)->get();
        
        $labels = $telemetry->map(function($t) {
            return $t->recorded_at->format('H:i');
        });
        $data = $telemetry->pluck('value');

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'metric' => $telemetry->first()->metric_name ?? 'Metric',
            'unit' => $telemetry->first()->unit ?? ''
        ]);
    }
}