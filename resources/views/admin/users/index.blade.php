@extends('layouts.app')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')

<!-- Filters -->
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or email...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="role" class="form-select form-select-sm">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                <option value="user"  {{ request('role')=='user'?'selected':'' }}>User</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
            </select>
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
            <a href="{{ route('admin.users') }}" class="btn btn-light btn-sm">Clear</a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="table-card">
    <div class="p-3 border-bottom">
        <span style="font-size:.85rem;color:#64748b">{{ $users->total() }} user(s)</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Devices</th>
                    <th>Account Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:36px;height:36px;border-radius:50%;background:#4f46e5;
                                        display:flex;align-items:center;justify-content:center;
                                        color:#fff;font-weight:600;font-size:.9rem">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-600" style="font-size:.9rem">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.65rem">You</span>
                                    @endif
                                </div>
                                <div style="font-size:.78rem;color:#94a3b8">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->isAdmin() ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary' }}" style="font-size:.75rem">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        <span class="fw-600" style="font-size:.9rem">{{ $user->devices_count }}</span>
                        <span class="text-muted" style="font-size:.8rem"> devices</span>
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="status-on">ACTIVE</span>
                        @else
                            <span class="status-off">INACTIVE</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem;color:#64748b">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            @if($user->id !== auth()->id())
                                <!-- Toggle active/inactive -->
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }} py-0 px-2"
                                            style="font-size:.75rem"
                                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}-fill"></i>
                                    </button>
                                </form>
                                <!-- Delete -->
                                <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                                      onsubmit="return confirm('Delete {{ $user->name }} and all their devices?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger py-0 px-2" style="font-size:.75rem">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted" style="font-size:.75rem">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
