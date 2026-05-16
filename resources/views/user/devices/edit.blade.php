@extends('layouts.app')
@section('title', 'Edit Device')
@section('page-title', 'Edit Device')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="table-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-600 mb-0">Edit: {{ $device->name }}</h5>
                    <span class="text-muted" style="font-size:.82rem">ID: {{ $device->device_id }}</span>
                </div>
                <span class="badge bg-{{ $device->getApprovalBadgeClass() }} bg-opacity-10 text-{{ $device->getApprovalBadgeClass() }}">
                    {{ ucfirst($device->approval_status) }}
                </span>
            </div>

            <form method="POST" action="{{ route('user.devices.update', $device) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-500">Device Name</label>
                    <input type="text" name="name" value="{{ old('name', $device->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-500">Device Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror"
                                id="typeSelect" onchange="toggleTempField()">
                            <option value="thermostat" {{ old('type', $device->type)=='thermostat'?'selected':'' }}>🌡️ Thermostat</option>
                            <option value="light"      {{ old('type', $device->type)=='light'?'selected':'' }}>💡 Light</option>
                            <option value="alarm"      {{ old('type', $device->type)=='alarm'?'selected':'' }}>🔔 Alarm</option>
                            <option value="camera"     {{ old('type', $device->type)=='camera'?'selected':'' }}>📹 Camera</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500">Location / Room</label>
                        <input type="text" name="location" value="{{ old('location', $device->location) }}"
                               class="form-control @error('location') is-invalid @enderror">
                        @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3" id="tempField">
                    <label class="form-label fw-500">Temperature (°C)</label>
                    <input type="number" name="temperature" value="{{ old('temperature', $device->temperature) }}"
                           class="form-control @error('temperature') is-invalid @enderror"
                           min="-50" max="100" step="0.5">
                    @error('temperature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-500">Description</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $device->description) }}</textarea>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                    <a href="{{ route('user.devices.index') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTempField() {
    const type = document.getElementById('typeSelect').value;
    document.getElementById('tempField').style.display = type === 'thermostat' ? 'block' : 'none';
}
toggleTempField();
</script>
@endpush
