<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IoT Dashboard') — Smart Home</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Theme Initialization Script -->
    <script>
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>

    <style>
        :root {
            --primary: #8b5cf6; /* Neon Purple */
            --primary-dark: #6d28d9;
            --secondary: #3b82f6; /* Neon Blue */
            --accent: #f472b6; /* Pink accent */
            --bg-dark: #0f172a; /* Deep Navy */
            --bg-darker: #020617; /* Near Black */
            --sidebar-width: 280px;
            
            --glass-bg: rgba(30, 41, 59, 0.4); 
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-blur: blur(20px);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        [data-theme="light"] {
            --bg-darker: #f8fafc;
            --bg-dark: #f1f5f9;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(0, 0, 0, 0.08);
            --text-main: #0f172a;
            --text-muted: #475569;
        }

        /* Smooth Theme Transition */
        body, .sidebar, .topbar, .glass-panel, .widget-card, .admin-stat-card, .form-control, .card, .main-content, .sidebar-nav .nav-link {
            transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, box-shadow 0.4s ease, transform 0.3s ease;
        }
        
        [data-theme="light"] body::before, [data-theme="light"] body::after {
            opacity: 0.6;
        }

        * { font-family: 'Poppins', sans-serif; }
        
        .font-dot {
            font-family: 'Poppins', sans-serif !important;
            letter-spacing: 1px !important;
            font-weight: 700;
        }

        body { 
            background: var(--bg-darker);
            color: var(--text-main);
            min-height: 100vh; 
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }

        /* Animated glowing background blobs */
        body::before {
            content: '';
            position: fixed;
            top: -20%; left: -10%;
            width: 70vw; height: 70vw;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, rgba(0,0,0,0) 60%);
            z-index: -2;
            animation: floatBlob 20s ease-in-out infinite alternate;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -20%; right: -10%;
            width: 60vw; height: 60vw;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(0,0,0,0) 60%);
            z-index: -2;
            animation: floatBlob 25s ease-in-out infinite alternate-reverse;
        }
        
        @keyframes floatBlob {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(5%, 5%) scale(1.1); }
        }

        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none; }
        .particle {
            position: absolute; width: 3px; height: 3px; background: rgba(139, 92, 246, 0.4); border-radius: 50%;
            animation: floatParticle linear infinite;
        }
        @keyframes floatParticle {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1.5); opacity: 0; }
        }

        /* ── Glass Utilities ── */
        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            border-radius: 24px;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-right: 1px solid var(--glass-border);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 30px rgba(0,0,0,0.2);
        }
        .sidebar-brand {
            padding: 2rem 1.5rem;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .sidebar-brand .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
            color: #fff;
        }
        .brand-text-main { font-weight: 700; font-size: 1.4rem; letter-spacing: 0.5px; }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .nav-section-label {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-top: 1rem;
        }
        .sidebar-nav .nav-link {
            color: var(--text-muted);
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            position: relative;
            border-radius: 16px;
            margin: 0.3rem 1rem;
        }
        .sidebar-nav .nav-link:hover {
            color: var(--text-main);
            background: var(--glass-border);
        }
        .sidebar-nav .nav-link.active {
            color: var(--text-main);
            background: linear-gradient(90deg, rgba(139, 92, 246, 0.2), rgba(59, 130, 246, 0.05));
            border-radius: 16px;
            font-weight: 600;
            border-left: 4px solid var(--primary);
            box-shadow: inset 0 0 20px rgba(139, 92, 246, 0.1);
        }
        .sidebar-nav .nav-link.active i { color: var(--primary); text-shadow: 0 0 10px var(--primary); }
        .sidebar-nav .nav-link i { font-size: 1.2rem; width: 24px; text-align: center; transition: all 0.3s; color: inherit; }

        /* ── Main content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        /* ── Topbar ── */
        .topbar {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 900;
        }
        .topbar .page-title { font-weight: 700; font-size: 1.5rem; color: var(--text-main); margin: 0; letter-spacing: 0.5px; }
        
        .live-clock-badge {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            color: #c4b5fd;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.15);
        }

        /* ── Cards ── */
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid var(--glass-border);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .stat-card::after {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, transparent 100%);
            opacity: 0; transition: opacity 0.4s; pointer-events: none;
        }
        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(139, 92, 246, 0.15);
            border-color: rgba(139, 92, 246, 0.4);
        }
        .stat-card:hover::after { opacity: 1; }
        .stat-card .icon-box {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .stat-card:hover .icon-box { animation: float 2s ease-in-out infinite; box-shadow: 0 0 15px var(--primary); color: var(--primary); }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        .stat-card .stat-value { font-size: 2.5rem; font-weight: 700; color: #ffffff; line-height: 1.2; position: relative; z-index: 2; }
        .stat-card .stat-label { font-size: 0.9rem; color: var(--text-muted); font-weight: 500; position: relative; z-index: 2; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ── Device Cards ── */
        .device-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .device-card:hover { 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 20px rgba(59, 130, 246, 0.15); 
            transform: translateY(-5px); 
            border-color: rgba(59, 130, 246, 0.4);
        }
        .device-card .card-header-custom {
            padding: 1.25rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: flex-start; justify-content: space-between;
        }
        .device-type-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .type-thermostat { color: #f97316; }
        .type-light       { color: #eab308; }
        .type-alarm       { color: #ef4444; }

        /* ── Toggle Switch (Futuristic) ── */
        .toggle-switch { position: relative; width: 50px; height: 26px; display: inline-block; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.1);
            border-radius: 30px;
            transition: .3s;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .toggle-slider::before {
            content: ''; position: absolute; width: 20px; height: 20px; left: 3px; bottom: 2px;
            background: #ffffff; border-radius: 50%; transition: .3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        input:checked + .toggle-slider { 
            background: var(--primary); 
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
        }
        input:checked + .toggle-slider::before { 
            transform: translateX(23px); 
            background: #fff;
        }

        /* ── Status Badge ── */
        .status-on  { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); box-shadow: 0 0 10px rgba(16, 185, 129, 0.2); font-size: .75rem; padding: .35rem .75rem; border-radius: 20px; font-weight: 600; letter-spacing: 0.5px; }
        .status-off { background: rgba(255, 255, 255, 0.05); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.1); font-size: .75rem; padding: .35rem .75rem; border-radius: 20px; font-weight: 600; letter-spacing: 0.5px; }

        /* ── Flash messages ── */
        .alert { border-radius: 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); color: var(--text-main); background: rgba(255,255,255,0.05); }
        .alert-success { border-color: rgba(16, 185, 129, 0.5); box-shadow: 0 0 15px rgba(16, 185, 129, 0.2); }
        .alert-danger { border-color: rgba(239, 68, 68, 0.5); box-shadow: 0 0 15px rgba(239, 68, 68, 0.2); }
        .alert-info { border-color: rgba(59, 130, 246, 0.5); box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }

        /* ── Tables & Forms ── */
        .table-card { background: var(--glass-bg); backdrop-filter: var(--glass-blur); border-radius: 24px; border: 1px solid var(--glass-border); box-shadow: 0 15px 35px rgba(0,0,0,0.2); overflow: hidden; padding: 1rem; }
        .table-card .table { margin: 0; color: var(--text-main); }
        .table-card .table th { background: transparent; font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--text-muted); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .table-card .table td { vertical-align: middle; font-size: .95rem; border-color: rgba(255,255,255,0.05); background: transparent; color: var(--text-main); }
        
        /* Form Controls */
        .form-control, .form-select {
            background: rgba(128,128,128,0.05);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(128,128,128,0.1);
            border-color: var(--primary);
            color: var(--text-main);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
            outline: none;
        }
        .form-control::placeholder, .form-select::placeholder { color: var(--text-muted); font-weight: 500; }
        
        select option {
            background-color: var(--glass-bg);
            color: var(--text-main);
        }
        
        /* ── Modals ── */
        .modal-content {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            color: #fff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 30px rgba(139, 92, 246, 0.1);
        }
        .modal-header { border-bottom: 1px solid rgba(255,255,255,0.05); border-top-left-radius: 24px; border-top-right-radius: 24px; }
        .modal-footer { border-top: 1px solid rgba(255,255,255,0.05); border-bottom-left-radius: 24px; border-bottom-right-radius: 24px; }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        /* ── Buttons ── */
        .btn { border-radius: 12px; padding: 0.6rem 1.5rem; font-weight: 500; letter-spacing: 0.5px; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3); color: #fff; }
        .btn-primary:hover { transform: translateY(-3px); background: linear-gradient(135deg, #9f7aea, var(--primary)); color: #fff; box-shadow: 0 8px 25px rgba(139, 92, 246, 0.5); }
        .btn-success { background: linear-gradient(135deg, #10b981, #059669); border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); color: #fff;}
        .btn-success:hover { transform: translateY(-3px); background: linear-gradient(135deg, #34d399, #10b981); color: #fff; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);}
        .btn-danger { background: linear-gradient(135deg, #ef4444, #dc2626); border: none; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); color: #fff; }
        .btn-danger:hover { transform: translateY(-3px); background: linear-gradient(135deg, #f87171, #ef4444); color: #fff; box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5); }
        .btn-blue { background: linear-gradient(135deg, var(--secondary), #2563eb); border: none; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); color: #fff; }
        .btn-blue:hover { transform: translateY(-3px); background: linear-gradient(135deg, #60a5fa, var(--secondary)); color: #fff; box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5); }
        .btn-light { background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px); transition: all 0.3s; }
        .btn-light:hover { background: rgba(255,255,255,0.15); color: #fff; border-color: rgba(255,255,255,0.3); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .btn-outline-warning { border-color: rgba(245, 158, 11, 0.5); color: #fbbf24; }
        .btn-outline-warning:hover { background: rgba(245, 158, 11, 0.2); color: #fcd34d; border-color: rgba(245, 158, 11, 0.8); }
        .btn-outline-light { border-color: rgba(255,255,255,0.2); color: #fff; }
        .btn-outline-light:hover { background: rgba(255,255,255,0.1); color: #fff; }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 20px 0 50px rgba(0,0,0,0.5); }
            .main-content { margin-left: 0; }
        }

        /* ── Page content padding ── */
        .page-content { padding: 2rem; flex: 1; }

        /* ── Spinner ── */
        .btn-loading .spinner-border { display: inline-block !important; }

        /* ── Log badges ── */
        .log-info    { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .log-warning { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .log-error   { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .log-control { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .log-badge {
            font-size: .72rem; font-weight: 600;
            padding: .35rem .75rem; border-radius: 8px;
            text-transform: uppercase; letter-spacing: .05em;
        }
        
        /* ── Custom Scrollbar ── */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.25); }

        /* ── Bootstrap Overrides ── */
        .border-top { border-top-color: rgba(255,255,255,0.05) !important; }
        .border-bottom { border-bottom-color: rgba(255,255,255,0.05) !important; }
        .text-muted { color: var(--text-muted) !important; }

        /* ── Chatbot Widget ── */
        .chatbot-widget {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            pointer-events: none;
        }
        .chatbot-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.5);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255,255,255,0.1);
            outline: none;
            position: relative;
            pointer-events: auto;
        }
        .chatbot-btn::after {
            content: ''; position: absolute; top: -5px; left: -5px; right: -5px; bottom: -5px;
            border-radius: 50%; border: 1px solid rgba(139, 92, 246, 0.5);
            animation: pulse-ring 2s infinite;
        }
        @keyframes pulse-ring { 0% { transform: scale(0.9); opacity: 1; } 100% { transform: scale(1.3); opacity: 0; } }
        .chatbot-btn:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 10px 40px rgba(139, 92, 246, 0.6);
        }
        .chatbot-window {
            width: 380px;
            height: 600px;
            max-height: calc(100vh - 120px);
            max-width: calc(100vw - 40px);
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 30px rgba(139, 92, 246, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            margin-bottom: 1.5rem;
            transform-origin: bottom right;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: scale(0) translateY(20px);
            opacity: 0;
            pointer-events: none;
        }
        .chatbot-window.open {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: all;
        }
        .chatbot-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(90deg, rgba(139, 92, 246, 0.15), rgba(59, 130, 246, 0.15));
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .chatbot-header-title {
            font-weight: 600;
            font-size: 1.15rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .chatbot-avatar {
            width: 36px; height: 36px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        .chatbot-close {
            background: rgba(255,255,255,0.05);
            width: 32px; height: 32px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .chatbot-close:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.3);
            transform: rotate(90deg);
        }
        .chatbot-messages {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            scroll-behavior: smooth;
        }
        
        .message-row { display: flex; align-items: flex-end; gap: 0.75rem; }
        .message-row.user { flex-direction: row-reverse; }
        
        .bot-icon {
            width: 28px; height: 28px; border-radius: 50%; background: rgba(139, 92, 246, 0.2);
            display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: #c4b5fd;
            flex-shrink: 0; border: 1px solid rgba(139, 92, 246, 0.3);
        }
        
        .chatbot-message {
            max-width: 85%;
            padding: 0.85rem 1.1rem;
            border-radius: 16px;
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .chatbot-message.bot {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-bottom-left-radius: 4px;
            color: #f8fafc;
        }
        .chatbot-message.user {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-bottom-right-radius: 4px;
            color: #fff;
            border: 1px solid rgba(139, 92, 246, 0.5);
        }
        .chatbot-input-area {
            padding: 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
        }
        .input-wrapper {
            display: flex;
            gap: 0.75rem;
            background: rgba(0,0,0,0.2);
            padding: 0.5rem;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s;
        }
        .input-wrapper:focus-within {
            border-color: rgba(139, 92, 246, 0.5);
            background: rgba(0,0,0,0.3);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        }
        .chatbot-input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 0.5rem 0.75rem;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
        }
        .chatbot-input::placeholder { color: #64748b; }
        .chatbot-send {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        .chatbot-send:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
        }
        .chatbot-send:active { transform: scale(0.95); }
        .typing-indicator {
            display: flex;
            gap: 5px;
            padding: 0.5rem;
            align-items: center;
        }
        .typing-dot {
            width: 6px;
            height: 6px;
            background: #94a3b8;
            border-radius: 50%;
            animation: typingDot 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typingDot {
            0%, 80%, 100% { transform: scale(0); opacity: 0.5; }
            40% { transform: scale(1.2); opacity: 1; background: var(--primary); }
        }
    
        /* ── Notification Panel ── */
        .notification-panel {
            position: fixed; top: 0; right: -400px; width: 380px; max-width: 100vw; height: 100vh;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(25px); border-left: 1px solid var(--glass-border);
            z-index: 1050; transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex; flex-direction: column; box-shadow: -10px 0 40px rgba(0,0,0,0.5);
        }
        .notification-panel.open { right: 0; }
        .noti-header { padding: 1.5rem; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; }
        .noti-body { flex: 1; overflow-y: auto; padding: 1rem; }
        .noti-item { padding: 1rem; border-radius: 16px; background: rgba(255,255,255,0.03); margin-bottom: 0.5rem; transition: all 0.3s; border: 1px solid transparent; }
        .noti-item:hover { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); }
        .noti-item.unread { background: rgba(139, 92, 246, 0.1); border-color: rgba(139, 92, 246, 0.2); }
        .noti-overlay { position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); z-index: 1040; display: none; backdrop-filter: blur(5px); }
        .noti-overlay.open { display: block; }
        .bell-btn { position: relative; background: transparent; border: none; color: var(--text-main); font-size: 1.2rem; cursor: pointer; transition: transform 0.3s; }
        .bell-btn:hover { transform: scale(1.1); color: var(--primary); }
        .bell-badge { position: absolute; top: -5px; right: -5px; background: #ef4444; font-size: 0.65rem; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; border: 2px solid var(--bg-dark); }

        /* ── Mobile Bottom Nav ── */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; width: 100%;
            background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(15px);
            border-top: 1px solid var(--glass-border); display: none; z-index: 900;
            padding: 0.5rem 1rem; padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));
        }
        @media (max-width: 992px) {
            .bottom-nav { display: flex; justify-content: space-around; align-items: center; }
            .sidebar { display: none; } /* Use bottom nav instead of sidebar for main links */
            .main-content { margin-left: 0; padding-bottom: 70px; }
            .topbar { padding: 1rem; }
            .sidebar.open { display: flex; width: 100%; } /* full screen sidebar if opened via menu */
        }
        .bottom-nav-item { color: var(--text-muted); text-align: center; font-size: 0.75rem; text-decoration: none; transition: color 0.3s; flex: 1; padding: 0.5rem 0; }
        .bottom-nav-item i { font-size: 1.4rem; display: block; margin-bottom: 2px; }
        .bottom-nav-item.active { color: var(--primary); }
        .bottom-nav-item.active i { text-shadow: 0 0 10px var(--primary); }

        .hero-footer {
            position: relative;
            min-height: auto;
            background: var(--bg-dark);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 1rem;
            color: var(--text-main);
            margin-top: auto;
            overflow: hidden;
            border-top: 1px solid var(--glass-border);
        }
        
        .footer-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom, var(--bg-dark), var(--bg-darker));
            z-index: 1;
        }

        .footer-content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            flex: 1;
        }
        
        .footer-nav {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .footer-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            transition: opacity 0.3s;
        }
        .footer-nav a:hover { opacity: 0.8; color: var(--primary); }
        
        .footer-socials {
            display: flex;
            gap: 1.5rem;
            margin-bottom: auto;
            justify-content: center;
        }
        .footer-socials a {
            color: #fff;
            font-size: 1.2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            transition: transform 0.3s;
        }
        .footer-socials a:hover { transform: translateY(-3px); color: var(--primary); }
        
        .footer-brand-text {
            font-weight: 800;
            font-size: clamp(3rem, 8vw, 7rem);
            color: rgba(255,255,255,0.05);
            line-height: 1;
            margin: auto 0;
            text-align: center;
            letter-spacing: -2px;
            text-shadow: none;
        }
        
        .footer-disclaimer {
            text-align: center;
            max-width: 800px;
            margin-top: auto;
        }
        .footer-disclaimer p {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        .footer-bottom-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
        }
        .footer-bottom-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .copyright {
            font-size: 0.8rem !important;
            color: rgba(255,255,255,0.4) !important;
        }

    </style>
    @stack('styles')
</head>
<body>

<!-- ===================== SIDEBAR ===================== -->
<aside class="sidebar" id="sidebar">
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="sidebar-brand">
        <div class="brand-icon" style="background: transparent; border: none; box-shadow: none; width: 64px; height: 64px; margin-right: 5px;">
            <img src="https://img.icons8.com/3d-fluency/94/combo-chart.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));">
        </div>
        <div>
            <div class="brand-text-main">SMART HOME</div>
            <div style="font-size:0.7rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:1.5px;margin-top:2px;">SMART HOME CONTROL</div>
        </div>
    </a>

    <nav class="sidebar-nav">
        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="nav-section-label">Admin Controls</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Overview
            </a>
            <a href="{{ route('admin.devices') }}" class="nav-link {{ request()->routeIs('admin.devices') ? 'active' : '' }}">
                <i class="bi bi-cpu"></i> Devices
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
            <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> System Logs
            </a>
            <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="bi bi-tools"></i> Service Requests
            </a>
        @else
            <!-- USER MENU -->
            <div class="nav-section-label">My Home</div>
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-heart"></i> Dashboard
            </a>
            <a href="{{ route('user.rooms.index') }}" class="nav-link {{ request()->routeIs('user.rooms.*') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Rooms
            </a>
            <a href="{{ route('user.devices.index') }}" class="nav-link {{ request()->routeIs('user.devices.*') ? 'active' : '' }}">
                <i class="bi bi-cpu"></i> My Devices
            </a>
            <div class="nav-section-label">Analytics & Account</div>
            <a href="{{ route('user.energy.index') }}" class="nav-link {{ request()->routeIs('user.energy.*') ? 'active' : '' }}">
                <i class="bi bi-lightning-charge"></i> Energy
            </a>
            <a href="{{ route('user.billing.index') }}" class="nav-link {{ request()->routeIs('user.billing.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i> Billing
            </a>
            
            <div class="nav-section-label">Support</div>
            <a href="{{ route('user.services.index') }}" class="nav-link {{ request()->routeIs('user.services.*') ? 'active' : '' }}">
                <i class="bi bi-tools"></i> Services
            </a>
        @endif
    </nav>

    <!-- User info at bottom -->
    <div style="padding:1rem 1.25rem;border-top:1px solid var(--glass-border)">
        <div style="display:flex;align-items:center;gap:.75rem">
            <!-- Profile Photo & Upload -->
            <div style="position:relative; width:40px; height:40px;">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" style="width:100%; height:100%; border-radius:50%; object-fit:cover; border: 2px solid var(--primary); cursor:pointer;" onclick="document.getElementById('profilePhotoInput').click()">
                <form id="profilePhotoForm" action="{{ route('user.profile.photo') }}" method="POST" enctype="multipart/form-data" style="display:none;">
                    @csrf
                    <input type="file" id="profilePhotoInput" name="photo" accept="image/*" onchange="document.getElementById('profilePhotoForm').submit()">
                </form>
            </div>
            
            <div style="flex:1;overflow:hidden">
                <div style="color:var(--text-main);font-size:.85rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ auth()->user()->name }}</div>
                <div style="color:var(--primary);font-size:.72rem">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--text-muted);cursor:pointer;padding:.25rem;transition:color 0.3s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--text-muted)'" title="Logout">
                    <i class="bi bi-box-arrow-right" style="font-size:1.1rem"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ===================== MAIN ===================== -->
<div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:1rem">
            <button class="btn btn-sm btn-light d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="bi bi-list" style="font-size:1.2rem"></i>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div style="display:flex;align-items:center;gap:1rem">
            <span class="live-clock-badge">
                <i class="bi bi-clock"></i>
                <span id="live-time"></span>
            </span>
            @if(auth()->user()->isAdmin())
                @php $pendingCount = \App\Models\Device::where('approval_status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <a href="{{ route('admin.devices') }}?approval=pending" class="btn btn-sm btn-warning" style="font-size:.8rem">
                        <i class="bi bi-shield-exclamation me-1"></i>{{ $pendingCount }} Pending
                    </a>
                @endif
            @endif
            <button class="bell-btn" id="theme-toggle" title="Toggle Theme" style="background: none; border: none; color: var(--text-main); font-size: 1.2rem; cursor: pointer;">
                <i class="bi bi-moon-fill" id="theme-icon"></i>
            </button>

            <button class="bell-btn" onclick="toggleNotifications()" style="background: none; border: none; color: var(--text-main); position: relative;">
                <i class="bi bi-bell"></i>
                <span class="bell-badge" id="noti-badge">3</span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>

    <!-- Global Footer -->
    <footer class="hero-footer mt-5">
        <div class="footer-overlay"></div>
        <div class="footer-content" style="padding: 1rem 0;">
            <div class="row w-100 align-items-center" style="max-width: 1200px; margin: 0 auto; z-index: 10; position: relative;">
                
                <!-- Left Side: Brand & Disclaimer -->
                <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="https://img.icons8.com/3d-fluency/94/combo-chart.png" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));">
                        <h1 class="footer-brand-text" style="font-size: clamp(2.5rem, 6vw, 4.5rem); text-align: left; margin: 0; line-height: 1; color: var(--text-main); text-shadow: 0 0 20px rgba(139, 92, 246, 0.5);">Smart Home</h1>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-muted); max-width: 400px; margin-bottom: 1rem;">
                        Features and availability may vary by location. Devices must be compatible and meet network requirements. All connections are conducted via securely encrypted, regulated facilities.
                    </p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start mb-2" style="font-size: 0.85rem;">
                        <a href="#" class="text-decoration-none" style="color: var(--text-main);">Privacy Policy</a>
                        <a href="#" class="text-decoration-none" style="color: var(--text-main);">Terms & Conditions</a>
                    </div>
                    <p class="copyright m-0" style="font-size: 0.75rem; color: var(--text-muted);">&copy; {{ date('Y') }} IoT Dashboard. All rights reserved.</p>
                </div>
                
                <!-- Right Side: Contact Form -->
                <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end">
                    <div style="background: var(--glass-bg); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--glass-border); backdrop-filter: blur(20px); text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-width: 400px; width: 100%;">
                        <h5 style="color: var(--text-main); font-family: 'Poppins', sans-serif; margin-bottom: 1rem; font-size: 1.1rem; letter-spacing: 0.5px;">Connect with us</h5>
                        <form action="{{ route('user.contact.submit') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <input type="email" name="email" class="form-control form-control-sm" placeholder="Your Gmail ID" required style="background: rgba(128,128,128,0.1); border: 1px solid var(--glass-border); color: var(--text-main); border-radius: 8px; padding: 0.5rem 0.75rem;">
                            </div>
                            <div class="mb-3">
                                <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="How can we help?" required style="background: rgba(128,128,128,0.1); border: 1px solid var(--glass-border); color: var(--text-main); border-radius: 8px; padding: 0.5rem 0.75rem; resize: none;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill py-2" style="font-weight: 600;">Send to Admin</button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </footer>
</div>

<!-- ===================== CHATBOT WIDGET ===================== -->
<div class="chatbot-widget">
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <div class="chatbot-header-title">
                <div class="chatbot-avatar"><i class="bi bi-robot"></i></div>
                <div>
                    <div>Smart Assistant</div>
                    <div style="font-size: 0.75rem; color: #4ade80; font-weight: 400; font-family: 'Outfit', sans-serif;"><span style="display:inline-block;width:8px;height:8px;background:#4ade80;border-radius:50%;margin-right:4px;box-shadow:0 0 10px #4ade80;"></span>Online</div>
                </div>
            </div>
            <button class="chatbot-close" onclick="toggleChatbot()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message-row bot">
                <div class="bot-icon"><i class="bi bi-robot"></i></div>
                <div class="chatbot-message bot">
                    Hi there! 👋 I am your Smart Home Assistant. How can I help you today?
                </div>
            </div>
        </div>
        <div class="chatbot-input-area">
            <div class="input-wrapper">
                <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type a message..." onkeypress="handleChatInput(event)" autocomplete="off">
                <button class="chatbot-send" onclick="sendChatMessage()">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>
    <button class="chatbot-btn" onclick="toggleChatbot()">
        <i class="bi bi-chat-dots-fill"></i>
    </button>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
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
if(themeIcon) {
    updateThemeIcon(currentTheme);
}

if(themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
        let theme = document.documentElement.getAttribute('data-theme');
        let newTheme = theme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
}

// Live clock in topbar
function updateTime() {
    const now = new Date();
    const timeElem = document.getElementById('live-time');
    if(timeElem) {
        timeElem.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
}
updateTime();
setInterval(updateTime, 1000);

// Chatbot functionality
function toggleChatbot() {
    document.getElementById('chatbotWindow').classList.toggle('open');
}

function handleChatInput(event) {
    if (event.key === 'Enter') {
        sendChatMessage();
    }
}

function sendChatMessage() {
    const input = document.getElementById('chatbotInput');
    const message = input.value.trim();
    if (!message) return;

    const messagesContainer = document.getElementById('chatbotMessages');
    
    // Add user message
    const userRow = document.createElement('div');
    userRow.className = 'message-row user';
    userRow.innerHTML = `<div class="chatbot-message user">${message}</div>`;
    messagesContainer.appendChild(userRow);
    
    // Clear input and scroll to bottom
    input.value = '';
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Show typing indicator
    const typingId = 'typing-' + Date.now();
    const typingRow = document.createElement('div');
    typingRow.className = 'message-row bot';
    typingRow.id = typingId;
    typingRow.innerHTML = `<div class="bot-icon"><i class="bi bi-robot"></i></div><div class="chatbot-message bot"><div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div></div>`;
    messagesContainer.appendChild(typingRow);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Simulate bot response
    setTimeout(() => {
        const typingElem = document.getElementById(typingId);
        if(typingElem) typingElem.remove();
        
        const botRow = document.createElement('div');
        botRow.className = 'message-row bot';
        
        // Basic responses
        let response = "I'm still learning! For now, I can help you navigate the dashboard or check your device statuses.";
        const lowerMsg = message.toLowerCase();
        
        if (lowerMsg.includes('hello') || lowerMsg.includes('hi')) {
            response = "Hello! What can I assist you with?";
        } else if (lowerMsg.includes('light') || lowerMsg.includes('turn on')) {
            response = "You can control your lights from the main dashboard. Let me know if you need help finding them!";
        } else if (lowerMsg.includes('temperature') || lowerMsg.includes('thermostat')) {
            response = "You can adjust your thermostat settings on the dashboard. Is there a specific temperature you prefer?";
        } else if (lowerMsg.includes('camera') || lowerMsg.includes('security')) {
            response = "Your security cameras are live on the dashboard. You can view all of them in the 'My Devices' section.";
        }
        
        botRow.innerHTML = `<div class="bot-icon"><i class="bi bi-robot"></i></div><div class="chatbot-message bot">${response}</div>`;
        messagesContainer.appendChild(botRow);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }, 1500);
}
</script>

@stack('scripts')

<!-- Particles -->
<div class="particles" id="particles"></div>

<!-- Notification Panel -->
<div class="noti-overlay" id="notiOverlay" onclick="toggleNotifications()"></div>
<div class="notification-panel" id="notiPanel">
    <div class="noti-header">
        <h5 class="mb-0" style="font-family:'Space Grotesk', sans-serif; font-weight:600;">Notifications</h5>
        <button class="btn-close" onclick="toggleNotifications()"></button>
    </div>
    <div class="noti-body" id="notiBody">
        <!-- Dummy notifications for now, will connect to API later -->
        <div class="noti-item unread">
            <div style="font-size:0.85rem; font-weight:600; color:#fff;">Device Approved</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">Your 'Living Room Thermostat' has been approved.</div>
        </div>
        <div class="noti-item unread">
            <div style="font-size:0.85rem; font-weight:600; color:#fff;">High Energy Usage</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">AC unit in Bedroom is consuming 20% more power than usual.</div>
        </div>
        <div class="noti-item">
            <div style="font-size:0.85rem; font-weight:600; color:#fff;">System Update</div>
            <div style="font-size:0.75rem; color:var(--text-muted);">Dashboard updated to v2.0 with new 3D Glassmorphism UI.</div>
        </div>
    </div>
    <div style="padding:1rem; border-top:1px solid var(--glass-border);">
        <button class="btn btn-sm w-100 btn-light" onclick="markAllAsRead()">Mark all as read</button>
    </div>
</div>

<!-- Mobile Bottom Nav -->
@if(!auth()->user()->isAdmin())
<div class="bottom-nav d-lg-none">
    <a href="{{ route('user.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house"></i> Home
    </a>
    <a href="{{ route('user.rooms.index') }}" class="bottom-nav-item {{ request()->routeIs('user.rooms.*') ? 'active' : '' }}">
        <i class="bi bi-grid"></i> Rooms
    </a>
    <a href="{{ route('user.devices.index') }}" class="bottom-nav-item {{ request()->routeIs('user.devices.*') ? 'active' : '' }}">
        <i class="bi bi-cpu"></i> Devices
    </a>
    <a href="{{ route('user.energy.index') }}" class="bottom-nav-item {{ request()->routeIs('user.energy.*') ? 'active' : '' }}">
        <i class="bi bi-lightning"></i> Energy
    </a>
</div>
@endif

<script>
// Particles Generation
const particlesContainer = document.getElementById('particles');
for(let i=0; i<30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random() * 100 + 'vw';
    p.style.animationDuration = (Math.random() * 10 + 10) + 's';
    p.style.animationDelay = (Math.random() * 10) + 's';
    particlesContainer.appendChild(p);
}

// Notifications Toggle
function toggleNotifications() {
    document.getElementById('notiPanel').classList.toggle('open');
    document.getElementById('notiOverlay').classList.toggle('open');
}
function markAllAsRead() {
    document.getElementById('noti-badge').style.display = 'none';
    document.querySelectorAll('.noti-item.unread').forEach(el => {
        el.classList.remove('unread');
    });
}
</script>
</body>
</html>
