@extends('layouts.app')
@section('title', 'My Devices')
@section('page-title', 'My Devices')

@push('styles')
<style>
    /* 3D Flip Card Styles */
    .device-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .flip-card {
        background-color: transparent;
        width: 100%;
        height: 240px;
        perspective: 1000px;
    }
    
    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
    }
    
    .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
    }
    
    .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: 20px;
        background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
        backdrop-filter: var(--glass-blur);
        border: 1px solid var(--glass-border);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .flip-card-back {
        transform: rotateY(180deg);
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.8));
        align-items: center;
        justify-content: center;
    }

    /* Glowing Ring */
    .device-icon-wrapper {
        position: relative;
        width: 64px;
        height: 64px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        background: rgba(255,255,255,0.05);
        transition: all 0.3s;
    }
    
    .device-is-on .device-icon-wrapper {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4), inset 0 0 10px rgba(16, 185, 129, 0.2);
    }
    
    .device-is-on .device-icon-wrapper::after {
        content: '';
        position: absolute;
        top: -4px; left: -4px; right: -4px; bottom: -4px;
        border-radius: 50%;
        border: 2px solid rgba(16, 185, 129, 0.5);
        animation: pulse-ring 2s infinite;
    }

    /* Waveform Animation */
    .waveform {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 3px;
        height: 20px;
        margin-top: 10px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .device-is-on .waveform { opacity: 1; }
    .wave-bar {
        width: 4px;
        background: #34d399;
        border-radius: 2px;
        animation: wave 1s infinite ease-in-out;
    }
    .wave-bar:nth-child(1) { height: 30%; animation-delay: -0.4s; }
    .wave-bar:nth-child(2) { height: 60%; animation-delay: -0.2s; }
    .wave-bar:nth-child(3) { height: 100%; animation-delay: 0s; }
    .wave-bar:nth-child(4) { height: 60%; animation-delay: -0.2s; }
    .wave-bar:nth-child(5) { height: 30%; animation-delay: -0.4s; }
    @keyframes wave {
        0%, 100% { transform: scaleY(0.5); }
        50% { transform: scaleY(1); }
    }

    /* Status Indicators */
    .status-bars {
        display: flex; gap: 2px;
    }
    .status-bar { width: 4px; height: 12px; background: rgba(255,255,255,0.2); border-radius: 2px; }
    .status-bar.active { background: #3b82f6; }
</style>
@endpush

@section('content')

<!-- Header bar -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0" style="font-size:.9rem">Manage and control all your registered IoT devices</p>
    </div>
    <a href="{{ route('user.devices.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Register New Device
    </a>
</div>

<!-- Filters -->
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Search by name, ID, location...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="thermostat" {{ request('type')=='thermostat'?'selected':'' }}>Thermostat</option>
                <option value="light"      {{ request('type')=='light'?'selected':'' }}>Light</option>
                <option value="alarm"      {{ request('type')=='alarm'?'selected':'' }}>Alarm</option>
                <option value="camera"     {{ request('type')=='camera'?'selected':'' }}>Camera</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                <option value="pending"  {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="location" class="form-select form-select-sm">
                <option value="">All Rooms</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc }}" {{ request('location')==$loc?'selected':'' }}>{{ $loc }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
            <a href="{{ route('user.devices.index') }}" class="btn btn-light btn-sm">Clear</a>
        </div>
    </form>
</div>

<!-- Devices Grid -->
<div class="mb-3">
    <span style="font-size:.85rem;color:#64748b">{{ $devices->total() }} device(s) found</span>
</div>

@if($devices->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:3rem">📱</div>
        <p class="text-muted mt-2">No devices found. <a href="{{ route('user.devices.create') }}">Add one now.</a></p>
    </div>
@else
    <div class="device-grid">
        @foreach($devices as $device)
            <div class="flip-card">
                <div class="flip-card-inner">
                    <!-- Front -->
                    <div class="flip-card-front {{ $device->isOn() ? 'device-is-on' : '' }}" id="device-card-{{ $device->id }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="badge bg-{{ $device->getApprovalBadgeClass() }} bg-opacity-10 text-{{ $device->getApprovalBadgeClass() }}" style="font-size:0.65rem;">
                                {{ ucfirst($device->approval_status) }}
                            </span>
                            <div class="status-bars" title="Signal Strength">
                                <div class="status-bar active"></div>
                                <div class="status-bar active"></div>
                                <div class="status-bar active"></div>
                                <div class="status-bar"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="device-icon-wrapper">
                                @if($device->type == 'thermostat') <i class="bi bi-thermometer-half"></i>
                                @elseif($device->type == 'light') <i class="bi bi-lightbulb"></i>
                                @elseif($device->type == 'camera') <i class="bi bi-camera-video"></i>
                                @else <i class="bi bi-bell"></i> @endif
                            </div>
                            
                            <div class="mt-3 fw-bold" style="font-family:'Poppins', sans-serif;">{{ $device->name }}</div>
                            <div style="font-size:0.8rem; color:var(--text-muted);"><i class="bi bi-geo-alt me-1"></i>{{ $device->location }}</div>
                            
                            <div class="waveform">
                                <div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            @if($device->isApproved())
                                <label class="toggle-switch" onclick="event.stopPropagation()">
                                    <input type="checkbox" {{ $device->isOn() ? 'checked' : '' }}
                                           onchange="toggleDevice({{ $device->id }}, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                            @else
                                <span class="text-muted" style="font-size:0.8rem">Pending Approval</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Back -->
                    <div class="flip-card-back">
                        <div class="text-center w-100">
                            <div class="mb-3" style="font-family:'Poppins', sans-serif; font-weight:600;">{{ $device->name }}</div>
                            <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom: 5px;">ID: {{ $device->device_id }}</div>
                            <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom: 15px;">Type: {{ ucfirst($device->type) }}</div>
                            <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom: 15px;">
                                Last seen: {{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}
                            </div>
                            
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('user.devices.show', $device) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Logs
                                </a>
                                <a href="{{ route('user.devices.edit', $device) }}" class="btn btn-sm btn-light">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </div>
                            <form method="POST" action="{{ route('user.devices.destroy', $device) }}" class="mt-2"
                                  onsubmit="return confirm('Delete {{ $device->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none">
                                    <i class="bi bi-trash"></i> Delete Device
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $devices->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
function toggleDevice(deviceId, checkbox) {
    fetch(`/dashboard/devices/${deviceId}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            checkbox.checked = !checkbox.checked;
            alert(data.message);
        } else {
            const cardFront = document.getElementById('device-card-' + deviceId);
            if(checkbox.checked) {
                cardFront.classList.add('device-is-on');
            } else {
                cardFront.classList.remove('device-is-on');
            }
        }
    })
    .catch(() => { checkbox.checked = !checkbox.checked; });
}
</script>
@endpush
