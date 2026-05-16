<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Device;
use App\Models\Alert;
use App\Models\EnergyUsage;
use Carbon\Carbon;

class AdvancedDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // 1. Add Cameras
            Device::create([
                'user_id' => $user->id,
                'name' => 'Front Door Camera',
                'type' => 'camera',
                'location' => 'Front Door',
                'status' => 'on',
                'approval_status' => 'approved',
                'device_id' => Device::generateDeviceId(),
                'stream_url' => 'https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ]);

            Device::create([
                'user_id' => $user->id,
                'name' => 'Backyard Camera',
                'type' => 'camera',
                'location' => 'Backyard',
                'status' => 'on',
                'approval_status' => 'approved',
                'device_id' => Device::generateDeviceId(),
                'stream_url' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60',
            ]);

            // 2. Add Alerts
            Alert::create([
                'user_id' => $user->id,
                'type' => 'warning',
                'message' => 'Motion detected at Front Door',
                'is_read' => false,
                'created_at' => now()->subMinutes(15),
            ]);

            Alert::create([
                'user_id' => $user->id,
                'type' => 'danger',
                'message' => 'Low Battery Smart Lock',
                'is_read' => false,
                'created_at' => now()->subHours(2),
            ]);

            // 3. Add Hourly Energy Usage for Today
            $today = Carbon::today()->toDateString();
            $pattern = [0.5, 0.4, 0.4, 0.5, 0.8, 1.2, 1.5, 1.8, 1.4, 1.1, 1.0, 1.2, 1.5, 2.0, 2.2, 2.5, 3.0, 2.8, 2.5, 2.0, 1.5, 1.0, 0.8, 0.6];
            
            for ($i = 0; $i < 24; $i++) {
                // Introduce slight randomness to the pattern
                $usage = max(0, $pattern[$i] + (rand(-2, 2) / 10));
                
                EnergyUsage::create([
                    'user_id' => $user->id,
                    'kilowatt_hours' => $usage,
                    'hour_recorded' => $i,
                    'date_recorded' => $today,
                ]);
            }
            
            // 4. Update existing lights with brightness
            Device::where('user_id', $user->id)->where('type', 'light')->update([
                'brightness' => rand(40, 100),
            ]);
            
            // 5. Update existing thermostats with target temp and mode
            Device::where('user_id', $user->id)->where('type', 'thermostat')->update([
                'target_temperature' => 22.0,
                'mode' => 'Cool',
            ]);
        }
    }
}
