<?php $__env->startSection('title', 'My Dashboard'); ?>
<?php $__env->startSection('page-title', 'My Smart Home'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Widget Card Base */
    .widget-card {
        background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
        backdrop-filter: var(--glass-blur);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .widget-card {
        transform-style: preserve-3d;
        perspective: 1000px;
    }
    .widget-card:hover {
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 20px rgba(139, 92, 246, 0.2);
        transform: translateY(-8px) rotateX(2deg) rotateY(-2deg);
    }
    
    /* Skeleton Shimmer Loading */
    .skeleton {
        background: rgba(255, 255, 255, 0.05);
        background-image: linear-gradient(90deg, rgba(255,255,255,0) 0, rgba(255,255,255,0.05) 50%, rgba(255,255,255,0) 100%);
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
        font-weight: 600;
        font-size: 1.1rem;
        color: #fff;
        margin-bottom: 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Range Slider */
    .custom-range {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        border-radius: 5px;
        background: rgba(255,255,255,0.1);
        outline: none;
        margin: 10px 0;
    }
    .custom-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #fff;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(255,255,255,0.5);
    }

    /* Thermostat Dial Simulation */
    .thermostat-dial {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: conic-gradient(from -90deg, var(--primary), var(--secondary) 50%, rgba(255,255,255,0.05) 50%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin: 0 auto;
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.2);
    }
    .thermostat-inner {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: #0f172a;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    /* Camera Feed */
    .camera-feed {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 1rem;
    }
    .camera-feed img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .camera-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 0.5rem 1rem;
        background: linear-gradient(0deg, rgba(0,0,0,0.8), transparent);
        color: #fff;
        font-size: 0.85rem;
        display: flex;
        justify-content: space-between;
    }
    .rec-indicator {
        width: 8px; height: 8px; border-radius: 50%; background: #ef4444;
        display: inline-block; margin-right: 5px;
        animation: blink 1.5s infinite;
    }
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0; } 100% { opacity: 1; } }

    /* Control Buttons */
    .control-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        transition: all 0.2s;
    }
    .control-btn.active {
        background: var(--primary);
        border-color: var(--primary);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

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
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Today's Energy</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #fff;"><?php echo e(number_format($dailyTotalEnergy, 1)); ?> <span style="font-size:0.9rem; color:var(--text-muted);">kWh</span></div>
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
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Active Devices</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #fff;"><?php echo e($activeDevicesCount); ?> <span style="font-size:0.9rem; color:var(--text-muted);">/ <?php echo e($allDevices->count()); ?></span></div>
            </div>
        </div>
    </div>

    <!-- Estimated Cost Card -->
    <div class="col-md-4">
        <div class="widget-card d-flex align-items-center">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(245, 158, 11, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fbbf24; margin-right: 1rem;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div>
                <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Est. Cost Today</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #fff;">$<?php echo e(number_format($estimatedCost, 2)); ?></div>
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
            
            <?php if($thermostats->count() > 0): ?>
                <?php $thermostat = $thermostats->first(); ?>
                <div class="text-center mb-4">
                    <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;"><?php echo e($thermostat->name); ?></div>
                    
                    <div class="thermostat-dial">
                        <div class="thermostat-inner">
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Target</div>
                            <div style="font-size: 2.5rem; font-weight: 700; font-family: 'Poppins', sans-serif; line-height: 1;">
                                <span id="target-temp-<?php echo e($thermostat->id); ?>"><?php echo e($thermostat->target_temperature ?? 22); ?></span>°
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">Current: <?php echo e($thermostat->temperature ?? 21); ?>°</div>
                        </div>
                    </div>
                </div>

                <!-- Temperature Controls -->
                <div class="d-flex justify-content-center gap-3 mb-4">
                    <button class="btn btn-light rounded-circle" style="width:40px;height:40px;" onclick="adjustTemp(<?php echo e($thermostat->id); ?>, -0.5)">
                        <i class="bi bi-dash"></i>
                    </button>
                    <button class="btn btn-light rounded-circle" style="width:40px;height:40px;" onclick="adjustTemp(<?php echo e($thermostat->id); ?>, 0.5)">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>

                <!-- Mode Controls -->
                <div class="d-flex justify-content-between gap-2">
                    <button class="flex-fill control-btn <?php echo e($thermostat->mode == 'Cool' ? 'active' : ''); ?>" onclick="setMode(<?php echo e($thermostat->id); ?>, 'Cool', this)">
                        <i class="bi bi-snow2 mb-1 d-block"></i> Cool
                    </button>
                    <button class="flex-fill control-btn <?php echo e($thermostat->mode == 'Heat' ? 'active' : ''); ?>" onclick="setMode(<?php echo e($thermostat->id); ?>, 'Heat', this)">
                        <i class="bi bi-fire mb-1 d-block"></i> Heat
                    </button>
                    <button class="flex-fill control-btn <?php echo e($thermostat->mode == 'Auto' ? 'active' : ''); ?>" onclick="setMode(<?php echo e($thermostat->id); ?>, 'Auto', this)">
                        <i class="bi bi-arrow-repeat mb-1 d-block"></i> Auto
                    </button>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted">No thermostat detected.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Security Cameras -->
    <div class="col-lg-8">
        <div class="widget-card">
            <div class="widget-title">
                Security Cameras
                <a href="#" style="font-size: 0.8rem; color: var(--primary); text-decoration: none;">View All</a>
            </div>
            
            <div class="row g-3">
                <?php if($cameras->count() > 0): ?>
                    <?php $__currentLoopData = $cameras->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $camera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="camera-feed">
                                <img src="<?php echo e($camera->stream_url ?? 'https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=60'); ?>" alt="Camera Feed">
                                <div class="camera-overlay">
                                    <span><span class="rec-indicator"></span> LIVE</span>
                                    <span><?php echo e($camera->location); ?></span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center px-1">
                                <div style="font-weight: 500; font-size: 0.9rem;"><?php echo e($camera->name); ?></div>
                                <label class="toggle-switch">
                                    <input type="checkbox" <?php echo e($camera->isOn() ? 'checked' : ''); ?> onchange="toggleDevice(<?php echo e($camera->id); ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="col-12 text-center py-4 text-muted">No cameras connected.</div>
                <?php endif; ?>
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
                <?php $__empty_1 = true; $__currentLoopData = $lights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $light): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(250, 204, 21, 0.15); display: flex; align-items: center; justify-content: center; color: #facc15;">
                                    <i class="bi bi-lightbulb-fill"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 500; font-size: 0.95rem;"><?php echo e($light->name); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo e($light->location); ?></div>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" <?php echo e($light->isOn() ? 'checked' : ''); ?> onchange="toggleDevice(<?php echo e($light->id); ?>, this)">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <i class="bi bi-brightness-low text-muted"></i>
                            <input type="range" class="custom-range flex-grow-1" min="0" max="100" value="<?php echo e($light->brightness ?? 100); ?>" onchange="updateBrightness(<?php echo e($light->id); ?>, this.value)" id="brightness-<?php echo e($light->id); ?>">
                            <i class="bi bi-brightness-high text-muted"></i>
                            <span style="font-size: 0.8rem; width: 30px; text-align: right;" id="brightness-val-<?php echo e($light->id); ?>"><?php echo e($light->brightness ?? 100); ?>%</span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4 text-muted">No smart lights found.</div>
                <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
    
    const energyData = <?php echo json_encode($energyChartData, 15, 512) ?>;
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\satya\OneDrive\Desktop\IOT-DASHBOARD\resources\views/user/dashboard.blade.php ENDPATH**/ ?>