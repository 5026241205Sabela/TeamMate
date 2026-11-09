{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Find Your Perfect Teammates') | TeamMate</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-slate-50 font-sans text-slate-800 antialiased">
        <x-navbar />

        <main class="flex-1">
            @yield('content')
        </main>

        <x-footer />
    </body>
</html> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TeamMate')</title>
    <style>
        :root {
            --navy: #0d1729;
            --navy-border: #202b3e;
            --blue: #2160f5;
            --page: #f5f7fb;
            --text: #172033;
            --muted: #8b98ad;
            --line: #e6ebf2;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: var(--page);
        }

        .app-shell { min-height: 100vh; }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 10;
            display: flex;
            flex-direction: column;
            width: 256px;
            color: #fff;
            background: var(--navy);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 85px;
            padding: 0 24px;
            border-bottom: 1px solid var(--navy-border);
            font-size: 20px;
            font-weight: 700;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #3280ff;
            font-size: 18px;
        }

        .sidebar-nav { padding: 19px 12px; }

        .create-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin: 0 4px 15px;
            padding: 12px;
            border-radius: 13px;
            color: white;
            background: var(--blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            margin: 5px 0;
            padding: 12px 13px;
            border-radius: 12px;
            color: #9aa8bd;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-link.active {
            color: white;
            background: var(--blue);
        }

        .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 17px;
        }

        .nav-spacer { flex: 1; }

        .profile-block {
            padding: 22px 16px 25px;
            border-top: 1px solid var(--navy-border);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .avatar {
            display: grid;
            place-items: center;
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            color: white;
            background: #079b72;
            font-size: 12px;
            font-weight: 700;
        }

        .profile-name { font-size: 13px; line-height: 1.5; }
        .profile-meta { color: #91a0b5; font-size: 12px; }

        .logout-link {
            color: #9aa8bd;
            text-decoration: none;
            font-size: 14px;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 256px;
        }

        .page-header {
            padding: 25px 32px 23px;
            background: #fff;
        }

        .page-header h1 {
            margin: 0 0 7px;
            font-size: 25px;
            line-height: 1.25;
        }

        .page-header p {
            margin: 0;
            color: #71819b;
            font-size: 14px;
        }

        .page-body {
            width: 100%;
            max-width: 1050px;
            margin: 0 auto;
            padding: 32px;
        }

        .form-card {
            margin-bottom: 24px;
            padding: 25px 24px 27px;
            border-radius: 17px;
            background: #fff;
        }

        .field-label {
            display: block;
            margin-bottom: 13px;
            font-size: 14px;
            font-weight: 600;
        }

        .required { color: #ef4444; }

        .form-control {
            display: block;
            width: 100%;
            min-height: 47px;
            padding: 12px 15px;
            border: 1px solid var(--line);
            border-radius: 12px;
            outline: none;
            color: #263247;
            background: #fff;
            font: inherit;
            font-size: 14px;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus {
            border-color: #8eafff;
            box-shadow: 0 0 0 3px rgba(33, 96, 245, .09);
        }

        .form-control::placeholder { color: #9aa3b1; }
        select.form-control { color: #263247; }
        textarea.form-control { min-height: 105px; resize: vertical; line-height: 1.5; }

        .field-group { margin-bottom: 19px; }
        .field-group:last-child { margin-bottom: 0; }

        .upload-box {
            display: flex;
            min-height: 160px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 10px;
            padding: 18px;
            border: 2px dashed #dce5f2;
            border-radius: 14px;
            text-align: center;
            cursor: pointer;
            color: #415575;
            background: #fff;
        }

        .upload-icon {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 13px;
            color: #8da2c0;
            background: #f0f4f9;
            font-size: 23px;
        }

        .upload-main { font-size: 14px; }
        .upload-help { color: #91a2bd; font-size: 12px; }
        .file-input { display: none; }

        .poster-preview {
            display: none;
            max-width: 100%;
            max-height: 220px;
            margin-top: 14px;
            border-radius: 10px;
            object-fit: contain;
        }

        .error-text {
            margin-top: 7px;
            color: #dc2626;
            font-size: 12px;
        }

        .alert {
            margin-bottom: 18px;
            padding: 13px 15px;
            border-radius: 10px;
            font-size: 14px;
        }

        .alert-success { color: #166534; background: #dcfce7; }
        .alert-error { color: #991b1b; background: #fee2e2; }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-bottom: 20px;
        }

        .btn {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            padding: 0 23px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: white;
            color: #40516c;
            text-decoration: none;
            font: inherit;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-primary {
            border-color: var(--blue);
            color: white;
            background: var(--blue);
        }

        @media (max-width: 760px) {
            .sidebar { position: static; width: 100%; min-height: auto; }
            .brand { min-height: 65px; }
            .sidebar-nav { padding: 10px 12px; }
            .sidebar-nav .nav-link { display: inline-flex; }
            .sidebar-nav .create-link { max-width: 220px; }
            .nav-spacer, .profile-block { display: none; }
            .main-content { margin-left: 0; }
            .page-header { padding: 22px 20px; }
            .page-header h1 { font-size: 22px; }
            .page-body { padding: 20px 14px; }
            .form-card { padding: 21px 17px; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <span class="brand-mark">♧</span>
            <span>TeamMate</span>
        </div>

        <nav class="sidebar-nav">
            <a class="create-link" href="{{ route('posts.create') }}">
                <span>＋</span> Create Post
            </a>
            <a class="nav-link" href="{{ route('home') }}"><span class="nav-icon">⌂</span> Home</a>
            {{-- These navigation destinations can be connected when their pages are ready. --}}
            <a class="nav-link" href="#"><span class="nav-icon">▤</span> My Posts</a>
            <a class="nav-link" href="#"><span class="nav-icon">♧</span> Requests</a>
            <a class="nav-link" href="#"><span class="nav-icon">♧</span> Notifications</a>
            <a class="nav-link" href="#"><span class="nav-icon">♙</span> Profile</a>
        </nav>

        <div class="nav-spacer"></div>

        <div class="profile-block">
            <div class="profile">
                <div class="avatar">SD</div>
                <div>
                    <div class="profile-name">{{ $testingAccount?->full_name ?? 'Testing Account' }}</div>
                    <div class="profile-meta">{{ $testingAccount?->nim ?? 'Temporary account' }}</div>
                </div>
            </div>
            <a class="logout-link" href="{{ route('home') }}">⇥ &nbsp; Logout</a>
        </div>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
