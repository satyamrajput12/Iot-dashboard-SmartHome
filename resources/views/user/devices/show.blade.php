@extends('layouts.app')
@section('title', $device->name . ' — Logs')
@section('page-title', 'Device Details & Logs')

@section('content')
<div class="row g-4">

    <!-- Device Info Card -->
    <div class="col-12 col-lg-4">
        <div class="table-card p-4">
            <div class="text-center mb-4">
                <div class="device-type-icon type-{{ $device->type }} mx-auto mb-3" style="width:60px;height:60px;font-size:1.8rem">
                    <i class="bi {{ $device->getTypeIcon() }}"></i>
                </div>
                <h5 class="fw-600">{{ $device->name }}</h5>
                <span class="badge bg-{{ $device->getApprovalBadgeClass() }} bg-opacity-10 text-{{ $device->getApprovalBadgeClass() }}">
                    {{ ucfirst($device->approval_status) }}
                </span>
            </div>

            <table class="table table-sm" style="font-size:.88rem">
                <tr><td class="text-muted">Device ID</td><td class="fw-500">{{ $device->device_id }}</td></tr>
                <tr><td class="text-muted">Type</td><td>{{ $device->getTypeLabel() }}</td></tr>
                <tr><td class="text-muted">Location</td><td><i class="bi bi-geo-alt me-1"></i>{{ $device->location }}</td></tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td><span class="status-{{ $device->status }}">{{ strtoupper($device->status) }}</span></td>
                </tr>
                @if($device->type === 'thermostat')
                <tr><td class="text-muted">Temperature</td><td>{{ $device->temperature ?? '—' }}°C</td></tr>
                @endif
                <tr><td class="text-muted">Last Seen</td><td style="font-size:.8rem">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</td></tr>
                <tr><td class="text-muted">Registered</td><td style="font-size:.8rem">{{ $device->created_at->format('M d, Y') }}</td></tr>
            </table>

            @if($device->description)
                <div class="alert alert-light" style="font-size:.85rem">{{ $device->description }}</div>
            @endif

            @if($device->approval_status === 'rejected' && $device->rejection_reason)
                <div class="alert alert-danger" style="font-size:.82rem">
                    <strong>Rejection reason:</strong> {{ $device->rejection_reason }}
                </div>
            @endif

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('user.devices.edit', $device) }}" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('user.devices.index') }}" class="btn btn-light btn-sm flex-fill">Back</a>
            </div>
        </div>
    </div>

    <!-- Logs -->
    <div class="col-12 col-lg-8">
        <div class="table-card">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-600"><i class="bi bi-journal-text me-2"></i>Device Logs & Troubleshooting</h6>
                <span style="font-size:.8rem;color:#64748b">{{ $logs->total() }} total entries</span>
            </div>

            @if($logs->isEmpty())
                <div class="text-center py-5">
                    <div style="font-size:2.5rem">📋</div>
                    <p class="text-muted mt-2">No logs yet for this device.</p>
                </div>
            @else
                <div class="p-0">
                    @foreach($logs as $log)
                    <div class="p-3 border-bottom d-flex gap-3 align-items-start" style="font-size:.88rem">
                        <div class="log-badge log-{{ $log->log_type }}" style="min-width:70px;text-align:center;margin-top:.15rem">
                            <i class="bi {{ $log->getIcon() }}"></i> {{ ucfirst($log->log_type) }}
                        </div>
                        <div style="flex:1">
                            <div class="fw-500">{{ $log->action }}</div>
                            <div style="color:#64748b;font-size:.82rem;margin-top:.2rem">{{ $log->message }}</div>
                            @if($log->metadata)
                                <div style="font-size:.75rem;color:#94a3b8;margin-top:.2rem;font-family:monospace">
                                    {{ json_encode($log->metadata) }}
                                </div>
                            @endif
                        </div>
                        <div style="text-align:right;white-space:nowrap">
                            <div style="font-size:.78rem;color:#64748b">{{ $log->created_at->diffForHumans() }}</div>
                            @if($log->ip_address)
                                <div style="font-size:.72rem;color:#94a3b8">{{ $log->ip_address }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="p-3">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
