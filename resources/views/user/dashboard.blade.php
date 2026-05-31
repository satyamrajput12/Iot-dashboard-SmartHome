@extends('layouts.app')
@section('title', 'My Dashboard')
@section('page-title', 'My Smart Home')

@push('styles')
<style>
    /* Widget Card Base */
    .widget-card {
        background: var(--glass-bg);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        padding: 1.8rem;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
    }
    .widget-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 20px rgba(139, 92, 246, 0.15);
        border-color: rgba(139, 92, 246, 0.4);
    }
    
    /* Skeleton Shimmer Loading */
    .skeleton {
        background: rgba(0, 0, 0, 0.05);
        background-image: linear-gradient(90deg, rgba(0,0,0,0) 0, rgba(0,0,0,0.05) 50%, rgba(0,0,0,0) 100%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 8px;
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    .widget-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--text-main);
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        letter-spacing: 0.5px;
    }

    /* Range Slider (iOS Style Thick Slider) */
    .custom-range {
        -webkit-appearance: none;
        width: 100%;
        height: 44px;
        border-radius: 22px;
        background: rgba(128,128,128,0.2);
        outline: none;
        margin: 10px 0;
        border: 1px solid var(--glass-border);
        overflow: hidden; /* For fill effect */
    }
    .custom-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--primary);
        cursor: pointer;
        box-shadow: -400px 0 0 380px var(--primary), 0 2px 10px rgba(0,0,0,0.2);
    }

    /* Thermostat Dial Simulation (Warm Cinematic) */
    .thermostat-dial {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: conic-gradient(from -90deg, #ffb5a7, rgba(255,255,255,0.05) 50%, rgba(255,255,255,0.02) 50%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin: 0 auto;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .thermostat-inner {
        width: 176px;
        height: 176px;
        border-radius: 50%;
        background: rgba(30, 25, 25, 0.6);
        backdrop-filter: blur(20px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 2;
        box-shadow: inset 0 4px 15px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* Camera Feed */
    .camera-feed {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.2);
        margin-bottom: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .camera-feed img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .camera-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 1rem 1.5rem;
        background: linear-gradient(0deg, rgba(0,0,0,0.8), transparent);
        color: #fff;
        font-size: 0.95rem;
        display: flex;
        justify-content: space-between;
    }
    .rec-indicator {
        width: 10px; height: 10px; border-radius: 50%; background: #ff4757;
        display: inline-block; margin-right: 8px;
        animation: blink 1.5s infinite;
        box-shadow: 0 0 10px #ff4757;
    }
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0; } 100% { opacity: 1; } }

    /* Control Buttons (Pill shaped translucent) */
    .control-btn {
        background: rgba(128,128,128,0.1);
        border: 1px solid var(--glass-border);
        color: var(--text-main);
        padding: 0.8rem 1.5rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
    }
    .control-btn.active {
        background: rgba(128,128,128,0.2);
        color: var(--text-main);
        box-shadow: 0 5px 15px rgba(128,128,128,0.2);
        transform: translateY(-2px);
    }
    .control-btn:hover:not(.active) {
        background: rgba(255,255,255,0.2);
    }
    
    /* Action Pill (Bottom floating) */
    .action-pill-container {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
    }
    .action-pill {
        background: linear-gradient(135deg, #1e1e1e, #111111);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 40px;
        padding: 0.6rem 1.5rem 0.6rem 0.6rem;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: transform 0.3s;
    }
    .action-pill:hover { transform: translateX(-50%) scale(1.02); }
    .action-pill-icon {
        width: 45px; height: 45px;
        border-radius: 50%;
        background: #ffffff;
        color: #000000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
        backdrop-filter: blur(10px);
    }
    .control-btn.active {
        background: rgba(128,128,128,0.2);
        border-color: var(--glass-border);
        color: var(--text-main);
        box-shadow: 0 5px 15px rgba(128,128,128,0.1);
        transform: translateY(-2px);
    }
    .control-btn:hover {
        background: rgba(128,128,128,0.15);
    }
</style>
@endpush

@section('content')

<div id="skeleton-loader">
    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="widget-card"><div class="skeleton" style="width:100%;height:60px;margin-bottom:10px;"></div><div class="skeleton" style="width:60%;height:30px;"></div></div></div>
        <div class="col-md-4"><div class="widget-card"><div class="skeleton" style="width:100%;height:60px;margin-bottom:10px;"></div><div class="skeleton" style="width:60%;height:30px;"></div></div></div>
        <div class="col-md-4"><div class="widget-card"><div class="skeleton" style="width:100%;height:60px;margin-bottom:10px;"></div><div class="skeleton" style="width:60%;height:30px;"></div></div></div>
    </div>
</div>
<div id="dashboard-content" style="display:none; animation: fadeIn 0.8s ease forwards;">
<style>@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }</style>
<!-- Quick Stats Row -->
<div class="row g-4 mb-4">
    <!-- Energy Usage Card -->
    <div class="col-md-4">
        <div class="widget-card d-flex align-items-center">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(59, 130, 246, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #60a5fa; margin-right: 1rem;">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Today's Energy</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">{{ number_format($dailyTotalEnergy, 1) }} <span style="font-size:0.9rem; color:var(--text-muted); font-weight: 600;">kWh</span></div>
            </div>
        </div>
    </div>
    
    <!-- Active Devices Card -->
    <div class="col-md-4">
        <div class="widget-card d-flex align-items-center">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #34d399; margin-right: 1rem;">
                <i class="bi bi-hdd-network-fill"></i>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Active Devices</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">{{ $activeDevicesCount }} <span style="font-size:0.9rem; color:var(--text-muted); font-weight: 600;">/ {{ $allDevices->count() }}</span></div>
            </div>
        </div>
    </div>

    <!-- Estimated Cost Card -->
    <div class="col-md-4">
        <div class="widget-card d-flex align-items-center">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(245, 158, 11, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fbbf24; margin-right: 1rem;">
                <i class="bi bi-currency-rupee"></i>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Est. Cost Today</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-main);">₹{{ number_format($estimatedCost, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Living Room Comfort (Thermostat) -->
    <div class="col-lg-4">
        <div class="widget-card">
            <div class="widget-title">
                Climate Control
                <button class="btn btn-sm" style="color:var(--text-muted)"><i class="bi bi-three-dots"></i></button>
            </div>
            
            @if($thermostats->count() > 0)
                @php $thermostat = $thermostats->first(); @endphp
                <div class="text-center mb-4">
                    <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">{{ $thermostat->name }}</div>
                    
                    <div class="thermostat-dial">
                        <div class="thermostat-inner">
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Target</div>
                            <div style="font-size: 2.5rem; font-weight: 700; font-family: 'Poppins', sans-serif; line-height: 1;">
                                <span id="target-temp-{{ $thermostat->id }}">{{ $thermostat->target_temperature ?? 22 }}</span>°
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">Current: {{ $thermostat->temperature ?? 21 }}°</div>
                        </div>
                    </div>
                </div>

                <!-- Temperature Controls -->
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <button class="btn btn-light rounded-circle" style="width:40px;height:40px;" onclick="adjustTemp({{ $thermostat->id }}, -0.5)">
                        <i class="bi bi-dash"></i>
                    </button>
                    <button class="btn btn-light rounded-circle" style="width:40px;height:40px;" onclick="adjustTemp({{ $thermostat->id }}, 0.5)">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>

                <!-- Mode Controls -->
                <div class="d-flex justify-content-between gap-2">
                    <button class="flex-fill control-btn {{ $thermostat->mode == 'Cool' ? 'active' : '' }}" onclick="setMode({{ $thermostat->id }}, 'Cool', this)">
                        <i class="bi bi-snow2 mb-1 d-block"></i> Cool
                    </button>
                    <button class="flex-fill control-btn {{ $thermostat->mode == 'Heat' ? 'active' : '' }}" onclick="setMode({{ $thermostat->id }}, 'Heat', this)">
                        <i class="bi bi-fire mb-1 d-block"></i> Heat
                    </button>
                    <button class="flex-fill control-btn {{ $thermostat->mode == 'Auto' ? 'active' : '' }}" onclick="setMode({{ $thermostat->id }}, 'Auto', this)">
                        <i class="bi bi-arrow-repeat mb-1 d-block"></i> Auto
                    </button>
                </div>

                <!-- Extra Controls to fill space -->
                <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-fan" style="color: var(--text-muted);"></i>
                            <span style="color: var(--text-muted); font-size: 0.9rem;">Fan Speed</span>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-light active" style="font-size: 0.75rem; border-color: rgba(255,255,255,0.2);">Low</button>
                            <button type="button" class="btn btn-outline-light" style="font-size: 0.75rem; border-color: rgba(255,255,255,0.2);">Med</button>
                            <button type="button" class="btn btn-outline-light" style="font-size: 0.75rem; border-color: rgba(255,255,255,0.2);">High</button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-power" style="color: var(--text-muted);"></i>
                            <span style="color: var(--text-muted); font-size: 0.9rem;">System Power</span>
                        </div>
                        <label class="toggle-switch mb-0">
                            <input type="checkbox" {{ $thermostat->isOn() ? 'checked' : '' }} onchange="toggleDevice({{ $thermostat->id }}, this)">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            @else
                <div class="text-center py-4 text-muted">No climate control device detected.</div>
            @endif
        </div>
    </div>

    <!-- Security Cameras -->
    <div class="col-lg-8">
        <div class="widget-card">
            <div class="widget-title">
                Smart Cameras & Appliances
                <a href="#" style="font-size: 0.8rem; color: var(--primary); text-decoration: none;">View All</a>
            </div>
            
            <div class="row g-3">
                @if($cameras->count() > 0)
                    @foreach($cameras->take(6) as $camera)
                        <div class="col-md-6">
                            <div class="camera-feed" id="camera-feed-{{ $camera->id }}" style="transition: all 0.3s; {{ $camera->isOn() ? '' : 'opacity: 0.5; filter: grayscale(100%);' }}">
                                <img src="{{ $camera->stream_url ?? asset('images/devices/' . $camera->type . '.png') }}" alt="{{ $camera->name }}" style="object-fit: contain; background: white; padding: 10px;">
                                <div class="camera-overlay">
                                    <span id="cam-status-{{ $camera->id }}">
                                        @if($camera->isOn())
                                            <span class="rec-indicator"></span> LIVE
                                        @else
                                            <i class="bi bi-camera-video-off text-muted"></i> OFFLINE
                                        @endif
                                    </span>
                                    <span>{{ $camera->location }}</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center px-1">
                                <div style="font-weight: 500; font-size: 0.9rem;">{{ $camera->name }}</div>
                                <label class="toggle-switch">
                                    <input type="checkbox" {{ $camera->isOn() ? 'checked' : '' }} onchange="toggleDevice({{ $camera->id }}, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-4 text-muted">No cameras connected.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Lighting Control -->
    <div class="col-lg-6">
        <div class="widget-card">
            <div class="widget-title">Lighting Control</div>
            
            <div class="d-flex flex-column gap-3">
                @forelse($lights as $light)
                    <div class="p-3 rounded-4" style="background: rgba(128,128,128,0.05); border: 1px solid var(--glass-border);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div id="light-icon-{{ $light->id }}" style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; {{ $light->isOn() ? 'background: rgba(250, 204, 21, 0.15); color: #facc15;' : 'background: rgba(128,128,128,0.1); color: #888888;' }}">
                                    <i class="bi bi-lightbulb-fill"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 500; font-size: 0.95rem;">{{ $light->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $light->location }}</div>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" {{ $light->isOn() ? 'checked' : '' }} onchange="toggleDevice({{ $light->id }}, this)">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <i class="bi bi-brightness-low text-muted"></i>
                            <input type="range" class="custom-range flex-grow-1" min="0" max="100" value="{{ $light->brightness ?? 100 }}" onchange="updateBrightness({{ $light->id }}, this.value)" id="brightness-{{ $light->id }}">
                            <i class="bi bi-brightness-high text-muted"></i>
                            <span style="font-size: 0.8rem; width: 30px; text-align: right;" id="brightness-val-{{ $light->id }}">{{ $light->brightness ?? 100 }}%</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">No smart lights found.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Energy Consumption Chart -->
    <div class="col-lg-6">
        <div class="widget-card">
            <div class="widget-title">
                Energy Consumption
                <select class="form-select form-select-sm" style="width: auto; background: rgba(0,0,0,0.2); border: none;">
                    <option>Today</option>
                    <option>This Week</option>
                </select>
            </div>
            <div style="height: 250px; width: 100%;">
                <canvas id="energyChart"></canvas>
            </div>
        </div>
    </div>
</div>

</div>



@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Device Toggle
function toggleDevice(deviceId, checkbox) {
    fetch(`/dashboard/devices/${deviceId}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            checkbox.checked = !checkbox.checked;
            showToast(data.message, 'error');
        } else {
            showToast(data.message, 'success');
            
            // Visual update for cameras
            const camFeed = document.getElementById('camera-feed-' + deviceId);
            const camStatus = document.getElementById('cam-status-' + deviceId);
            if (camFeed) {
                if (checkbox.checked) {
                    camFeed.style.opacity = '1';
                    camFeed.style.filter = 'grayscale(0%)';
                    if (camStatus) camStatus.innerHTML = '<span class="rec-indicator"></span> LIVE';
                } else {
                    camFeed.style.opacity = '0.5';
                    camFeed.style.filter = 'grayscale(100%)';
                    if (camStatus) camStatus.innerHTML = '<i class="bi bi-camera-video-off text-muted"></i> OFFLINE';
                }
            }
            
            // Visual update for lights
            const lightIcon = document.getElementById('light-icon-' + deviceId);
            if (lightIcon) {
                if (checkbox.checked) {
                    lightIcon.style.background = 'rgba(250, 204, 21, 0.15)';
                    lightIcon.style.color = '#facc15';
                } else {
                    lightIcon.style.background = 'rgba(255,255,255,0.05)';
                    lightIcon.style.color = '#888888';
                }
            }
        }
    }).catch(() => { checkbox.checked = !checkbox.checked; showToast('Error toggling device.', 'error'); });
}

// Thermostat Controls
function adjustTemp(deviceId, amount) {
    const el = document.getElementById('target-temp-' + deviceId);
    let currentTemp = parseFloat(el.textContent);
    let newTemp = currentTemp + amount;
    
    fetch(`/dashboard/devices/${deviceId}/temperature`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ temperature: newTemp })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            el.textContent = newTemp.toFixed(1);
        } else {
            showToast(data.message, 'error');
        }
    });
}

function setMode(deviceId, mode, btnElement) {
    fetch(`/dashboard/devices/${deviceId}/mode`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ mode: mode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update active state of buttons
            const buttons = btnElement.parentElement.querySelectorAll('.control-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');
        }
    });
}

// Lighting Brightness
function updateBrightness(deviceId, value) {
    document.getElementById('brightness-val-' + deviceId).textContent = value + '%';
    
    fetch(`/dashboard/devices/${deviceId}/brightness`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ brightness: value })
    });
}

// Chart.js Init
document.addEventListener('DOMContentLoaded', function() {
    // Simulate loading for shimmer effect
    setTimeout(() => {
        document.getElementById('skeleton-loader').style.display = 'none';
        document.getElementById('dashboard-content').style.display = 'block';
    }, 800);

    const ctx = document.getElementById('energyChart').getContext('2d');
    
    // Create gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
    
    const energyData = @json($energyChartData);
    const labels = Array.from({length: 24}, (_, i) => i + ':00');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Energy (kWh)',
                data: energyData,
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#64748b', maxTicksLimit: 8 }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                    ticks: { color: '#64748b' },
                    beginAtZero: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.style.cssText = `position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
        background:${type === 'success' ? '#16a34a' : '#dc2626'};color:#fff;
        padding:.75rem 1.25rem;border-radius:10px;font-size:.88rem;font-weight:500;
        box-shadow:0 4px 20px rgba(0,0,0,.2);transition:opacity .3s`;
    toast.textContent = (type === 'success' ? '✓ ' : '✗ ') + message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
@endpush
