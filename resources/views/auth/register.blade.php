<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — SmartHome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { margin: 0; padding: 0; background-color: #ffffff; overflow-x: hidden; }
        .split-layout { display: flex; width: 100vw; min-height: 100vh; }
        
        /* Left Panel - Form Area */
        .left-panel { flex: 1; display: flex; flex-direction: column; background: #ffffff; position: relative; z-index: 10; }
        .header-bar { padding: 2rem 3rem; display: flex; justify-content: space-between; align-items: center; }
        .brand-logo { display: flex; align-items: center; gap: 10px; font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; text-decoration: none; }
        .brand-logo i { color: #2563eb; font-size: 1.8rem; }
        
        .nav-text { font-size: 0.95rem; color: #64748b; font-weight: 500; }
        .nav-link { color: #2563eb; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .nav-link:hover { color: #1d4ed8; text-decoration: underline; }
        
        .form-wrapper { max-width: 480px; width: 100%; margin: auto; padding: 2rem; }
        .form-title { font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -1px; }
        .form-subtitle { font-size: 1rem; color: #64748b; margin-bottom: 2.5rem; line-height: 1.5; }
        
        .form-control, .form-select {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.85rem 1.2rem;
            font-size: 0.95rem;
            color: #0f172a;
            transition: all 0.2s;
            box-shadow: none;
        }
        .form-control::placeholder { color: #94a3b8; font-weight: 400; }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        
        .input-group-gap { margin-bottom: 1.25rem; position: relative; }
        .password-toggle { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; cursor: pointer; font-size: 1.1rem; transition: color 0.2s; }
        .password-toggle:hover { color: #475569; }
        
        .btn-submit {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none; font-weight: 600; padding: 0.9rem;
            border-radius: 12px; color: white; margin-top: 1rem; width: 100%;
            transition: all 0.3s; font-size: 1rem; letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3); color: white; }
        
        .terms-text { font-size: 0.85rem; color: #64748b; line-height: 1.5; }
        .terms-text a { color: #2563eb; text-decoration: none; font-weight: 500; }
        .terms-text a:hover { text-decoration: underline; }
        
        /* Right Panel - Showcase Area */
        .right-panel {
            flex: 1; 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            display: flex; flex-direction: column; align-items: center; 
            padding: 2rem; position: sticky; top: 0; height: 100vh; align-self: flex-start; overflow-y: auto; overflow-x: hidden;
        }
        
        /* Decorative Background Elements */
        .bg-shape-1 { position: absolute; top: -10%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; }
        .bg-shape-2 { position: absolute; bottom: -10%; left: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; }
        
        .showcase-content { max-width: 500px; position: relative; z-index: 2; width: 100%; margin-top: auto; margin-bottom: auto; padding: 2rem 0; }
        
        .preview-image-container { margin-bottom: 1.5rem; perspective: 1000px; max-width: 400px; margin-left: auto; margin-right: auto; }
        .preview-image {
            width: 100%; border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255,255,255,0.1);
            transform-style: preserve-3d;
        }
        
        .features-grid { display: grid; gap: 1.5rem; }
        .feature-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 1.25rem; border-radius: 16px; backdrop-filter: blur(10px); display: flex; gap: 1rem; align-items: flex-start; transition: all 0.3s; }
        .feature-card:hover { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); transform: translateY(-2px); }
        .feature-icon { font-size: 1.5rem; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; flex-shrink: 0; }
        .icon-security { background: rgba(239, 68, 68, 0.1); color: #f87171; }
        .icon-automation { background: rgba(59, 130, 246, 0.1); color: #60a5fa; }
        
        .feature-text h4 { font-size: 1.05rem; font-weight: 600; color: #f8fafc; margin-bottom: 0.25rem; }
        .feature-text p { color: #94a3b8; font-size: 0.9rem; line-height: 1.5; margin: 0; }
        
        /* Animations */
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(20px); }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
        @media (max-width: 991px) {
            .split-layout { flex-direction: column; }
            .right-panel { display: none; }
            .header-bar { padding: 1.5rem; }
            .form-wrapper { padding: 1.5rem; }
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
                <div class="nav-text">
                    Already have an account? <a href="{{ route('login') }}" class="nav-link">Log In</a>
                </div>
            </div>
            
            <div class="form-wrapper fade-in-up delay-1">
                <h1 class="form-title">Get Started</h1>
                <p class="form-subtitle">Create your account to unlock the full potential of your connected devices.</p>
                
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 input-group-gap">
                            <label class="form-label text-muted small fw-semibold">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="John Doe" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 input-group-gap">
                            <label class="form-label text-muted small fw-semibold">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                    
                    <div class="input-group-gap">
                        <label class="form-label text-muted small fw-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 input-group-gap">
                            <label class="form-label text-muted small fw-semibold">Password</label>
                            <div style="position:relative;">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                            </div>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 input-group-gap">
                            <label class="form-label text-muted small fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <div class="input-group-gap">
                        <label class="form-label text-muted small fw-semibold">Primary Interest</label>
                        <select class="form-select" name="product" required>
                            <option value="" selected disabled>Select a product category...</option>
                            <option value="thermostat">Smart Thermostats</option>
                            <option value="lighting">Smart Lighting Systems</option>
                            <option value="security">Home Security Cameras</option>
                            <option value="appliance">Smart Home Appliances</option>
                        </select>
                    </div>
                    
                    <div class="mb-4 form-check d-flex align-items-start gap-2 pt-2">
                        <input type="checkbox" class="form-check-input mt-1" id="agree" required style="cursor:pointer; border-color: #cbd5e1;">
                        <label class="form-check-label terms-text" for="agree" style="cursor:pointer;">
                            I agree to SmartHome's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-submit">Create Account</button>
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
                
                <h3 class="fade-in-up delay-2" style="font-weight: 700; color: white; margin-bottom: 1.5rem; font-size: 1.5rem;">Why choose SmartHome?</h3>
                
                <div class="features-grid fade-in-up delay-3">
                    <div class="feature-card">
                        <div class="feature-icon icon-security"><i class="bi bi-shield-check"></i></div>
                        <div class="feature-text">
                            <h4>Bank-grade Security</h4>
                            <p>End-to-end encryption ensures your home network and data remain entirely private.</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon icon-automation"><i class="bi bi-lightning-charge"></i></div>
                        <div class="feature-text">
                            <h4>Real-time Control</h4>
                            <p>Experience zero-latency automation for your lights, locks, and thermostats.</p>
                        </div>
                    </div>
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
    </script>
</body>
</html>
