<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder
 * Seeds the database with sample admin, users, devices, and logs.
 * Run: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // CREATE ADMIN USER
        // =============================================
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@iot.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'is_active' => true,
        ]);

        // =============================================
        // CREATE REGULAR USERS
        // =============================================
        $alice = User::create([
            'name'     => 'Alice Johnson',
            'email'    => 'alice@iot.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'is_active' => true,
        ]);

        $bob = User::create([
            'name'     => 'Bob Smith',
            'email'    => 'bob@iot.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'is_active' => true,
        ]);

        $carol = User::create([
            'name'     => 'Carol White',
            'email'    => 'carol@iot.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'is_active' => false, // Deactivated account example
        ]);

        // =============================================
        // CREATE DEVICES FOR ALICE
        // =============================================
        $devices = [
            // Alice's devices
            [
                'user_id'         => $alice->id,
                'name'            => 'Living Room Thermostat',
                'type'            => 'thermostat',
                'location'        => 'Living Room',
                'status'          => 'on',
                'temperature'     => 22.5,
                'approval_status' => 'approved',
                'device_id'       => 'IOT-LRT001A',
                'description'     => 'Main living room temperature control',
                'last_seen'       => now()->subMinutes(5),
            ],
            [
                'user_id'         => $alice->id,
                'name'            => 'Kitchen Lights',
                'type'            => 'light',
                'location'        => 'Kitchen',
                'status'          => 'on',
                'approval_status' => 'approved',
                'device_id'       => 'IOT-KCL002A',
                'description'     => 'Smart kitchen ceiling lights',
                'last_seen'       => now()->subMinutes(2),
            ],
            [
                'user_id'         => $alice->id,
                'name'            => 'Front Door Alarm',
                'type'            => 'alarm',
                'location'        => 'Entrance',
                'status'          => 'off',
                'approval_status' => 'approved',
                'device_id'       => 'IOT-FDA003A',
                'description'     => 'Motion-triggered front door security alarm',
                'last_seen'       => now()->subHours(1),
            ],
            [
                'user_id'         => $alice->id,
                'name'            => 'Bedroom Lights',
                'type'            => 'light',
                'location'        => 'Bedroom',
                'status'          => 'off',
                'approval_status' => 'approved',
                'device_id'       => 'IOT-BDL004A',
                'description'     => 'Smart bedroom ambient lights',
                'last_seen'       => now()->subHours(3),
            ],
            [
                'user_id'         => $alice->id,
                'name'            => 'Garage Alarm',
                'type'            => 'alarm',
                'location'        => 'Garage',
                'status'          => 'off',
                'approval_status' => 'pending',
                'device_id'       => 'IOT-GRG005A',
                'description'     => 'Awaiting admin approval',
                'last_seen'       => null,
            ],

            // Bob's devices
            [
                'user_id'         => $bob->id,
                'name'            => 'Office Thermostat',
                'type'            => 'thermostat',
                'location'        => 'Home Office',
                'status'          => 'on',
                'temperature'     => 20.0,
                'approval_status' => 'approved',
                'device_id'       => 'IOT-OFT001B',
                'description'     => 'Home office temperature controller',
                'last_seen'       => now()->subMinutes(10),
            ],
            [
                'user_id'         => $bob->id,
                'name'            => 'Patio Lights',
                'type'            => 'light',
                'location'        => 'Patio',
                'status'          => 'off',
                'approval_status' => 'approved',
                'device_id'       => 'IOT-PTL002B',
                'description'     => 'Outdoor patio string lights',
                'last_seen'       => now()->subHours(6),
            ],
            [
                'user_id'         => $bob->id,
                'name'            => 'Basement Alarm',
                'type'            => 'alarm',
                'location'        => 'Basement',
                'status'          => 'off',
                'approval_status' => 'rejected',
                'rejection_reason' => 'Device ID already registered under another account.',
                'device_id'       => 'IOT-BSM003B',
                'description'     => 'Basement intrusion detector',
                'last_seen'       => null,
            ],
            [
                'user_id'         => $bob->id,
                'name'            => 'Bathroom Thermostat',
                'type'            => 'thermostat',
                'location'        => 'Bathroom',
                'status'          => 'on',
                'temperature'     => 24.0,
                'approval_status' => 'pending',
                'device_id'       => 'IOT-BTH004B',
                'description'     => 'Heated floor controller',
                'last_seen'       => null,
            ],
        ];

        $createdDevices = [];
        foreach ($devices as $deviceData) {
            $createdDevices[] = Device::create($deviceData);
        }

        // =============================================
        // CREATE DEVICE LOGS
        // =============================================
        $logTemplates = [
            ['log_type' => 'info',    'action' => 'Device Registered',   'message' => 'Device successfully registered and awaiting approval.'],
            ['log_type' => 'info',    'action' => 'Device Approved',      'message' => 'Device was approved by administrator.'],
            ['log_type' => 'control', 'action' => 'Device Turned ON',     'message' => 'Device was powered on by the user.'],
            ['log_type' => 'control', 'action' => 'Device Turned OFF',    'message' => 'Device was powered off by the user.'],
            ['log_type' => 'info',    'action' => 'Status Check',         'message' => 'Routine status check completed successfully.'],
            ['log_type' => 'warning', 'action' => 'Connection Unstable',  'message' => 'Device experienced intermittent connectivity. Reconnected automatically.'],
            ['log_type' => 'error',   'action' => 'Sensor Error',         'message' => '[SIMULATED] Temperature sensor returned invalid data. Manual check recommended.'],
            ['log_type' => 'error',   'action' => 'Network Timeout',      'message' => '[SIMULATED] Device failed to respond within the expected timeout window.'],
            ['log_type' => 'warning', 'action' => 'Low Battery Warning',  'message' => 'Battery level below 20%. Consider replacing batteries soon.'],
            ['log_type' => 'info',    'action' => 'Firmware Updated',     'message' => 'Device firmware updated to latest version successfully.'],
        ];

        // Add logs to the first 6 devices
        foreach (array_slice($createdDevices, 0, 6) as $i => $device) {
            // Shuffle and add 3-6 random logs per device
            shuffle($logTemplates);
            $count = rand(3, 6);
            foreach (array_slice($logTemplates, 0, $count) as $log) {
                DeviceLog::create([
                    'device_id'  => $device->id,
                    'user_id'    => $device->user_id,
                    'log_type'   => $log['log_type'],
                    'action'     => $log['action'],
                    'message'    => $log['message'],
                    'ip_address' => '192.168.1.' . rand(1, 50),
                    'created_at' => now()->subHours(rand(1, 72)),
                    'updated_at' => now()->subHours(rand(1, 72)),
                ]);
            }
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📋 Login Credentials:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@iot.com', 'password'],
                ['User',  'alice@iot.com', 'password'],
                ['User',  'bob@iot.com',   'password'],
            ]
        );
    }
}
