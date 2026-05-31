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
        height: 290px;
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
    
    .flip-card.flipped .flip-card-inner {
        transform: rotateY(180deg);
    }
    
    .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: 40px;
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: var(--text-main);
    }
    
    .flip-card-back {
        transform: rotateY(180deg);
        background: rgba(255, 255, 255, 0.08);
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
        background: rgba(128,128,128,0.15);
        color: var(--text-main);
        box-shadow: inset 0 0 15px rgba(128,128,128,0.05);
        transition: all 0.3s;
    }
    
    .device-main-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 15px;
        background: #ffffff;
        padding: 5px;
        margin: 0 auto;
        display: block;
        transition: all 0.3s;
    }
    
    .device-is-on .device-main-img {
        box-shadow: 0 0 25px rgba(212, 179, 255, 0.6);
        border: 2px solid #d4b3ff;
        transform: scale(1.05);
    }
    
    .device-is-on .device-icon-wrapper {
        background: rgba(212, 179, 255, 0.15);
        color: #d4b3ff;
        box-shadow: 0 0 20px rgba(212, 179, 255, 0.4), inset 0 0 10px rgba(212, 179, 255, 0.2);
    }
    
    .device-is-on .device-icon-wrapper::after {
        content: '';
        position: absolute;
        top: -4px; left: -4px; right: -4px; bottom: -4px;
        border-radius: 50%;
        border: 2px solid rgba(212, 179, 255, 0.5);
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
        background: #d4b3ff;
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
    .status-bar { width: 4px; height: 12px; background: rgba(255,255,255,0.3); border-radius: 2px; }
    .status-bar.active { background: #d4b3ff; } /* Lilac for active bars */
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
            <div class="flip-card" id="flip-card-{{ $device->id }}">
                <div class="flip-card-inner">
                    <!-- Front -->
                    <div class="flip-card-front {{ $device->isOn() ? 'device-is-on' : '' }}" id="device-card-{{ $device->id }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="badge bg-{{ $device->getApprovalBadgeClass() }} bg-opacity-10 text-{{ $device->getApprovalBadgeClass() }}" style="font-size:0.65rem;">
                                {{ ucfirst($device->approval_status) }}
                            </span>
                            <div class="d-flex gap-2">
                                <div class="status-bars mt-1" title="Signal Strength">
                                    <div class="status-bar active"></div>
                                    <div class="status-bar active"></div>
                                    <div class="status-bar active"></div>
                                    <div class="status-bar"></div>
                                </div>
                                <button type="button" class="btn btn-link text-white p-0" onclick="flipCard({{ $device->id }})" title="Device Info">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-center w-100 mt-2">
                                @php
                                    $defaultImages = ['light', 'thermostat', 'camera', 'air_purifier', 'ac', 'refrigerator', 'tv', 'purifier', 'speaker'];
                                    $hasDefaultImage = in_array($device->type, $defaultImages);
                                @endphp
                                
                                @if($device->image)
                                    <img src="{{ asset('storage/' . $device->image) }}" alt="{{ $device->name }}" class="device-main-img">
                                @elseif($device->stream_url)
                                    <img src="{{ $device->stream_url }}" alt="{{ $device->name }}" class="device-main-img" style="padding: 0;">
                                @elseif($hasDefaultImage)
                                    <img src="{{ asset('images/devices/' . $device->type . '.png') }}" alt="{{ $device->name }}" class="device-main-img">
                                @else
                                    <div class="device-icon-wrapper">
                                        <i class="bi bi-bell"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-3" style="color: var(--text-main); font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.25rem;">{{ $device->name }}</div>
                            <div style="font-size:0.8rem; color: var(--text-muted);"><i class="bi bi-geo-alt me-1"></i>{{ $device->location }}</div>
                            
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
                                <span style="font-size:0.85rem; color: var(--text-muted) !important; font-weight: 500;">Pending Approval</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Back -->
                    <div class="flip-card-back position-relative">
                        <button type="button" class="btn btn-link text-white p-0 position-absolute" style="top: 15px; right: 20px;" onclick="flipCard({{ $device->id }})" title="Close Info">
                            <i class="bi bi-x-circle fs-5"></i>
                        </button>
                        <div class="text-center w-100 mt-2">
                            @if(isset($device->image) && $device->image)
                                <img src="{{ asset('storage/' . $device->image) }}" alt="{{ $device->name }}" class="rounded-circle mb-3 shadow" style="width: 70px; height: 70px; object-fit: cover; border: 3px solid var(--glass-border);">
                            @endif
                            <div class="mb-2" style="color: var(--text-main); font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.25rem;">{{ $device->name }}</div>
                            <div style="font-size:0.85rem; color: var(--text-muted); margin-bottom: 5px;">ID: <span style="font-family: monospace;">{{ $device->device_id }}</span></div>
                            <div style="font-size:0.85rem; color: var(--text-muted); margin-bottom: 5px;">Type: {{ ucfirst($device->type) }}</div>
                            <div style="font-size:0.85rem; color: var(--text-muted); margin-bottom: 20px;">
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

function flipCard(id) {
    document.getElementById('flip-card-' + id).classList.toggle('flipped');
}
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
