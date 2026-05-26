<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IndustrialModule;
use App\Models\IndustrialDevice;
use App\Models\IndustrialTelemetry;
use Carbon\Carbon;
use Illuminate\Support\Str;

class IndustrialSeeder extends Seeder
{
    public function run(): void
    {
                $modules = [
            [
                'name' => 'Smart Factory', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-building',
                'short_description' => 'Enhance operational efficiency and real-time insights with our Smart Factory dashboard, optimizing production processes for increased productivity.',
                'long_description' => 'IoTdashboard provides a variety of IoT-based Smart Factory monitoring system solutions. Here are some of them:
- Enhanced Operational Efficiency: Streamlined monitoring of multiple parameters in one dashboard optimizes operational efficiency. Quickly identify bottlenecks or irregularities, minimizing downtime and improving overall productivity.
- Predictive Maintenance: Leverage real-time data to predict equipment failures before they happen, saving massive repair costs.
- Seamless Integration: Connect with your existing ERP and SCADA systems effortlessly.'
            ],
            [
                'name' => 'Energy Meter Monitoring', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-speedometer2',
                'short_description' => 'Track your factory energy consumption in real-time to identify wastage and optimize electrical efficiency.',
                'long_description' => 'IoTdashboard offers comprehensive Energy Meter Monitoring to ensure your facility runs at peak electrical efficiency.
- Real-Time Tracking: Monitor kW, kVA, and Power Factor instantly.
- Peak Load Management: Receive alerts before hitting peak demand charges.
- Automated Reporting: Generate daily/weekly consumption reports for audits.'
            ],
            [
                'name' => 'Gas Meter Monitoring', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-clipboard-data',
                'short_description' => 'Ensure safety and monitor gas flow rates across pipelines with highly accurate telemetry data.',
                'long_description' => 'Keep track of hazardous and non-hazardous gas levels securely.
- Leak Detection: Instant alerts for abnormal pressure drops indicating leaks.
- Volume Tracking: Measure exact volumetric flow for accurate billing and internal allocation.
- Regulatory Compliance: Maintain historical data logs required for safety audits.'
            ],
            [
                'name' => 'Water meter Monitoring', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-droplet',
                'short_description' => 'Monitor industrial water usage, detect leaks, and ensure sustainable resource management.',
                'long_description' => 'Water is a critical resource; manage it effectively with our solutions.
- Flow Rate Analysis: Monitor GPM across different facility zones.
- Leakage Alerts: Detect continuous abnormal flow during off-hours.
- Sustainability Metrics: Track your exact water footprint to achieve corporate sustainability goals.'
            ],
            [
                'name' => 'Temp. & Humidity Monitoring', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-thermometer-half',
                'short_description' => 'Maintain critical environmental parameters in warehouses, server rooms, and production lines.',
                'long_description' => 'Strict environmental control for sensitive materials.
- Precision Logging: Maintain temperatures within strict tolerances for pharma or food storage.
- Out-of-Bound Alerts: Get notified immediately via SMS/Email if parameters deviate.
- Historical Trends: Analyze seasonal variations to optimize HVAC performance.'
            ],
            [
                'name' => 'Machine Monitoring', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-graph-up-arrow',
                'short_description' => 'Get real-time visibility into machine status, cycle times, and operational health.',
                'long_description' => 'Unlock the full potential of your shop floor.
- Spindle Speed & Temperature: Monitor critical CNC machine parameters.
- Cycle Time Analysis: Identify the longest cycle times to improve throughput.
- Remote Diagnostics: Allow engineers to troubleshoot machines without being on-site.'
            ],
            [
                'name' => 'Waste Management', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-trash',
                'short_description' => 'Optimize waste collection routes and monitor bin fill-levels across large facilities.',
                'long_description' => 'Smart waste management for smarter cities and factories.
- Fill-Level Sensors: Only dispatch collection trucks when bins are actually full.
- Route Optimization: Save fuel and time by mapping the most efficient collection paths.
- Spill & Fire Alerts: Detect hazardous conditions in waste containers early.'
            ],
            [
                'name' => 'Smart Irrigation', 
                'category' => 'Industry 4.0', 
                'icon' => 'bi-tree',
                'short_description' => 'Automate watering schedules based on real-time soil moisture and weather forecasting.',
                'long_description' => 'Precision agriculture and landscaping control.
- Soil Moisture Sensors: Only water when the soil actually needs it, preventing over-watering.
- Weather API Integration: Automatically skip watering if rain is forecasted.
- Valve Control: Remotely open and close irrigation valves from your dashboard.'
            ],
            
            [
                'name' => 'Smart Office', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-building-check',
                'short_description' => 'Create a comfortable, energy-efficient workspace with automated lighting, HVAC, and occupancy tracking.',
                'long_description' => 'The modern office is intelligent and adaptable.
- Occupancy Sensors: Turn off lights and AC in empty meeting rooms automatically.
- Desk Utilization: Understand how your office space is actually being used for better real-estate planning.
- Comfort Control: Allow employees to vote on ambient temperature settings.'
            ],
            [
                'name' => 'Air Quality (AQI) Monitoring', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-wind',
                'short_description' => 'Track PM2.5, CO2, and VOCs to ensure a healthy breathing environment for your workforce.',
                'long_description' => 'Healthier air means a healthier, more productive team.
- CO2 Monitoring: Automatically increase fresh air ventilation when CO2 levels rise in crowded rooms.
- Dust & Particulate Tracking: Essential for manufacturing floors generating fine dust.
- Public Dashboards: Display current AQI on lobby screens to assure employees of air safety.'
            ],
            [
                'name' => 'Silos Monitoring', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-funnel',
                'short_description' => 'Accurately measure inventory levels of grain, cement, or liquids inside large silos.',
                'long_description' => 'Never run out of raw materials unexpectedly.
- Radar & Ultrasonic Sensors: Highly accurate volume measurements regardless of dust or uneven surfaces.
- Supplier Integration: Automatically trigger re-order requests when silos drop below 20%.
- Spoilage Prevention: Monitor internal temperature and humidity to prevent grain spoilage.'
            ],
            [
                'name' => 'Environmental Monitoring', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-cloud-haze',
                'short_description' => 'Deploy rugged outdoor sensors to track weather patterns, soil health, and atmospheric conditions.',
                'long_description' => 'Understand the world outside your facility.
- Wind Speed & Direction: Critical for crane operations and outdoor safety.
- Rain & Flood Gauges: Get early warnings of rising water levels near your facilities.
- UV Index & Solar Radiation: Useful for agriculture and solar farm efficiency.'
            ],
            [
                'name' => 'Solar Remote Monitoring', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-sun',
                'short_description' => 'Maximize solar energy yield by monitoring string performance, inverter efficiency, and panel soiling.',
                'long_description' => 'Ensure your renewable energy investments pay off.
- Inverter Analytics: Track AC/DC conversion efficiency in real-time.
- String-Level Monitoring: Quickly identify which specific group of panels is underperforming.
- Soiling Ratios: Calculate exactly when the financial loss from dirty panels justifies the cost of cleaning.'
            ],
            [
                'name' => 'Machine OEE Monitoring', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-gear',
                'short_description' => 'Track Overall Equipment Effectiveness (Availability, Performance, Quality) automatically.',
                'long_description' => 'The gold standard for manufacturing productivity.
- Real-Time OEE Calculation: Replace manual clipboards with automated, instant OEE scores.
- Downtime Categorization: Require operators to input reasons for stops, identifying the biggest loss categories.
- Shift Comparisons: See which shifts and teams perform best and share best practices.'
            ],
            [
                'name' => 'Fleet Tracking', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-geo-alt',
                'short_description' => 'Monitor vehicle locations, driver behavior, and engine diagnostics in real-time using GPS telemetry.',
                'long_description' => 'Complete visibility over your mobile assets.
- Live GPS Mapping: See exactly where your delivery trucks are at any moment.
- Driver Behavior: Get alerts for harsh braking, rapid acceleration, and speeding.
- Geofencing: Automatically log when vehicles enter or leave designated job sites or warehouses.'
            ],
            [
                'name' => 'Fuel Tank Level Monitoring', 
                'category' => 'Industrial IoT Solutions', 
                'icon' => 'bi-ev-station',
                'short_description' => 'Prevent theft and ensure steady supply with remote volumetric monitoring of diesel and fuel tanks.',
                'long_description' => 'Secure your most liquid assets.
- Theft Detection: Instant alerts if fuel levels drop rapidly outside of authorized dispensing hours.
- Usage Analytics: Track exact fuel consumption per generator or machine.
- Delivery Verification: Ensure you received the exact amount of fuel you paid the supplier for.'
            ],
        ];

        foreach ($modules as $mod) {
            $module = IndustrialModule::create([
                'name' => $mod['name'],
                'slug' => Str::slug($mod['name']),
                'category' => $mod['category'],
                'icon' => $mod['icon'],
                'short_description' => $mod['short_description'],
                'long_description' => $mod['long_description']
            ]);

            // Create 3 devices per module
            for ($i = 1; $i <= 3; $i++) {
                $device = IndustrialDevice::create([
                    'industrial_module_id' => $module->id,
                    'name' => $mod['name'] . ' Sensor ' . $i,
                    'location' => 'Zone ' . chr(64 + $i),
                    'status' => $i === 3 ? 'offline' : 'online',
                ]);

                // Create 20 telemetry points per device for the last 24 hours
                for ($j = 20; $j >= 0; $j--) {
                    IndustrialTelemetry::create([
                        'industrial_device_id' => $device->id,
                        'metric_name' => 'Primary Metric',
                        'value' => rand(10, 100) + (rand(0, 99) / 100),
                        'unit' => 'Unit',
                        'recorded_at' => Carbon::now()->subHours($j),
                    ]);
                }
            }
        }
    }
}