@extends('layouts.app')
@section('title', 'Rooms')
@section('page-title', 'Floor Plan & Rooms')

@push('styles')
<style>
    .room-card {
        background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
        backdrop-filter: var(--glass-blur);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .room-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 20px rgba(59, 130, 246, 0.2);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .room-icon {
        width: 60px; height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: #fff;
        margin-bottom: 1rem;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .room-title {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }
    .health-ring {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: bold;
        background: conic-gradient(var(--primary) var(--health-deg), rgba(255,255,255,0.1) 0deg);
        position: relative;
    }
    .health-ring::after {
        content: ''; position: absolute;
        width: 35px; height: 35px; background: #0f172a; border-radius: 50%;
    }
    .health-value { position: relative; z-index: 2; }
</style>
@endpush

@section('content')

@if(empty($roomsData))
    <div class="text-center py-5">
        <div style="font-size:3rem">🏠</div>
        <p class="text-muted mt-2">No rooms detected. <a href="{{ route('user.devices.create') }}">Add devices with locations first.</a></p>
    </div>
@else
    <div class="row g-4">
        @foreach($roomsData as $room)
            <div class="col-md-6 col-lg-4">
                <!-- Click redirects to devices filtered by this room -->
                <a href="{{ route('user.devices.index', ['location' => $room->name]) }}" class="room-card" style="--health-deg: {{ $room->health * 3.6 }}deg;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="room-icon">
                            @if(stripos($room->name, 'living') !== false) <i class="bi bi-tv"></i>
                            @elseif(stripos($room->name, 'bed') !== false) <i class="bi bi-moon-stars"></i>
                            @elseif(stripos($room->name, 'kitchen') !== false) <i class="bi bi-cup-hot"></i>
                            @elseif(stripos($room->name, 'bath') !== false) <i class="bi bi-droplet"></i>
                            @else <i class="bi bi-house"></i> @endif
                        </div>
                        <div class="health-ring" title="Room Health Score">
                            <span class="health-value">{{ $room->health }}%</span>
                        </div>
                    </div>
                    
                    <div class="room-title">{{ $room->name }}</div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">Devices</div>
                            <div class="fw-bold">{{ $room->activeCount }} / {{ $room->deviceCount }} <span style="font-size:0.7rem; font-weight:normal;">Active</span></div>
                        </div>
                        <div class="text-end">
                            <div style="font-size:0.75rem; color:var(--text-muted);">Temperature</div>
                            <div class="fw-bold"><i class="bi bi-thermometer-half text-warning me-1"></i>{{ $room->temperature }}°C</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif

@endsection
