<?php $__env->startSection('title', 'Billing & Subscriptions'); ?>
<?php $__env->startSection('page-title', 'Billing & Subscriptions'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .plan-card {
        background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
        backdrop-filter: var(--glass-blur);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        padding: 2rem;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    .plan-card:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: rgba(255,255,255,0.2);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5), 0 0 20px rgba(59, 130, 246, 0.2);
    }
    .plan-card.active {
        background: linear-gradient(145deg, rgba(59, 130, 246, 0.15), rgba(30, 41, 59, 0.8));
        border: 2px solid var(--primary);
    }
    .plan-card.active::before {
        content: 'CURRENT PLAN';
        position: absolute;
        top: 20px; right: -30px;
        background: var(--primary);
        color: #fff;
        font-size: 0.7rem;
        font-weight: bold;
        padding: 5px 30px;
        transform: rotate(45deg);
    }
    .price { font-family: 'Space Grotesk', sans-serif; font-size: 3rem; font-weight: 700; color: #fff; }
    
    /* Progress Bar */
    .usage-bar {
        height: 10px;
        background: rgba(255,255,255,0.1);
        border-radius: 5px;
        overflow: hidden;
        margin-top: 10px;
    }
    .usage-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 5px;
        width: <?php echo e(($devicesUsed / $deviceLimit) * 100); ?>%;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-4 mb-5">
    <!-- Free Plan -->
    <div class="col-lg-4">
        <div class="plan-card <?php echo e($currentPlan == 'Free' ? 'active' : ''); ?>">
            <h3 style="font-family:'Space Grotesk', sans-serif; color:var(--text-muted);">Free Plan</h3>
            <div class="price">$0<span style="font-size:1rem; color:var(--text-muted); font-weight:400;">/mo</span></div>
            <ul class="list-unstyled mt-4" style="color:var(--text-muted); line-height: 2;">
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Up to 5 Devices</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Basic Analytics</li>
                <li><i class="bi bi-x-circle-fill text-danger me-2"></i> No Log Export</li>
                <li><i class="bi bi-x-circle-fill text-danger me-2"></i> No Priority Support</li>
            </ul>
            <button class="btn btn-outline-light w-100 mt-4 rounded-pill" disabled>Downgrade</button>
        </div>
    </div>
    
    <!-- Pro Plan -->
    <div class="col-lg-4">
        <div class="plan-card <?php echo e($currentPlan == 'Pro' ? 'active' : ''); ?>">
            <h3 style="font-family:'Space Grotesk', sans-serif; color:#3b82f6;">Pro Plan</h3>
            <div class="price">$9.99<span style="font-size:1rem; color:var(--text-muted); font-weight:400;">/mo</span></div>
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
        <div class="plan-card <?php echo e($currentPlan == 'Enterprise' ? 'active' : ''); ?>">
            <h3 style="font-family:'Space Grotesk', sans-serif; color:#a855f7;">Enterprise</h3>
            <div class="price">$29.99<span style="font-size:1rem; color:var(--text-muted); font-weight:400;">/mo</span></div>
            <ul class="list-unstyled mt-4" style="color:var(--text-main); line-height: 2;">
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Unlimited Devices</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Real-time API Access</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Advanced User Roles</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> 24/7 Priority Support</li>
            </ul>
            <button class="btn btn-outline-light w-100 mt-4 rounded-pill">Upgrade</button>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="widget-card p-4" style="background: rgba(30, 41, 59, 0.7); border-radius: 20px; border: 1px solid var(--glass-border);">
            <h5 style="font-family:'Space Grotesk', sans-serif; margin-bottom: 1.5rem;">Current Usage</h5>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <span style="color:var(--text-muted)">Devices Registered</span>
                    <span class="fw-bold"><?php echo e($devicesUsed); ?> / <?php echo e($deviceLimit); ?></span>
                </div>
                <div class="usage-bar"><div class="usage-fill"></div></div>
            </div>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <span style="color:var(--text-muted)">API Calls (Simulated)</span>
                    <span class="fw-bold">12,450 / 50,000</span>
                </div>
                <div class="usage-bar"><div class="usage-fill" style="width: 25%; background: #10b981;"></div></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="widget-card p-4" style="background: rgba(30, 41, 59, 0.7); border-radius: 20px; border: 1px solid var(--glass-border);">
            <h5 style="font-family:'Space Grotesk', sans-serif; margin-bottom: 1.5rem;">Invoice History</h5>
            <div class="table-responsive">
                <table class="table table-borderless text-white mb-0">
                    <thead style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <tr>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Date</th>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Invoice ID</th>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Amount</th>
                            <th class="text-muted fw-normal" style="padding-bottom:10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-3"><?php echo e($inv->date); ?></td>
                            <td class="py-3" style="font-family: monospace;"><?php echo e($inv->id); ?></td>
                            <td class="py-3">$<?php echo e(number_format($inv->amount, 2)); ?></td>
                            <td class="py-3"><span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3"><?php echo e($inv->status); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\satya\OneDrive\Desktop\IOT-DASHBOARD\resources\views/user/billing/index.blade.php ENDPATH**/ ?>