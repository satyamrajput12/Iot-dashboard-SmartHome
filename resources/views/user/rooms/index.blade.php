@extends('layouts.app')
@section('title', 'Rooms')
@section('page-title', 'Floor Plan & Rooms')

@push('styles')
<style>
    .room-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(50px);
        -webkit-backdrop-filter: blur(50px);
        border-radius: 32px;
        border: 1px solid rgba(255,255,255,0.08);
        padding: 1.8rem;
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: pointer;
        display: block;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    .room-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        opacity: 0; transition: opacity 0.4s;
    }
    .room-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 30px 60px rgba(0,0,0,0.4), 0 0 20px rgba(255, 181, 167, 0.2);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .room-card:hover::before { opacity: 1; }
    
    .room-icon {
        width: 64px; height: 64px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: var(--text-main);
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: inset 0 0 15px rgba(255,255,255,0.05);
        transition: all 0.3s ease;
    }
    .room-card:hover .room-icon {
        background: rgba(255, 181, 167, 0.15);
        color: #ffb5a7;
        box-shadow: 0 0 20px rgba(255, 181, 167, 0.4), inset 0 0 10px rgba(255, 181, 167, 0.2);
        border-color: rgba(255, 181, 167, 0.5);
        transform: scale(1.1) rotate(5deg);
    }
    
    .room-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--text-main);
        font-weight: 600;
    }
    
    .health-ring {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: bold;
        background: conic-gradient(#ffb5a7 var(--health-deg), rgba(255,255,255,0.1) 0deg);
        position: relative;
        box-shadow: 0 0 10px rgba(255, 181, 167, 0.2);
    }
    .health-ring::after {
        content: ''; position: absolute;
        width: 35px; height: 35px; background: rgba(0, 0, 0, 0.4); border-radius: 50%;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
    }
    .health-value { position: relative; z-index: 2; color: var(--text-main); }
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
