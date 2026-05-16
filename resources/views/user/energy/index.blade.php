@extends('layouts.app')
@section('title', 'Energy Monitoring')
@section('page-title', 'Energy Monitoring')

@push('styles')
<style>
    .energy-card {
        background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
        backdrop-filter: var(--glass-blur);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        padding: 2rem;
        position: relative;
    }
    .cost-large {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(90deg, #34d399, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .device-usage-item {
        padding: 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .device-usage-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="energy-card h-100">
            <h5 style="font-family:'Space Grotesk', sans-serif; margin-bottom: 1.5rem;">Daily Energy Usage (Last 7 Days)</h5>
            <div style="height: 300px; width: 100%;">
                <canvas id="weeklyEnergyChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="energy-card h-100 d-flex flex-column justify-content-center align-items-center text-center">
            <h5 style="font-family:'Space Grotesk', sans-serif; color:var(--text-muted);">Estimated Monthly Cost</h5>
            <div class="cost-large mt-3 mb-2">${{ number_format($monthlyCostEst, 2) }}</div>
            <div style="color:var(--text-muted); font-size: 0.9rem;">
                <i class="bi bi-arrow-down-right text-success me-1"></i> 12% lower than last month
            </div>
        </div>
    </div>
</div>

<div class="energy-card">
    <h5 style="font-family:'Space Grotesk', sans-serif; margin-bottom: 1.5rem;">Devices Ranked by Energy Usage</h5>
    
    <div class="table-responsive">
        <table class="table table-borderless text-white mb-0 align-middle">
            <thead style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <tr>
                    <th class="text-muted fw-normal pb-3">Device Name</th>
                    <th class="text-muted fw-normal pb-3">Location</th>
                    <th class="text-muted fw-normal pb-3">Status</th>
                    <th class="text-muted fw-normal pb-3 text-end">Consumption (kWh)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deviceUsage as $usage)
                <tr>
                    <td class="py-3 fw-bold">{{ $usage->name }}</td>
                    <td class="py-3" style="color:var(--text-muted);"><i class="bi bi-geo-alt me-1"></i>{{ $usage->location }}</td>
                    <td class="py-3">
                        @if($usage->is_on)
                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3">Online</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-25 text-secondary rounded-pill px-3">Offline</span>
                        @endif
                    </td>
                    <td class="py-3 text-end fw-bold" style="font-family: monospace; font-size:1.1rem;">
                        {{ number_format($usage->usage_kwh, 1) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('weeklyEnergyChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(52, 211, 153, 0.8)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Energy (kWh)',
                data: @json($energyChartData),
                backgroundColor: gradient,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
