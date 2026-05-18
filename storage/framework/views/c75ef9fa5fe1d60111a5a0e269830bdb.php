
<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Admin Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .admin-stat-card {
        background: linear-gradient(145deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
        backdrop-filter: var(--glass-blur);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .admin-stat-card::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: radial-gradient(circle, var(--accent-color) 0%, transparent 70%);
        opacity: 0.15;
        border-radius: 50%;
    }
    
    .count-up {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .activity-feed {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .map-placeholder {
        background: url('https://upload.wikimedia.org/wikipedia/commons/8/80/World_map_-_low_resolution.svg') no-repeat center center;
        background-size: cover;
        opacity: 0.3;
        height: 300px;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        filter: invert(1) sepia(1) hue-rotate(180deg) saturate(3) opacity(0.5);
    }
    .map-dot {
        position: absolute; width: 10px; height: 10px; background: #34d399; border-radius: 50%;
        box-shadow: 0 0 15px #34d399;
        animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    @keyframes ping { 75%, 100% { transform: scale(2.5); opacity: 0; } }
    
    .sys-health-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .sys-health-item:last-child { border-bottom: none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Quick Actions -->
<div class="d-flex justify-content-end gap-3 mb-4">
    <button class="btn btn-outline-light rounded-pill"><i class="bi bi-file-earmark-arrow-down me-2"></i>Export Logs CSV</button>
    <button class="btn btn-primary rounded-pill"><i class="bi bi-check2-all me-2"></i>Approve All Pending</button>
</div>

<!-- Hero Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #3b82f6;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Total Users</div>
            <div class="count-up" data-target="<?php echo e($stats['total_users']); ?>">0</div>
            <div style="font-size:0.85rem; color:#3b82f6;"><i class="bi bi-arrow-up-right"></i> 12% this week</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #8b5cf6;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Total Devices</div>
            <div class="count-up" data-target="<?php echo e($stats['total_devices']); ?>">0</div>
            <div style="font-size:0.85rem; color:#8b5cf6;"><i class="bi bi-arrow-up-right"></i> 5% this week</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #f59e0b;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Pending Approvals</div>
            <div class="count-up text-warning" data-target="<?php echo e($stats['pending']); ?>">0</div>
            <div style="font-size:0.85rem; color:#f59e0b;">Requires attention</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card" style="--accent-color: #10b981;">
            <div style="color:var(--text-muted); font-weight:600; text-transform:uppercase; font-size:0.85rem; margin-bottom:1rem;">Devices Online</div>
            <div class="count-up text-success" data-target="<?php echo e($stats['online_devices']); ?>">0</div>
            <div style="font-size:0.85rem; color:#10b981;">Live connected</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Map Widget -->
    <div class="col-lg-8">
        <div class="admin-stat-card h-100 p-0">
            <div class="p-4 border-bottom" style="border-color: rgba(255,255,255,0.05) !important;">
                <h6 class="mb-0 fw-bold" style="font-family:'Space Grotesk', sans-serif;">Global Device Locations</h6>
            </div>
            <div class="p-4 relative">
                <div class="map-placeholder">
                    <div class="map-dot" style="top: 30%; left: 20%;"></div>
                    <div class="map-dot" style="top: 40%; left: 45%;"></div>
                    <div class="map-dot" style="top: 25%; left: 55%;"></div>
                    <div class="map-dot" style="top: 60%; left: 70%;"></div>
                    <div class="map-dot" style="top: 75%; left: 85%;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Health -->
    <div class="col-lg-4">
        <div class="admin-stat-card h-100">
            <h6 class="mb-4 fw-bold" style="font-family:'Space Grotesk', sans-serif;">System Health</h6>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-hdd-network me-2"></i>Database</span>
                <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3">Connected</span>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-clock me-2"></i>Server Uptime</span>
                <span class="fw-bold">99.9% (45 days)</span>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-cpu me-2"></i>CPU Usage</span>
                <span class="fw-bold text-warning">42%</span>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-memory me-2"></i>RAM Usage</span>
                <div class="w-50">
                    <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                        <div class="progress-bar bg-primary" style="width: 65%;"></div>
                    </div>
                </div>
            </div>
            <div class="sys-health-item">
                <span style="color:var(--text-muted);"><i class="bi bi-code-slash me-2"></i>PHP Version</span>
                <span class="fw-bold">8.2.0</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Real-time Activity Feed -->
    <div class="col-12">
        <div class="admin-stat-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="mb-0 fw-bold" style="font-family:'Space Grotesk', sans-serif;"><span class="spinner-grow spinner-grow-sm text-danger me-2" role="status"></span>Real-time Activity Feed</h6>
                <a href="<?php echo e(route('admin.logs')); ?>" style="font-size:.85rem; color:var(--primary);">View All Logs</a>
            </div>
            
            <div class="activity-feed" id="activityFeed">
                <?php $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-3 border-bottom d-flex gap-3 align-items-center" style="border-color: rgba(255,255,255,0.05) !important;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
                        <?php if($log->log_type == 'info'): ?> <i class="bi bi-info-circle text-info"></i>
                        <?php elseif($log->log_type == 'warning'): ?> <i class="bi bi-exclamation-triangle text-warning"></i>
                        <?php elseif($log->log_type == 'error'): ?> <i class="bi bi-x-circle text-danger"></i>
                        <?php else: ?> <i class="bi bi-activity text-success"></i> <?php endif; ?>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:0.9rem;">
                            <span class="fw-bold text-white"><?php echo e($log->device->name ?? 'System'); ?></span>
                            <span style="color:var(--text-muted);"> — <?php echo e($log->action); ?></span>
                        </div>
                        <div style="font-size:0.75rem; color:#64748b; margin-top:2px;"><?php echo e($log->created_at->diffForHumans()); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Animated Count-Up
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.count-up');
    const speed = 200;

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 15);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    });
    
    // Auto-scroll activity feed slowly to simulate live incoming data
    const feed = document.getElementById('activityFeed');
    setInterval(() => {
        if(feed.scrollTop < feed.scrollHeight - feed.clientHeight) {
            feed.scrollTop += 1;
        }
    }, 100);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\satya\OneDrive\Desktop\IOT-DASHBOARD\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>