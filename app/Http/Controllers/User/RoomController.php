<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class RoomController extends Controller
{
    public function index()
    {
        $devices = auth()->user()->devices;
        $roomsData = [];
        
        $locations = $devices->pluck('location')->unique()->filter();
        
        foreach($locations as $loc) {
            $roomDevices = $devices->where('location', $loc);
            $total = $roomDevices->count();
            $active = $roomDevices->filter(function($d) { return $d->isOn(); })->count();
            
            // Try to find a thermostat for room temp, else simulated
            $thermostat = $roomDevices->where('type', 'thermostat')->first();
            $temp = $thermostat ? ($thermostat->temperature ?? 22) : rand(18, 26);
            
            // Health score % (devices not rejected / total)
            $working = $roomDevices->where('approval_status', '!=', 'rejected')->count();
            $health = $total > 0 ? round(($working / $total) * 100) : 100;
            
            $roomsData[] = (object)[
                'name' => $loc,
                'deviceCount' => $total,
                'activeCount' => $active,
                'temperature' => $temp,
                'health' => $health
            ];
        }

        return view('user.rooms.index', compact('roomsData'));
    }
}
