<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In — SmartHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { margin: 0; padding: 0; background-color: #0b1120; overflow-x: hidden; color: #ffffff; }
        .split-layout { display: flex; width: 100vw; min-height: 100vh; }
        
        /* Left Panel - Form Area */
        .left-panel { flex: 1; display: flex; flex-direction: column; background: #0b1120; position: relative; z-index: 10; border-right: 1px solid rgba(255,255,255,0.05); }
        .header-bar { padding: 2rem 3rem; display: flex; justify-content: space-between; align-items: center; }
        .brand-logo { display: flex; align-items: center; gap: 10px; font-size: 1.5rem; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; text-decoration: none; }
        .brand-logo i { color: #8b5cf6; font-size: 1.8rem; }
        
        .nav-text { font-size: 0.95rem; color: rgba(255,255,255,0.6); font-weight: 500; }
        .nav-link { color: #8b5cf6; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .nav-link:hover { color: #a78bfa; text-decoration: underline; }
        
        .form-wrapper { max-width: 440px; width: 100%; margin: auto; padding: 2rem; }
        .form-title { font-size: 2.2rem; font-weight: 800; color: #ffffff; margin-bottom: 0.5rem; letter-spacing: -1px; }
        .form-subtitle { font-size: 1rem; color: rgba(255,255,255,0.6); margin-bottom: 2.5rem; line-height: 1.5; }
        
        .form-control {
            background-color: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.85rem 1.2rem;
            font-size: 0.95rem;
            color: #ffffff !important;
            transition: all 0.2s;
            box-shadow: none;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.4); font-weight: 400; }
        .form-control:focus {
            background-color: rgba(255,255,255,0.05);
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
            color: #ffffff !important;
        }
        
        .form-label, .form-check-label { color: rgba(255,255,255,0.8) !important; }
        
        .input-group-gap { margin-bottom: 1.25rem; position: relative; }
        .password-toggle { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.5); cursor: pointer; font-size: 1.1rem; transition: color 0.2s; }
        .password-toggle:hover { color: rgba(255,255,255,0.8); }
        
        .form-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .forgot-link { color: #8b5cf6; text-decoration: none; font-weight: 500; }
        .forgot-link:hover { text-decoration: underline; }
        
        .btn-submit {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            border: none; font-weight: 600; padding: 0.9rem;
            border-radius: 12px; color: white; width: 100%;
            transition: all 0.3s; font-size: 1rem; letter-spacing: 0.3px;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(139, 92, 246, 0.6); color: white; }
        
        .divider { display: flex; align-items: center; text-align: center; margin: 1.5rem 0; color: rgba(255,255,255,0.4); font-size: 0.85rem; font-weight: 500; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .divider span { padding: 0 15px; }
        
        .btn-google {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: #ffffff; font-weight: 600;
            padding: 0.85rem; border-radius: 12px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: all 0.2s; font-size: 0.95rem;
        }
        .btn-google:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2); }
        
        .demo-box { background: rgba(139, 92, 246, 0.05); border: 1px dashed rgba(139, 92, 246, 0.3); border-radius: 12px; padding: 1rem 1.25rem; margin-top: 2rem; font-size: 0.85rem; color: rgba(255,255,255,0.6); }
        .demo-box strong { color: #ffffff; }
        
        /* Right Panel - Showcase Area */
        .right-panel {
            flex: 1; 
            background: #060913;
            display: flex; flex-direction: column; align-items: center; 
            padding: 2rem; position: sticky; top: 0; height: 100vh; align-self: flex-start; overflow-y: auto; overflow-x: hidden;
            border-left: 1px solid rgba(255,255,255,0.02);
        }
        
        /* Decorative Background Elements */
        .bg-shape-1 { position: absolute; top: -10%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; }
        .bg-shape-2 { position: absolute; bottom: -10%; left: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; }
        
        .showcase-content { max-width: 500px; position: relative; z-index: 2; width: 100%; text-align: center; margin-top: auto; margin-bottom: auto; padding: 2rem 0; }
        
        .preview-image-container { margin-bottom: 1.5rem; perspective: 1000px; max-width: 400px; margin-left: auto; margin-right: auto; }
        .preview-image {
            width: 100%; border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255,255,255,0.1);
            transform-style: preserve-3d;
        }
        
        .showcase-text h2 { color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: 0.75rem; letter-spacing: -0.5px; }
        .showcase-text p { color: rgba(255,255,255,0.6); font-size: 0.95rem; line-height: 1.5; max-width: 450px; margin: 0 auto; }
        
        /* Animations */
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(20px); }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
        @media (max-width: 991px) {
            .split-layout { flex-direction: column; }
            .right-panel { display: none; }
            .header-bar { padding: 1.5rem; }
            .form-wrapper { padding: 1.5rem; }
        }

        /* Light Theme Overrides */
        [data-theme="light"] body { background-color: #f8fafc; color: #0f172a; }
        [data-theme="light"] .left-panel { background-color: #f8fafc; border-right: 1px solid rgba(0,0,0,0.05); }
        [data-theme="light"] .right-panel { background-color: #f1f5f9; border-left: 1px solid rgba(0,0,0,0.05); }
        [data-theme="light"] .brand-logo { color: #0f172a; }
        [data-theme="light"] .nav-text { color: #475569; }
        [data-theme="light"] .form-title { color: #0f172a; }
        [data-theme="light"] .form-subtitle { color: #475569; }
        [data-theme="light"] .form-label, [data-theme="light"] .form-check-label { color: #334155 !important; }
        [data-theme="light"] .form-control { background-color: #ffffff; border-color: #cbd5e1; color: #0f172a !important; }
        [data-theme="light"] .form-control:focus { background-color: #ffffff; border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15); }
        [data-theme="light"] .form-control::placeholder { color: #94a3b8; }
        [data-theme="light"] .password-toggle { color: #94a3b8; }
        [data-theme="light"] .divider { color: #94a3b8; }
        [data-theme="light"] .divider::before, [data-theme="light"] .divider::after { border-bottom: 1px solid #e2e8f0; }
        [data-theme="light"] .btn-google { background-color: #ffffff; border-color: #cbd5e1; color: #334155; }
        [data-theme="light"] .btn-google:hover { background-color: #f8fafc; }
        [data-theme="light"] .showcase-text h2 { color: #0f172a; }
        [data-theme="light"] .showcase-text p { color: #475569; }
        [data-theme="light"] .demo-box { background: rgba(139, 92, 246, 0.05); border: 1px dashed rgba(139, 92, 246, 0.3); color: #475569; }
        [data-theme="light"] .demo-box strong { color: #0f172a; }

        /* Smooth Transitions */
        body, .left-panel, .right-panel, .form-control, .btn-google, .nav-text, .brand-logo, .form-title, .form-subtitle, .showcase-text h2, .showcase-text p {
            transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
        }
    </style>
</head>
<body>
    <div class="split-layout">
        
        <!-- Left Form Panel -->
        <div class="left-panel">
            <div class="header-bar fade-in-up">
                <a href="/" class="brand-logo">
                    <i class="bi bi-house-gear-fill"></i> SmartHome
                </a>
                <div class="nav-text" style="display:flex; align-items:center; gap: 1rem;">
                    <button id="theme-toggle" style="background:none; border:none; color:inherit; font-size:1.2rem; cursor:pointer;" title="Toggle Theme">
                        <i class="bi bi-moon-fill" id="theme-icon"></i>
                    </button>
                    <div>New user? <a href="{{ route('register') }}" class="nav-link">Create an account</a></div>
                </div>
            </div>
            
            <div class="form-wrapper fade-in-up delay-1">
                <h1 class="form-title">Welcome Back</h1>
                <p class="form-subtitle">Enter your details to access your smart home dashboard.</p>
                
                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.9rem;">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="input-group-gap">
                        <label class="form-label text-muted small fw-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="name@example.com" required autofocus>
                    </div>
                    
                    <div class="input-group-gap">
                        <label class="form-label text-muted small fw-semibold">Password</label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input mt-0" {{ old('remember') ? 'checked' : '' }} style="cursor:pointer; border-color: #cbd5e1;">
                            <label class="form-check-label text-muted" for="remember" style="cursor:pointer;">Remember me</label>
                        </div>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-submit">Log In</button>
                    
                    <div class="divider"><span>OR</span></div>
                    
                    <button type="button" class="btn-google" onclick="alert('Google Authentication is not configured yet. To make this work, we need to integrate Laravel Socialite and setup Google API Keys. For now, please use the demo credentials.')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18px" height="18px"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
                        Sign in with Google
                    </button>
                    
                    <div class="demo-box">
                        <div style="font-weight:700; margin-bottom:8px; color:#1e293b;">
                            <i class="bi bi-key-fill text-primary"></i> Demo Credentials
                        </div>
                        <div class="mb-1"><strong>Admin:</strong> admin@iot.com / password</div>
                        <div><strong>User:</strong> alice@iot.com / password</div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Right Features Panel -->
        <div class="right-panel">
            <div class="bg-shape-1"></div>
            <div class="bg-shape-2"></div>
            
            <div class="showcase-content">
                <div class="preview-image-container fade-in-up delay-1">
                    <img src="{{ asset('images/dashboard-preview.png') }}" alt="SmartHome Dashboard" class="preview-image" data-tilt data-tilt-max="5" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.3">
                </div>
                
                <div class="showcase-text fade-in-up delay-2">
                    <h2>Manage your connected life.</h2>
                    <p>Experience seamless control of your smart devices. Secure, monitor, and automate your entire home effortlessly.</p>
                </div>
            </div>
        </div>
        
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        function updateThemeIcon(theme) {
            if (theme === 'light') {
                themeIcon.classList.remove('bi-sun-fill');
                themeIcon.classList.add('bi-moon-fill');
            } else {
                themeIcon.classList.remove('bi-moon-fill');
                themeIcon.classList.add('bi-sun-fill');
            }
        }
        
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if(themeIcon) { updateThemeIcon(currentTheme); }

        if(themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                let theme = document.documentElement.getAttribute('data-theme');
                let newTheme = theme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });
        }
    </script>
</body>
</html>
