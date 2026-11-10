<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TeamMate')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --navy: #0b1528;
            --navy-border: #1e2d47;
            --blue: #2160f5;
            --page: #f6f8fc;
            --text: #172033;
            --muted: #8b98ad;
            --line: #e2e8f0;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-sans);
            color: var(--text);
            background: var(--page);
        }

        .app-shell { min-height: 100vh; }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 20;
            display: flex;
            flex-direction: column;
            width: 256px;
            color: #fff;
            background: var(--navy);
            border-right: 1px solid var(--navy-border);
        }

        .brand {
            display: flex;
            align-items: center;
            min-height: 80px;
            padding: 0 22px;
            border-bottom: 1px solid var(--navy-border);
            color: #fff;
            text-decoration: none;
        }

        .sidebar-nav { padding: 18px 12px; }

        .create-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin: 0 4px 16px;
            padding: 12px;
            border-radius: 12px;
            color: white;
            background: var(--blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: transform 180ms cubic-bezier(0.16, 1, 0.3, 1), background-color 180ms, box-shadow 180ms;
        }

        .create-link:hover {
            background: #1b52d4;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -8px rgba(33, 96, 245, 0.65);
        }

        .create-link:active {
            transform: scale(0.97);
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 4px 0;
            padding: 11px 13px;
            border-radius: 11px;
            color: #9aa8bd;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 180ms, color 180ms;
        }

        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .sidebar-nav .nav-link.active {
            color: white;
            background: var(--blue);
            font-weight: 600;
        }

        .nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-spacer { flex: 1; }

        .profile-block {
            padding: 20px 16px 24px;
            border-top: 1px solid var(--navy-border);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .avatar {
            display: grid;
            place-items: center;
            flex: 0 0 36px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: white;
            background: #0284c7;
            font-size: 12px;
            font-weight: 700;
        }

        .profile-name { font-size: 13px; line-height: 1.4; font-weight: 600; color: #fff; }
        .profile-meta { color: #91a0b5; font-size: 12px; margin-top: 2px; }

        .logout-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #9aa8bd;
            text-decoration: none;
            font: inherit;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: color 150ms;
        }

        .logout-link:hover { color: #fff; }

        .main-content {
            min-height: 100vh;
            margin-left: 256px;
        }

        .page-header {
            padding: 24px 32px 22px;
            background: #fff;
            border-bottom: 1px solid var(--line);
        }

        .page-header h1 {
            margin: 0 0 6px;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .page-header p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        .page-body {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 32px;
        }

        .form-card {
            margin-bottom: 24px;
            padding: 25px 24px 27px;
            border-radius: 17px;
            background: #fff;
            border: 1px solid var(--line);
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
@php
    $currentAccount = auth()->user() ?? ($testingAccount ?? null);
    $accountName = $currentAccount?->full_name ?? 'Student Member';
    $initials = collect(explode(' ', trim($accountName)))
        ->filter()
        ->take(2)
        ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('') ?: 'TM';
@endphp

<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('home') }}" aria-label="TeamMate Home">
            <x-logo theme="dark" />
        </a>

        <nav class="sidebar-nav" aria-label="Sidebar Navigation">
            <a class="create-link" href="{{ route('posts.create') }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                </svg>
                <span>Create Post</span>
            </a>

            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1h-5.5v-6h-5v6H4a1 1 0 0 1-1-1v-9.5z" />
                </svg>
                <span>Home</span>
            </a>

            <a class="nav-link {{ request()->routeIs('posts.index', 'posts.edit') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="4" y="4" width="16" height="16" rx="2.5" />
                    <path stroke-linecap="round" d="M8 9h8M8 13h8M8 17h5" />
                </svg>
                <span>My Posts</span>
            </a>

            <a class="nav-link {{ request()->routeIs('join-requests.*') ? 'active' : '' }}" href="{{ route('join-requests.index') }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6M16 11h6" />
                </svg>
                <span>Requests</span>
            </a>
        </nav>

        <div class="nav-spacer"></div>

        <div class="profile-block">
            <div class="profile">
                <div class="avatar">{{ $initials }}</div>
                <div>
                    <div class="profile-name">{{ $accountName }}</div>
                    <div class="profile-meta">{{ $currentAccount?->nim ?? $currentAccount?->email ?? 'Active Member' }}</div>
                </div>
            </div>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-link">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <a class="logout-link" href="{{ route('landing') }}">
                    <span>Back to Landing</span>
                </a>
            @endauth
        </div>
    </aside>

    <main class="main-content">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
