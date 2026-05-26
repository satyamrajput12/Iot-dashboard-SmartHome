@extends('layouts.app')
@section('title', 'Device Logs')
@section('page-title', 'Device Logs & Troubleshooting')

@section('content')

<!-- Filters -->
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search action or message...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="info"    {{ request('type')=='info'?'selected':'' }}>Info</option>
                <option value="warning" {{ request('type')=='warning'?'selected':'' }}>Warning</option>
                <option value="error"   {{ request('type')=='error'?'selected':'' }}>Error</option>
                <option value="control" {{ request('type')=='control'?'selected':'' }}>Control</option>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="device_id" class="form-select form-select-sm">
                <option value="">All Devices</option>
                @foreach($devices as $device)
                    <option value="{{ $device->id }}" {{ request('device_id')==$device->id?'selected':'' }}>
                        {{ $device->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
            <a href="{{ route('admin.logs') }}" class="btn btn-light btn-sm">Clear</a>
        </div>
    </form>
</div>

<!-- Stats bar -->
<div class="row g-3 mb-4">
    @php
        $typeCounts = \App\Models\DeviceLog::selectRaw('log_type, count(*) as cnt')->groupBy('log_type')->pluck('cnt', 'log_type');
    @endphp
    @foreach(['info' => '🔵', 'warning' => '🟡', 'error' => '🔴', 'control' => '🟢'] as $type => $icon)
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div style="font-size:1.5rem">{{ $icon }}</div>
            <div class="stat-value" style="font-size:1.5rem">{{ $typeCounts[$type] ?? 0 }}</div>
            <div class="stat-label">{{ ucfirst($type) }} Logs</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Logs Table -->
<div class="table-card">
    <div class="p-3 border-bottom border-light border-opacity-10">
        <span style="font-size:.85rem;color:rgba(255, 255, 255, 0.6)">{{ $logs->total() }} log entries</span>
    </div>
    @if($logs->isEmpty())
        <div class="text-center py-5 text-white-50">No logs found for the selected filters.</div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0 text-white">
            <thead>
                <tr>
                    <th style="width:90px">Type</th>
                    <th>Action</th>
                    <th>Message</th>
                    <th>Device</th>
                    <th>User</th>
                    <th>IP</th>
                    <th>When</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>
                        <span class="log-badge log-{{ $log->log_type }}">
                            <i class="bi {{ $log->getIcon() }} me-1"></i>{{ ucfirst($log->log_type) }}
                        </span>
                    </td>
                    <td class="fw-500" style="font-size:1rem">{{ $log->action }}</td>
                    <td style="font-size:0.95rem;color:rgba(255, 255, 255, 0.85);max-width:250px">
                        <span title="{{ $log->message }}">{{ \Illuminate\Support\Str::limit($log->message, 80) }}</span>
                    </td>
                    <td style="font-size:0.95rem">
                        @if($log->device)
                            <div class="fw-500 text-white">{{ $log->device->name }}</div>
                            <div style="font-size:0.85rem;color:rgba(255, 255, 255, 0.6)">{{ $log->device->device_id }}</div>
                        @else
                            <span class="text-white-50">—</span>
                        @endif
                    </td>
                    <td style="font-size:0.95rem">{{ $log->user->name ?? '—' }}</td>
                    <td style="font-size:0.9rem;color:rgba(255, 255, 255, 0.6);font-family:monospace">{{ $log->ip_address ?? '—' }}</td>
                    <td style="font-size:0.9rem;color:rgba(255, 255, 255, 0.7);white-space:nowrap">{{ $log->created_at->format('M d, H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $logs->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
