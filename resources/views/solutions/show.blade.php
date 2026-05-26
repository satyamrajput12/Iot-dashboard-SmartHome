@extends('layouts.app')

@section('title', $module->name)
@section('page-title', $module->name)

@push('styles')
<style>
    /* New Hero Section Styles */
    .hero-section {
        background: linear-gradient(135deg, #09090b, #18181b), url('data:image/svg+xml;utf8,<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)" /></svg>');
        border-radius: 24px;
        padding: 4rem 3rem;
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .hero-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        max-width: 800px;
    }
    .hero-subtitle {
        font-size: 1.15rem;
        color: #a1a1aa;
        max-width: 700px;
        line-height: 1.6;
        margin-bottom: 2.5rem;
    }
    .btn-demo {
        background-color: #10b981;
        color: #fff;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        border: none;
    }
    .btn-demo:hover {
        background-color: #059669;
        color: #fff;
        transform: translateY(-2px);
    }

    /* Info Section Styles */
    .info-section {
        background: #ffffff;
        border-radius: 24px;
        padding: 3rem;
        margin-top: -20px; /* Slight overlap */
        position: relative;
        z-index: 10;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .info-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #18181b;
        margin-bottom: 1.5rem;
    }
    .info-content {
        font-size: 1.1rem;
        color: #4b5563;
        line-height: 1.8;
        white-space: pre-line;
    }

    /* Existing Dashboard Styles */
    .dashboard-header {
        margin: 3rem 0 1.5rem 0;
        padding-top: 2rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .module-card {
        background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
        backdrop-filter: blur(16px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 1.5rem;
        height: 100%;
    }
    .kpi-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #fff;
        font-family: 'Space Grotesk', sans-serif;
    }
    .kpi-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-online { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-offline { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
</style>
@endpush

@section('content')

<!-- 1. Dark Hero Section -->
<div class="row g-0 px-4 mt-2">
    <div class="col-12">
        <div class="hero-section">
            <h1 class="hero-title">{{ $module->name }}<br>Monitoring</h1>
            <p class="hero-subtitle">{{ $module->short_description ?? 'Enhance operational efficiency and real-time insights with our advanced dashboard, optimizing production processes for increased productivity.' }}</p>
            <a href="#" class="btn-demo"><i class="bi bi-window-dock"></i> Request a demo</a>
            
            <!-- Abstract background graphic -->
            <div style="position:absolute; right: -50px; top: 50%; transform: translateY(-50%); opacity: 0.3; pointer-events:none;">
                <svg width="400" height="200" viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,100 C100,200 200,0 400,100" fill="none" stroke="#e11d48" stroke-width="2" stroke-dasharray="5,5"/>
                    <path d="M0,120 C150,250 250,-50 400,120" fill="none" stroke="#2563eb" stroke-width="1.5" stroke-dasharray="4,6"/>
                    <path d="M0,80 C120,180 220,20 400,80" fill="none" stroke="#10b981" stroke-width="1" stroke-dasharray="3,7"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- 2. Light Info Section -->
<div class="row g-0 px-4">
    <div class="col-12">
        <div class="info-section">
            <h2 class="info-title">{{ $module->name }}</h2>
            <div class="info-content">{{ $module->long_description ?? 'IoTdashboard provides a variety of IoT-based monitoring system solutions. By leveraging real-time telemetry and advanced analytics, we empower businesses to make data-driven decisions that reduce downtime and improve overall operational efficiency.' }}</div>
        </div>
    </div>
</div>

<!-- 3. Existing Dashboard Content -->
<div class="px-4">
    <div class="dashboard-header">
        <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(59, 130, 246, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #60a5fa;">
            <i class="bi {{ $module->icon }}"></i>
        </div>
        <h3 class="mb-0" style="color: #fff; font-weight: 700;">Live Dashboard</h3>
        <button class="btn btn-outline-light ms-auto btn-sm"><i class="bi bi-download me-2"></i>Export Report</button>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="module-card">
                <div class="kpi-label mb-2"><i class="bi bi-cpu me-2"></i>Total Sensors</div>
                <div class="kpi-value">{{ $totalDevices }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="module-card">
                <div class="kpi-label mb-2"><i class="bi bi-wifi me-2"></i>Online Status</div>
                <div class="kpi-value text-success" style="color: #34d399 !important;">{{ $onlineDevices }} / {{ $totalDevices }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="module-card">
                <div class="kpi-label mb-2"><i class="bi bi-activity me-2"></i>System Health</div>
                <div class="kpi-value text-info" style="color: #60a5fa !important;">99.9%</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="module-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0" style="color: #fff; font-weight: 600;">Live Telemetry Data</h5>
                    <span class="badge bg-success bg-opacity-25 text-success border border-success"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Live</span>
                </div>
                <div style="height: 350px; width: 100%;">
                    <canvas id="telemetryChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="module-card">
                <h5 class="mb-4" style="color: #fff; font-weight: 600;">Active Devices</h5>
                <div class="d-flex flex-column gap-3">
                    @forelse($module->devices as $device)
                    <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-weight: 600; color: #fff; font-size: 0.95rem;">{{ $device->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="bi bi-geo-alt me-1"></i>{{ $device->location }}</div>
                            </div>
                            <span class="status-badge {{ $device->status == 'online' ? 'badge-online' : 'badge-offline' }}">{{ $device->status }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">No devices connected.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('telemetryChart').getContext('2d');
    
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Loading...',
                data: [],
                borderColor: '#10b981',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointRadius: 4,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } }
            },
            animation: { duration: 0 }
        }
    });

    function fetchTelemetry() {
        fetch("{{ route('solutions.telemetry', $module->slug) }}")
            .then(res => res.json())
            .then(data => {
                chart.data.labels = data.labels;
                chart.data.datasets[0].data = data.data;
                chart.data.datasets[0].label = data.metric + (data.unit ? ' (' + data.unit + ')' : '');
                chart.update();
            });
    }

    fetchTelemetry();
    setInterval(fetchTelemetry, 5000);
});
</script>
@endpush
