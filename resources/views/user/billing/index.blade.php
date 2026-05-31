@extends('layouts.app')
@section('title', 'Billing & Subscriptions')
@section('page-title', 'Billing & Subscriptions')

@push('styles')
<style>
    .plan-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        border-radius: 40px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        padding: 2rem;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    .plan-card:hover {
        transform: translateY(-10px) scale(1.02);
    }
    .plan-card.active {
        background: rgba(255, 230, 220, 0.1);
        border: 2px solid var(--secondary);
    }
    .plan-card.active::before {
        content: 'CURRENT PLAN';
        position: absolute;
        top: 20px; right: -30px;
        background: var(--secondary);
        color: #000;
        font-family: 'DotGothic16', sans-serif;
        font-size: 0.7rem;
        font-weight: bold;
        padding: 5px 30px;
        transform: rotate(45deg);
    }
    .price { font-family: 'DotGothic16', sans-serif; font-size: 3rem; font-weight: 700; color: var(--text-main); }
    
    /* Progress Bar */
    .usage-bar {
        height: 10px;
        background: rgba(0,0,0,0.05);
        border-radius: 5px;
        overflow: hidden;
        margin-top: 10px;
    }
    .usage-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 5px;
        width: {{ ($devicesUsed / $deviceLimit) * 100 }}%;
    }
</style>
@endpush

@section('content')

<div class="row g-4 mb-5">
    <!-- Free Plan -->
    <div class="col-lg-4">
        <div class="plan-card {{ $currentPlan == 'Free' ? 'active' : '' }}">
            <h3 class="font-dot" style="color:var(--text-main);">Free Plan</h3>
            <div class="price">₹0<span style="font-size:1rem; color:var(--text-muted); font-weight:400;">/mo</span></div>
            <ul class="list-unstyled mt-4" style="color:var(--text-main); line-height: 2; font-weight: 500;">
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Up to 5 Devices</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Basic Analytics</li>
                <li><i class="bi bi-x-circle-fill text-danger me-2"></i> No Log Export</li>
                <li><i class="bi bi-x-circle-fill text-danger me-2"></i> No Priority Support</li>
            </ul>
            <button class="btn btn-outline-secondary w-100 mt-4 rounded-pill" disabled>Downgrade</button>
        </div>
    </div>
    
    <!-- Pro Plan -->
    <div class="col-lg-4">
        <div class="plan-card {{ $currentPlan == 'Pro' ? 'active' : '' }}">
            <h3 class="font-dot" style="color:var(--text-main);">Pro Plan</h3>
            <div class="price">₹799<span style="font-size:1rem; color:var(--text-muted); font-weight:400;">/mo</span></div>
            <ul class="list-unstyled mt-4" style="color:var(--text-main); line-height: 2;">
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Up to 50 Devices</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Advanced Analytics</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> CSV Log Exports</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Standard Support</li>
            </ul>
            <button class="btn btn-primary w-100 mt-4 rounded-pill">Current Plan</button>
        </div>
    </div>
    
    <!-- Enterprise Plan -->
    <div class="col-lg-4">
        <div class="plan-card {{ $currentPlan == 'Enterprise' ? 'active' : '' }}">
            <h3 class="font-dot" style="color:var(--text-main);">Enterprise</h3>
            <div class="price">₹2499<span style="font-size:1rem; color:var(--text-muted); font-weight:400;">/mo</span></div>
            <ul class="list-unstyled mt-4" style="color:var(--text-main); line-height: 2; font-weight: 500;">
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Unlimited Devices</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Real-time API Access</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Advanced User Roles</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> 24/7 Priority Support</li>
            </ul>
            <button class="btn btn-outline-primary w-100 mt-4 rounded-pill upgrade-btn" data-plan="Enterprise">Upgrade</button>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="widget-card p-4" style="background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-radius: 40px; border: 1px solid var(--glass-border); box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <h5 class="font-dot" style="margin-bottom: 1.5rem; color: var(--text-main); font-weight: 600;">Current Usage</h5>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <span style="color:var(--text-muted)">Devices Registered</span>
                    <span class="fw-bold" style="color: var(--text-main);">{{ $devicesUsed }} / {{ $deviceLimit }}</span>
                </div>
                <div class="usage-bar"><div class="usage-fill"></div></div>
            </div>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <span style="color:var(--text-muted)">API Calls (Simulated)</span>
                    <span class="fw-bold" style="color: var(--text-main);">12,450 / 50,000</span>
                </div>
                <div class="usage-bar"><div class="usage-fill" style="width: 25%; background: #10b981;"></div></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="widget-card p-4" style="background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-radius: 40px; border: 1px solid var(--glass-border); box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <h5 class="font-dot" style="margin-bottom: 1.5rem; color: var(--text-main); font-weight: 600;">Invoice History</h5>
            <div class="table-responsive">
                <table class="table table-borderless mb-0" style="--bs-table-bg: transparent; --bs-table-color: var(--text-main);">
                    <thead style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <tr>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Date</th>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Invoice ID</th>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Amount</th>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $inv)
                        <tr>
                            <td class="py-3" style="color: var(--text-main); font-weight: 500;">{{ $inv->date }}</td>
                            <td class="py-3" style="font-family: monospace; color: var(--text-muted);">{{ $inv->id }}</td>
                            <td class="py-3" style="color: var(--text-main); font-weight: 600;">₹{{ number_format($inv->amount, 0) }}</td>
                            <td class="py-3"><span class="badge rounded-pill px-3 py-2" style="background: rgba(34, 197, 94, 0.2); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.4);">{{ $inv->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Verification Form (Hidden) -->
<form action="{{ route('user.billing.verify-payment') }}" method="POST" id="verifyForm">
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
document.querySelectorAll('.upgrade-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const plan = this.getAttribute('data-plan');
        const originalText = this.innerHTML;
        
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
        this.disabled = true;

        fetch('{{ route("user.billing.purchase") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ plan: plan })
        })
        .then(res => res.json())
        .then(data => {
            this.innerHTML = originalText;
            this.disabled = false;

            if(!data.success) {
                alert(data.message || 'Something went wrong.');
                return;
            }

            var options = {
                "key": data.key,
                "amount": data.amount, // in paise
                "currency": "INR",
                "name": "IoT Dashboard",
                "description": plan + " Plan Upgrade",
                "image": "https://img.icons8.com/3d-fluency/94/combo-chart.png",
                "order_id": data.order_id,
                "handler": function (response){
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
            this.innerHTML = originalText;
            this.disabled = false;
            alert('Something went wrong. Please try again.');
            console.error(err);
        });
    });
});
</script>
@endpush
