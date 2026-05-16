<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'IoT Dashboard'); ?> — Smart Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%; max-width: 440px;
            box-shadow: 0 25px 50px rgba(0,0,0,.3);
        }
        .auth-logo {
            width: 56px; height: 56px;
            background: #4f46e5;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1.25rem;
        }
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: .7rem 1rem;
            transition: border-color .2s;
        }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .btn-primary {
            background: #4f46e5; border: none; border-radius: 10px;
            padding: .75rem; font-weight: 600;
            transition: background .2s;
        }
        .btn-primary:hover { background: #3730a3; }
        .alert { border-radius: 10px; border: none; }
        .demo-box {
            background: #f8fafc; border-radius: 10px;
            padding: .85rem 1rem; margin-top: 1.25rem;
            font-size: .82rem; color: #475569;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center">
            <div class="auth-logo">🏠</div>
            <h1 style="font-size:1.5rem;font-weight:700;color:#1e293b">IoT Smart Home</h1>
            <p style="color:#64748b;font-size:.9rem">Device Dashboard</p>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success mt-3 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger mt-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill"></i> <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\satya\OneDrive\Desktop\IOT-DASHBOARD\resources\views/layouts/auth.blade.php ENDPATH**/ ?>