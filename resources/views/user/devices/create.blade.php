@extends('layouts.app')
@section('title', 'Add New Device')
@section('page-title', 'Register New Device')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">

        <div class="table-card p-4">
            <div class="mb-4">
                <h5 class="fw-600 mb-1">Device Registration</h5>
                <p class="text-muted mb-0" style="font-size:.88rem">
                    Fill in the details below. Your device will be reviewed by an administrator before it can be controlled.
                </p>
            </div>

            <form method="POST" action="{{ route('user.devices.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label fw-500">Device Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="e.g. Living Room Light">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label fw-500">Device Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" id="typeSelect" onchange="toggleTempField()">
                            <option value="">— Select Type —</option>
                            <option value="thermostat" {{ old('type')=='thermostat'?'selected':'' }}>🌡️ Thermostat</option>
                            <option value="light"      {{ old('type')=='light'?'selected':'' }}>💡 Light</option>
                            <option value="alarm"      {{ old('type')=='alarm'?'selected':'' }}>🔔 Alarm</option>
                            <option value="camera"     {{ old('type')=='camera'?'selected':'' }}>📹 Camera</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <label class="form-label fw-500">Location / Room <span class="text-danger">*</span></label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               class="form-control @error('location') is-invalid @enderror"
                               placeholder="e.g. Living Room, Kitchen">
                        @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Temperature (thermostat only) -->
                <div class="mb-3" id="tempField" style="display:none">
                    <label class="form-label fw-500">Initial Temperature (°C)</label>
                    <input type="number" name="temperature" value="{{ old('temperature', 22) }}"
                           class="form-control @error('temperature') is-invalid @enderror"
                           min="-50" max="100" step="0.5" placeholder="22.0">
                    <div class="form-text">Set the initial target temperature for this thermostat.</div>
                    @error('temperature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label fw-500">Description <span class="text-muted" style="font-weight:400">(optional)</span></label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Add any notes about this device...">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Info box -->
                <div class="alert alert-info d-flex gap-2 align-items-start mb-4" style="font-size:.85rem">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div>
                        A unique <strong>Device ID</strong> will be automatically generated. Your device will be
                        <strong>pending approval</strong> until an administrator reviews and approves it.
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-cpu me-2"></i>Register Device
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
// Run on page load in case of old() value
toggleTempField();
</script>
@endpush
