@extends('layouts.app')
@section('title', 'Manage Devices')
@section('page-title', 'Device Management')

@section('content')

<!-- Filters -->
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name, ID, location...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="thermostat" {{ request('type') == 'thermostat' ? 'selected' : '' }}>Thermostat</option>
                <option value="light" {{ request('type') == 'light' ? 'selected' : '' }}>Light</option>
                <option value="alarm" {{ request('type') == 'alarm' ? 'selected' : '' }}>Alarm</option>
                <option value="camera" {{ request('type') == 'camera' ? 'selected' : '' }}>Camera</option>
                <option value="smart_plug" {{ request('type') == 'smart_plug' ? 'selected' : '' }}>Smart Plug</option>
                <option value="door_lock" {{ request('type') == 'door_lock' ? 'selected' : '' }}>Door Lock</option>
                <option value="motion_sensor" {{ request('type') == 'motion_sensor' ? 'selected' : '' }}>Motion Sensor</option>
                <option value="smoke_detector" {{ request('type') == 'smoke_detector' ? 'selected' : '' }}>Smoke Detector</option>
                <option value="water_sensor" {{ request('type') == 'water_sensor' ? 'selected' : '' }}>Water Leak Sensor</option>
                <option value="blinds" {{ request('type') == 'blinds' ? 'selected' : '' }}>Smart Blinds</option>
                <option value="speaker" {{ request('type') == 'speaker' ? 'selected' : '' }}>Smart Speaker</option>
                <option value="vacuum" {{ request('type') == 'vacuum' ? 'selected' : '' }}>Robot Vacuum</option>
                <option value="air_purifier" {{ request('type') == 'air_purifier' ? 'selected' : '' }}>Air Purifier</option>
                <option value="tv" {{ request('type') == 'tv' ? 'selected' : '' }}>Smart TV</option>
                <option value="garage_door" {{ request('type') == 'garage_door' ? 'selected' : '' }}>Garage Door</option>
                <option value="fridge" {{ request('type') == 'fridge' ? 'selected' : '' }}>Smart Fridge</option>
                <option value="oven" {{ request('type') == 'oven' ? 'selected' : '' }}>Smart Oven</option>
                <option value="washer" {{ request('type') == 'washer' ? 'selected' : '' }}>Smart Washer</option>
                <option value="dryer" {{ request('type') == 'dryer' ? 'selected' : '' }}>Smart Dryer</option>
                <option value="kettle" {{ request('type') == 'kettle' ? 'selected' : '' }}>Smart Kettle</option>
                <option value="fan" {{ request('type') == 'fan' ? 'selected' : '' }}>Smart Fan</option>
                <option value="mirror" {{ request('type') == 'mirror' ? 'selected' : '' }}>Smart Mirror</option>
                <option value="pet_feeder" {{ request('type') == 'pet_feeder' ? 'selected' : '' }}>Pet Feeder</option>
                <option value="plant_monitor" {{ request('type') == 'plant_monitor' ? 'selected' : '' }}>Plant Monitor</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="approval" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="pending"  {{ request('approval')=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ request('approval')=='approved'?'selected':'' }}>Approved</option>
                <option value="rejected" {{ request('approval')=='rejected'?'selected':'' }}>Rejected</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="user_id" class="form-select form-select-sm">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id')==$user->id?'selected':'' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
            <a href="{{ route('admin.devices') }}" class="btn btn-light btn-sm">Clear</a>
        </div>
    </form>
</div>

<!-- Devices Table -->
<div class="table-card">
    <div class="p-3 border-bottom">
        <span style="font-size:.85rem;color:#64748b">{{ $devices->total() }} device(s)</span>
    </div>
    @if($devices->isEmpty())
        <div class="text-center py-5 text-muted">No devices found.</div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Owner</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Approval</th>
                    <th>Power</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($devices as $device)
                <tr>
                    <td>
                        <div class="fw-600" style="font-size:.9rem">{{ $device->name }}</div>
                        <div style="font-size:.73rem;color:#94a3b8">{{ $device->device_id }}</div>
                    </td>
                    <td style="font-size:.88rem">
                        <div>{{ $device->user->name }}</div>
                        <div style="font-size:.75rem;color:#94a3b8">{{ $device->user->email }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark border" style="font-size:.75rem">{{ $device->getTypeLabel() }}</span></td>
                    <td style="font-size:.85rem">{{ $device->location }}</td>
                    <td>
                        <span class="badge bg-{{ $device->getApprovalBadgeClass() }} bg-opacity-10 text-{{ $device->getApprovalBadgeClass() }}" style="font-size:.75rem">
                            {{ ucfirst($device->approval_status) }}
                        </span>
                        @if($device->approval_status === 'rejected' && $device->rejection_reason)
                            <div style="font-size:.72rem;color:#94a3b8;max-width:150px" class="text-truncate" title="{{ $device->rejection_reason }}">
                                {{ $device->rejection_reason }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="status-{{ $device->status }}">{{ strtoupper($device->status) }}</span>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @if($device->approval_status !== 'approved')
                            <form method="POST" action="{{ route('admin.devices.approve', $device) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success py-0 px-2" style="font-size:.75rem">✓</button>
                            </form>
                            @endif
                            @if($device->approval_status !== 'rejected')
                            <button class="btn btn-sm btn-warning py-0 px-2" style="font-size:.75rem"
                                    onclick="showRejectModal({{ $device->id }}, '{{ addslashes($device->name) }}')">✗</button>
                            @endif

                            <form method="POST" action="{{ route('admin.devices.delete', $device) }}"
                                  onsubmit="return confirm('Permanently delete {{ $device->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger py-0 px-2" style="font-size:.75rem">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $devices->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px">
            <div class="modal-header border-0">
                <h6 class="modal-title">Reject: <span id="rejectDeviceName"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body pt-0">
                    <textarea name="reason" class="form-control" rows="3"
                              placeholder="Reason for rejection..." required></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRejectModal(id, name) {
    document.getElementById('rejectDeviceName').textContent = name;
    let url = "{{ route('admin.devices.reject', 'REPLACE_ID') }}";
    document.getElementById('rejectForm').action = url.replace('REPLACE_ID', id);
    bootstrap.Modal.getOrCreateInstance(document.getElementById('rejectModal')).show();
}
</script>
@endpush
