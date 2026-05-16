<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #1e1b4b, #4338ca); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: #fff; }
        .code { font-size: 8rem; font-weight: 800; line-height: 1; opacity: .15; }
    </style>
</head>
<body class="text-center">
    <div>
        <div class="code">404</div>
        <div style="font-size:3rem;margin:-2rem 0 1rem">🏠</div>
        <h2 class="fw-700">Page Not Found</h2>
        <p style="opacity:.7">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ url('/') }}" class="btn btn-light px-4 mt-2">Go Home</a>
    </div>
</body>
</html>
