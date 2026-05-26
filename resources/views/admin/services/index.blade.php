@extends('layouts.app')
@section('title', 'Service Requests')
@section('page-title', 'Service Requests')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="card-header border-0 bg-transparent p-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: var(--text-main); font-weight: 600;">All Tech Team Bookings</h5>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Address</th>
                            <th>Amount</th>
                            <th>Payment ID</th>
                            <th>Payment Status</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>
                                <div class="fw-bold" style="color: var(--text-main);">{{ $req->user->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $req->user->email }}</div>
                            </td>
                            <td style="color: var(--text-main); max-width: 250px;">
                                <div class="text-truncate" title="{{ $req->address }}">{{ $req->address }}</div>
                            </td>
                            <td style="color: var(--text-main); font-weight: 600;">₹{{ number_format($req->amount, 2) }}</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $req->razorpay_payment_id ?? 'N/A' }}</td>
                            <td>
                                @if($req->payment_status == 'paid')
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-2">Paid</span>
                                @elseif($req->payment_status == 'failed')
                                    <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill px-3 py-2">Failed</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill px-3 py-2">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($req->status == 'approved')
                                    <span class="badge bg-success rounded-pill px-3 py-2">Approved</span>
                                @elseif($req->status == 'completed')
                                    <span class="badge bg-primary rounded-pill px-3 py-2">Completed</span>
                                @elseif($req->status == 'rejected')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Pending</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($req->status == 'pending')
                                <div class="d-flex gap-2 justify-content-end">
                                    <form action="{{ route('admin.services.approve', $req->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve Request">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.services.reject', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this request?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject Request">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                    <span class="text-muted" style="font-size: 0.85rem;">Action taken</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No service requests found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 border-top" style="border-color: var(--glass-border) !important;">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
