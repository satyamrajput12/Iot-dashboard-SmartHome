@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .admin-stat-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .admin-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        border-color: rgba(255,255,255,0.15);
    }
    .admin-stat-card::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: radial-gradient(circle, var(--accent-color) 0%, transparent 70%);
        opacity: 0.25;
        border-radius: 50%;
        transition: opacity 0.3s;
    }
    .admin-stat-card:hover::before { opacity: 0.4; }
    
    .leaflet-layer,
    .leaflet-control-zoom-in,
    .leaflet-control-zoom-out,
    .leaflet-control-attribution {
        filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
    }
    
    .count-up {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .activity-feed {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .sys-health-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem 0; border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .sys-health-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

<!-- Quick Actions -->
<div class="d-flex justify-content-end gap-3 mb-4">
    <button class="btn btn-outline-light rounded-pill"><i class="bi bi-file-earmark-arrow-down me-2"></i>Export Logs CSV</button>
    <button class="btn btn-primary rounded-pill"><i class="bi bi-check2-all me-2"></i>Approve All Pending</button>
</div>

<!-- Hero Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #3b82f6;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Total Users</div>
            <div class="count-up" data-target="{{ $stats['total_users'] }}">0</div>
            <div style="font-size:0.85rem; color:#3b82f6;"><i class="bi bi-arrow-up-right"></i> 12% this week</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #8b5cf6;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Total Devices</div>
            <div class="count-up" data-target="{{ $stats['total_devices'] }}">0</div>
            <div style="font-size:0.85rem; color:#8b5cf6;"><i class="bi bi-arrow-up-right"></i> 5% this week</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #f59e0b;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Pending Approvals</div>
            <div class="count-up text-warning" data-target="{{ $stats['pending'] }}">0</div>
            <div style="font-size:0.85rem; color:#f59e0b;">Requires attention</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #10b981;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Devices Online</div>
            <div class="count-up text-success" data-target="{{ $stats['online_devices'] }}">0</div>
            <div style="font-size:0.85rem; color:#10b981;">Live connected</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Map Widget -->
    <div class="col-lg-8">
        <div class="admin-stat-card h-100 p-0">
            <div class="p-4 border-bottom" style="border-color: rgba(0,0,0,0.05) !important;">
                <h6 class="mb-0 fw-bold" style="font-family:'Poppins', sans-serif; color: var(--text-main);">Device Locations (India)</h6>
            </div>
            <div class="p-4 relative">
                <div id="india-map" style="height: 320px; border-radius: 16px; z-index: 1;"></div>
            </div>
        </div>
    </div>
    
    <!-- System Health -->
    <div class="col-lg-4">
        <div class="admin-stat-card h-100">
            <h6 class="mb-4 fw-bold" style="font-family:'Poppins', sans-serif; color: var(--text-main);">System Health</h6>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-hdd-network me-2"></i>Database</span>
                <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3">Connected</span>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-clock me-2"></i>Server Uptime</span>
                <span class="fw-bold" style="color: var(--text-main);">99.9% (45 days)</span>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-cpu me-2"></i>CPU Usage</span>
                <span class="fw-bold text-warning">42%</span>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-memory me-2"></i>RAM Usage</span>
                <div class="w-50">
                    <div class="progress" style="height: 6px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar bg-primary" style="width: 65%;"></div>
                    </div>
                </div>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-code-slash me-2"></i>PHP Version</span>
                <span class="fw-bold" style="color: var(--text-main);">8.2.0</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Real-time Activity Feed -->
    <div class="col-12">
        <div class="admin-stat-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="mb-0 fw-bold" style="font-family:'Poppins', sans-serif; color: var(--text-main);"><span class="spinner-grow spinner-grow-sm text-danger me-2" role="status"></span>Real-time Activity Feed</h6>
                <a href="{{ route('admin.logs') }}" style="font-size:.85rem; color:var(--primary);">View All Logs</a>
            </div>
            
            <div class="activity-feed" id="activityFeed">
                @foreach($recentLogs as $log)
                <div class="p-3 border-bottom d-flex gap-3 align-items-center" style="border-color: rgba(0,0,0,0.05) !important;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center;">
                        @if($log->log_type == 'info') <i class="bi bi-info-circle text-info"></i>
                        @elseif($log->log_type == 'warning') <i class="bi bi-exclamation-triangle text-warning"></i>
                        @elseif($log->log_type == 'error') <i class="bi bi-x-circle text-danger"></i>
                        @else <i class="bi bi-activity text-success"></i> @endif
                    </div>
                    <div style="flex:1">
                        <div style="font-size:0.9rem;">
                            <span class="fw-bold" style="color: var(--text-main);">{{ $log->device->name ?? 'System' }}</span>
                            <span style="color:var(--text-muted);"> — {{ $log->action }}</span>
                        </div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">{{ $log->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Initialize Interactive Map of India
document.addEventListener('DOMContentLoaded', () => {
    // 20.5937, 78.9629 is the center of India
    const map = L.map('india-map', {
        zoomControl: false,
        attributionControl: false
    }).setView([22.5937, 79.9629], 4.4);

    // Use a clean, light-themed map tile (CartoDB Positron) and invert it via CSS
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19
    }).addTo(map);

    // Custom pulse marker CSS
    const css = `
    .pulse-marker {
        width: 12px;
        height: 12px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    `;
    const style = document.createElement('style');
    style.innerHTML = css;
    document.head.appendChild(style);

    const pulseIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div class="pulse-marker"></div>',
        iconSize: [12, 12],
        iconAnchor: [6, 6]
    });

    // Dummy coordinates for major Indian cities with active devices
    const locations = [
        { name: 'Delhi', coords: [28.7041, 77.1025] },
        { name: 'Mumbai', coords: [19.0760, 72.8777] },
        { name: 'Bangalore', coords: [12.9716, 77.5946] },
        { name: 'Pune', coords: [18.5204, 73.8567] },
        { name: 'Hyderabad', coords: [17.3850, 78.4867] },
        { name: 'Chennai', coords: [13.0827, 80.2707] }
    ];

    locations.forEach(loc => {
        L.marker(loc.coords, { icon: pulseIcon })
         .addTo(map)
         .bindPopup(`<b>${loc.name}</b><br>Active Devices`);
    });
});

// Animated Count-Up
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.count-up');
    const speed = 200;

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 15);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    });
    
    // Auto-scroll activity feed slowly to simulate live incoming data
    const feed = document.getElementById('activityFeed');
    setInterval(() => {
        if(feed.scrollTop < feed.scrollHeight - feed.clientHeight) {
            feed.scrollTop += 1;
        }
    }, 100);
});
</script>
@endpush
