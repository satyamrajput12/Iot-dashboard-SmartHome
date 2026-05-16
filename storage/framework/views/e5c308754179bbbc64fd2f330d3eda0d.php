<?php $__env->startSection('title', 'Manage Devices'); ?>
<?php $__env->startSection('page-title', 'Device Management'); ?>

<?php $__env->startSection('content'); ?>

<!-- Filters -->
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Search name, ID, location...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="thermostat" <?php echo e(request('type')=='thermostat'?'selected':''); ?>>Thermostat</option>
                <option value="light"      <?php echo e(request('type')=='light'?'selected':''); ?>>Light</option>
                <option value="alarm"      <?php echo e(request('type')=='alarm'?'selected':''); ?>>Alarm</option>
                <option value="camera"     <?php echo e(request('type')=='camera'?'selected':''); ?>>Camera</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="approval" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="pending"  <?php echo e(request('approval')=='pending'?'selected':''); ?>>Pending</option>
                <option value="approved" <?php echo e(request('approval')=='approved'?'selected':''); ?>>Approved</option>
                <option value="rejected" <?php echo e(request('approval')=='rejected'?'selected':''); ?>>Rejected</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="user_id" class="form-select form-select-sm">
                <option value="">All Users</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id')==$user->id?'selected':''); ?>><?php echo e($user->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
            <a href="<?php echo e(route('admin.devices')); ?>" class="btn btn-light btn-sm">Clear</a>
        </div>
    </form>
</div>

<!-- Devices Table -->
<div class="table-card">
    <div class="p-3 border-bottom">
        <span style="font-size:.85rem;color:#64748b"><?php echo e($devices->total()); ?> device(s)</span>
    </div>
    <?php if($devices->isEmpty()): ?>
        <div class="text-center py-5 text-muted">No devices found.</div>
    <?php else: ?>
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
                <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="fw-600" style="font-size:.9rem"><?php echo e($device->name); ?></div>
                        <div style="font-size:.73rem;color:#94a3b8"><?php echo e($device->device_id); ?></div>
                    </td>
                    <td style="font-size:.88rem">
                        <div><?php echo e($device->user->name); ?></div>
                        <div style="font-size:.75rem;color:#94a3b8"><?php echo e($device->user->email); ?></div>
                    </td>
                    <td><span class="badge bg-light text-dark border" style="font-size:.75rem"><?php echo e($device->getTypeLabel()); ?></span></td>
                    <td style="font-size:.85rem"><?php echo e($device->location); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($device->getApprovalBadgeClass()); ?> bg-opacity-10 text-<?php echo e($device->getApprovalBadgeClass()); ?>" style="font-size:.75rem">
                            <?php echo e(ucfirst($device->approval_status)); ?>

                        </span>
                        <?php if($device->approval_status === 'rejected' && $device->rejection_reason): ?>
                            <div style="font-size:.72rem;color:#94a3b8;max-width:150px" class="text-truncate" title="<?php echo e($device->rejection_reason); ?>">
                                <?php echo e($device->rejection_reason); ?>

                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-<?php echo e($device->status); ?>"><?php echo e(strtoupper($device->status)); ?></span>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <?php if($device->approval_status !== 'approved'): ?>
                            <form method="POST" action="<?php echo e(route('admin.devices.approve', $device)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-sm btn-success py-0 px-2" style="font-size:.75rem">✓</button>
                            </form>
                            <?php endif; ?>
                            <?php if($device->approval_status !== 'rejected'): ?>
                            <button class="btn btn-sm btn-warning py-0 px-2" style="font-size:.75rem"
                                    onclick="showRejectModal(<?php echo e($device->id); ?>, '<?php echo e(addslashes($device->name)); ?>')">✗</button>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('admin.devices.simulate-error', $device)); ?>">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-light py-0 px-2" style="font-size:.75rem" title="Simulate Error">
                                    <i class="bi bi-bug"></i>
                                </button>
                            </form>
                            <form method="POST" action="<?php echo e(route('admin.devices.delete', $device)); ?>"
                                  onsubmit="return confirm('Permanently delete <?php echo e($device->name); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-light text-danger py-0 px-2" style="font-size:.75rem">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="p-3"><?php echo e($devices->links('pagination::bootstrap-5')); ?></div>
    <?php endif; ?>
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
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showRejectModal(id, name) {
    document.getElementById('rejectDeviceName').textContent = name;
    document.getElementById('rejectForm').action = `/admin/devices/${id}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\satya\OneDrive\Desktop\IOT-DASHBOARD\resources\views/admin/devices/index.blade.php ENDPATH**/ ?>