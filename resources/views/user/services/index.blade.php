@extends('layouts.app')
@section('title', 'Services')
@section('page-title', 'Book Tech Team')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0" style="background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-radius: 40px; border: 1px solid var(--glass-border); overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <div class="card-header border-0 pt-4 pb-0 px-4" style="background: transparent;">
                <h4 class="mb-0 font-dot" style="color: var(--text-main);">Tech Team Request</h4>
                <p class="mt-2" style="font-size: 0.95rem; color: var(--text-muted);">Having issues with your smart home setup? Book an on-site visit from our tech experts.</p>
            </div>
            <div class="card-body p-4" style="background: transparent;">
                <form id="booking-form">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px;">Service Details</label>
                        <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(59, 130, 246, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.2rem;">
                                        <i class="bi bi-tools"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.95rem; color: var(--text-main);">Tech Team Visit</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">Hardware setup & troubleshooting</div>
                                    </div>
                                </div>
                                <div style="font-weight: 700; font-size: 1.1rem; color: var(--text-main);">₹2.00</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px;">Service Address</label>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="text" class="form-control" id="address_line_1" name="address_line_1" required placeholder="Address Line 1 (House No, Building, Street, Area)">
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" id="address_line_2" name="address_line_2" placeholder="Address Line 2 (Locality, Landmark) - Optional">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="city" name="city" required placeholder="City">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="state" name="state" required placeholder="State">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="pincode" name="pincode" required placeholder="Pincode" pattern="[0-9]{6}">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <small>A nominal fee of ₹2.00 is required to confirm the booking. (Settlements routed to UPI: 7250596948@ybl)</small>
                    </div>

                    <button type="button" id="pay-btn" class="btn btn-blue w-100 py-3 mt-2" style="border-radius: 12px; font-weight: 600;">
                        Pay ₹2.00 & Book Now via Razorpay
                    </button>
                </form>
            </div>
        </div>

        @if($requests->count() > 0)
        <h5 class="mt-5 mb-3 font-dot" style="color: var(--text-main);">Your Recent Requests</h5>
        <div class="card border-0" style="background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-radius: 40px; border: 1px solid var(--glass-border); overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <div class="list-group list-group-flush" style="border-radius: 40px;">
                @foreach($requests as $req)
                <div class="list-group-item p-4 border-bottom" style="background: transparent; border-color: rgba(255,255,255,0.05) !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-weight: 700; font-size: 1rem; color: var(--text-main);">{{ $req->service_name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $req->created_at->format('M d, Y') }} &bull; {{ \Illuminate\Support\Str::limit($req->address, 30) }}</div>
                            <div style="font-size: 0.75rem; color: var(--primary); margin-top: 4px;">Txn: {{ $req->razorpay_payment_id ?? 'N/A' }}</div>
                        </div>
                        <div>
                            @if($req->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                            @elseif($req->status == 'approved')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Approved</span>
                            @elseif($req->status == 'completed')
                                <span class="badge bg-primary px-3 py-2 rounded-pill">Completed</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill">Rejected</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Verification Form (Hidden) -->
<form action="{{ route('user.services.verify-payment') }}" method="POST" id="verifyForm">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    <input type="hidden" name="request_id" id="request_id">
</form>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('pay-btn').onclick = function(e){
    e.preventDefault();
    
    const address_line_1 = document.getElementById('address_line_1').value;
    const address_line_2 = document.getElementById('address_line_2').value;
    const city = document.getElementById('city').value;
    const state = document.getElementById('state').value;
    const pincode = document.getElementById('pincode').value;
    
    if(!address_line_1.trim() || !city.trim() || !state.trim() || !pincode.trim()) {
        alert('Please fill in all mandatory address fields (Line 1, City, State, Pincode).');
        return;
    }

    const btn = document.getElementById('pay-btn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
    btn.disabled = true;

    fetch('{{ route("user.services.book") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            address_line_1: address_line_1,
            address_line_2: address_line_2,
            city: city,
            state: state,
            pincode: pincode
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.innerHTML = 'Pay ₹2.00 & Book Now via Razorpay';
        btn.disabled = false;

        if(!data.success) {
            alert(data.message || 'Something went wrong.');
            return;
        }

        // Open Razorpay Checkout JS
        var options = {
            "key": data.key,
            "amount": data.amount, // in paise
            "currency": "INR",
            "name": "IoT Dashboard",
            "description": "Tech Team Booking",
            "image": "https://img.icons8.com/color/48/smart-home-connection.png",
            "order_id": data.order_id,
            "handler": function (response){
                // Submit signature for verification
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('request_id').value = data.request_id;
                document.getElementById('verifyForm').submit();
            },
            "prefill": {
                "name": "{{ auth()->user()->name }}",
                "email": "{{ auth()->user()->email }}"
            },
            "theme": {
                "color": "#3b82f6"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.on('payment.failed', function (response){
            alert("Payment failed: " + response.error.description);
        });
        rzp1.open();
    })
    .catch(err => {
        btn.innerHTML = 'Pay ₹2.00 & Book Now via Razorpay';
        btn.disabled = false;
        alert('Something went wrong. Please try again.');
        console.error(err);
    });
}
</script>
@endpush
