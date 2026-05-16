<?php $__env->startSection('title', 'Device Logs'); ?>
<?php $__env->startSection('page-title', 'Device Logs & Troubleshooting'); ?>

<?php $__env->startSection('content'); ?>

<!-- Filters -->
<div class="table-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control" placeholder="Search action or message...">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="info"    <?php echo e(request('type')=='info'?'selected':''); ?>>Info</option>
                <option value="warning" <?php echo e(request('type')=='warning'?'selected':''); ?>>Warning</option>
                <option value="error"   <?php echo e(request('type')=='error'?'selected':''); ?>>Error</option>
                <option value="control" <?php echo e(request('type')=='control'?'selected':''); ?>>Control</option>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="device_id" class="form-select form-select-sm">
                <option value="">All Devices</option>
                <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($device->id); ?>" <?php echo e(request('device_id')==$device->id?'selected':''); ?>>
                        <?php echo e($device->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
            <a href="<?php echo e(route('admin.logs')); ?>" class="btn btn-light btn-sm">Clear</a>
        </div>
    </form>
</div>

<!-- Stats bar -->
<div class="row g-3 mb-4">
    <?php
        $typeCounts = \App\Models\DeviceLog::selectRaw('log_type, count(*) as cnt')->groupBy('log_type')->pluck('cnt', 'log_type');
    ?>
    <?php $__currentLoopData = ['info' => '🔵', 'warning' => '🟡', 'error' => '🔴', 'control' => '🟢']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div style="font-size:1.5rem"><?php echo e($icon); ?></div>
            <div class="stat-value" style="font-size:1.5rem"><?php echo e($typeCounts[$type] ?? 0); ?></div>
            <div class="stat-label"><?php echo e(ucfirst($type)); ?> Logs</div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Logs Table -->
<div class="table-card">
    <div class="p-3 border-bottom">
        <span style="font-size:.85rem;color:#64748b"><?php echo e($logs->total()); ?> log entries</span>
    </div>
    <?php if($logs->isEmpty()): ?>
        <div class="text-center py-5 text-muted">No logs found for the selected filters.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:90px">Type</th>
                    <th>Action</th>
                    <th>Message</th>
                    <th>Device</th>
                    <th>User</th>
                    <th>IP</th>
                    <th>When</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <span class="log-badge log-<?php echo e($log->log_type); ?>">
                            <i class="bi <?php echo e($log->getIcon()); ?> me-1"></i><?php echo e(ucfirst($log->log_type)); ?>

                        </span>
                    </td>
                    <td class="fw-500" style="font-size:.88rem"><?php echo e($log->action); ?></td>
                    <td style="font-size:.82rem;color:#475569;max-width:250px">
                        <span title="<?php echo e($log->message); ?>"><?php echo e(\Illuminate\Support\Str::limit($log->message, 80)); ?></span>
                    </td>
                    <td style="font-size:.82rem">
                        <?php if($log->device): ?>
                            <div class="fw-500"><?php echo e($log->device->name); ?></div>
                            <div style="font-size:.72rem;color:#94a3b8"><?php echo e($log->device->device_id); ?></div>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.82rem"><?php echo e($log->user->name ?? '—'); ?></td>
                    <td style="font-size:.78rem;color:#94a3b8;font-family:monospace"><?php echo e($log->ip_address ?? '—'); ?></td>
                    <td style="font-size:.78rem;color:#64748b;white-space:nowrap"><?php echo e($log->created_at->format('M d, H:i')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="p-3"><?php echo e($logs->links('pagination::bootstrap-5')); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\satya\OneDrive\Desktop\IOT-DASHBOARD\resources\views/admin/logs.blade.php ENDPATH**/ ?>