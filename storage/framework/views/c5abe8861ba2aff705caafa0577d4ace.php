<?php $__env->startSection('title', 'Add New Device'); ?>
<?php $__env->startSection('page-title', 'Register New Device'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">

        <div class="table-card p-4">
            <div class="mb-4">
                <h5 class="fw-600 mb-1">Device Registration</h5>
                <p class="text-muted mb-0" style="font-size:.88rem">
                    Fill in the details below. Your device will be reviewed by an administrator before it can be controlled.
                </p>
            </div>

            <form method="POST" action="<?php echo e(route('user.devices.store')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label fw-500">Device Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>"
                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="e.g. Living Room Light">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row g-3 mb-3">
                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label fw-500">Device Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="typeSelect" onchange="toggleTempField()">
                            <option value="">— Select Type —</option>
                            <option value="thermostat" <?php echo e(old('type')=='thermostat'?'selected':''); ?>>🌡️ Thermostat</option>
                            <option value="light"      <?php echo e(old('type')=='light'?'selected':''); ?>>💡 Light</option>
                            <option value="alarm"      <?php echo e(old('type')=='alarm'?'selected':''); ?>>🔔 Alarm</option>
                            <option value="camera"     <?php echo e(old('type')=='camera'?'selected':''); ?>>📹 Camera</option>
                        </select>
                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Location -->
                    <div class="col-md-6">
                        <label class="form-label fw-500">Location / Room <span class="text-danger">*</span></label>
                        <input type="text" name="location" value="<?php echo e(old('location')); ?>"
                               class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="e.g. Living Room, Kitchen">
                        <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Temperature (thermostat only) -->
                <div class="mb-3" id="tempField" style="display:none">
                    <label class="form-label fw-500">Initial Temperature (°C)</label>
                    <input type="number" name="temperature" value="<?php echo e(old('temperature', 22)); ?>"
                           class="form-control <?php $__errorArgs = ['temperature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           min="-50" max="100" step="0.5" placeholder="22.0">
                    <div class="form-text">Set the initial target temperature for this thermostat.</div>
                    <?php $__errorArgs = ['temperature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label fw-500">Description <span class="text-muted" style="font-weight:400">(optional)</span></label>
                    <textarea name="description" rows="3"
                              class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              placeholder="Add any notes about this device..."><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <a href="<?php echo e(route('user.devices.index')); ?>" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleTempField() {
    const type = document.getElementById('typeSelect').value;
    document.getElementById('tempField').style.display = type === 'thermostat' ? 'block' : 'none';
}
// Run on page load in case of old() value
toggleTempField();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\satya\OneDrive\Desktop\IOT-DASHBOARD\resources\views/user/devices/create.blade.php ENDPATH**/ ?>