
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Requests | TeamMate</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #182338;
            background: #f7f9fc;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 248px;
            background: #101a30;
            color: #91a2bf;
            display: flex;
            flex-direction: column;
            z-index: 10;
        }

        .brand {
            height: 81px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 28px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            border-bottom: 1px solid #263149;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #2874f0;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        .sidebar-menu {
            padding: 19px 16px;
        }

        .create-button {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            background: #2160f5;
            color: white;
            padding: 12px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 11px 12px;
            margin-bottom: 3px;
            border-radius: 11px;
            text-decoration: none;
            color: #91a2bf;
            font-size: 14px;
            transition: .2s;
        }

        .nav-link:hover {
            background: #1d2a45;
            color: white;
        }

        .nav-link.active {
            background: #2160f5;
            color: white;
        }

        .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 18px;
        }

        .notification-badge {
            margin-left: auto;
            background: #f43f46;
            color: white;
            border-radius: 50%;
            width: 19px;
            height: 19px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 19px 20px;
            border-top: 1px solid #263149;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 15px;
        }

        .profile-avatar {
            width: 31px;
            height: 31px;
            border-radius: 50%;
            background: #079b76;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .profile-name {
            color: white;
            font-size: 13px;
            font-weight: bold;
        }

        .profile-nim {
            color: #91a2bf;
            font-size: 12px;
            margin-top: 3px;
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #91a2bf;
            font-size: 13px;
            text-decoration: none;
        }

        /* MAIN AREA */
        .main-area {
            margin-left: 248px;
            min-height: 100vh;
        }

        .topbar {
            height: 126px;
            background: white;
            padding: 26px 58px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-link {
            text-decoration: none;
            color: #8190a8;
            font-size: 14px;
        }

        .page-title {
            font-size: 23px;
            margin: 19px 0 0;
            font-weight: bold;
        }

        .total {
            text-align: right;
        }

        .total strong {
            display: block;
            font-size: 26px;
        }

        .total small {
            color: #8190a8;
            font-size: 12px;
        }

        /* CONTENT */
        .content {
            padding: 29px 58px 50px;
        }

        .content-inner {
            max-width: 850px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 14px;
            font-weight: bold;
            margin: 3px 0 14px;
        }

        .count {
            background: #fff0ca;
            color: #bd7a00;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }

        /* REQUEST CARD */
        .request-card {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 19px;
            background: white;
            border: 1px solid #edf1f5;
            border-radius: 15px;
            margin-bottom: 13px;
            min-height: 142px;
        }

        .avatar {
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #ed0055;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 13px;
            font-weight: bold;
        }

        .avatar.reviewed {
            background: #2563eb;
        }

        .info {
            flex: 1;
            min-width: 0;
        }

        .info strong {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .details {
            font-size: 12px;
            color: #7888a0;
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin: 10px 0 13px;
            font-size: 12px;
            color: #7888a0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .file-name {
            color: #456a9f;
        }

        .post-reference {
            color: #8794a8;
            font-size: 11px;
            margin-top: 4px;
        }

        .actions {
            display: flex;
            gap: 8px;
            margin-top: 11px;
        }

        .btn {
            padding: 7px 15px;
            min-width: 72px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        .btn-decline {
            background: #fff1f1;
            color: #d23c47;
            border: 1px solid #ffcccc;
        }

        .btn-accept {
            background: #08a546;
            color: white;
        }

        .btn:hover {
            opacity: .85;
        }

        .status {
            font-size: 11px;
            padding: 6px 11px;
            border-radius: 20px;
            font-weight: bold;
            white-space: nowrap;
        }

        .pending {
            background: #fff7e5;
            color: #c77d00;
        }

        .accepted {
            background: #e6f9ec;
            color: #139447;
        }

        .declined {
            background: #fff1f1;
            color: #d23c47;
        }

        .reviewed-heading {
            margin-top: 32px;
        }

        .empty {
            background: white;
            padding: 25px;
            border-radius: 12px;
            color: #8190a8;
            text-align: center;
            font-size: 13px;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .alert-success {
            background: #e6f9ec;
            color: #139447;
        }

        .alert-error {
            background: #fff1f1;
            color: #d23c47;
        }

        /* RESPONSIVE */
        @media(max-width: 900px) {
            .sidebar {
                width: 205px;
            }

            .main-area {
                margin-left: 205px;
            }

            .topbar {
                padding: 24px 30px;
            }

            .content {
                padding: 25px 30px;
            }
        }

        @media(max-width: 600px) {
            .sidebar {
                width: 65px;
            }

            .brand {
                padding: 0;
                justify-content: center;
            }

            .brand span:last-child,
            .create-button span:last-child,
            .nav-link span:last-child,
            .sidebar-bottom {
                display: none;
            }

            .sidebar-menu {
                padding: 12px 8px;
            }

            .create-button,
            .nav-link {
                justify-content: center;
                padding: 12px 5px;
            }

            .main-area {
                margin-left: 65px;
            }

            .topbar {
                padding: 20px;
            }

            .content {
                padding: 20px 14px;
            }

            .request-card {
                padding: 13px;
                flex-wrap: wrap;
            }

            .status {
                margin-left: 54px;
            }
        }
    </style>
</head>

<body>

@php
    $currentAccount = auth()->user();
    $notificationCount = $currentAccount
        ? $currentAccount->notifications()->count()
        : 0;

    $accountName = $currentAccount->full_name ?? 'Demo Account';
    $accountNim = $currentAccount->nim ?? 'Preview mode';

    $profileInitials = collect(explode(' ', trim($accountName)))
        ->filter()
        ->take(2)
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');
@endphp

<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="brand">
        <div class="brand-icon">♙</div>
        <span>TeamMate</span>
    </div>

    <div class="sidebar-menu">

        <a href="{{ route('posts.create') }}" class="create-button">
            <span style="font-size:20px">+</span>
            <span>Create Post</span>
        </a>

        <a href="{{ route('home') }}" class="nav-link">
            <span class="nav-icon">⌂</span>
            <span>Home</span>
        </a>

        <a href="{{ route('posts.index') }}" class="nav-link" title="My Posts">
            <span class="nav-icon">▤</span>
            <span>My Posts</span>
        </a>

        <a href="{{ route('join-requests.index') }}" class="nav-link active">
            <span class="nav-icon">♧</span>
            <span>Requests</span>
        </a>

        <a href="#" class="nav-link" title="Notifications">
            <span class="nav-icon">♧</span>
            <span>Notifications</span>

            @if($notificationCount > 0)
                <span class="notification-badge">
                    {{ $notificationCount }}
                </span>
            @endif
        </a>

        <a href="#" class="nav-link" title="Profile">
            <span class="nav-icon">♙</span>
            <span>Profile</span>
        </a>

    </div>

    <!-- PROFILE BOTTOM -->
    <div class="sidebar-bottom">

        <div class="profile-info">
            <div class="profile-avatar">
                {{ $profileInitials ?: 'TM' }}
            </div>

            <div>
                <div class="profile-name">
                    {{ $accountName }}
                </div>

                <div class="profile-nim">
                    {{ $accountNim }}
                </div>
            </div>
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link" style="background:none;border:0;cursor:pointer;font:inherit;padding:0;">
                    <span>⇥</span>
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('landing') }}" class="logout-link">
                <span>⇥</span>
                Back to Landing
            </a>
        @endauth

    </div>

</aside>


<!-- MAIN AREA -->
<div class="main-area">

    <!-- HEADER -->
    <header class="topbar">

        <div>
            <a href="{{ route('home') }}" class="back-link">
                &larr; Back
            </a>

            <h1 class="page-title">Join Requests</h1>
        </div>

        <div class="total">
            <strong>{{ $totalApplicants }}</strong>
            <small>Total applicants</small>
        </div>

    </header>


    <!-- CONTENT -->
    <main class="content">

        <div class="content-inner">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif


            <!-- PENDING REVIEW -->
            <h2 class="section-title">
                <span class="count">{{ $pendingRequests->count() }}</span>
                Pending Review
            </h2>

            @forelse($pendingRequests as $joinRequest)

                @php
                    $name = $joinRequest->account->full_name ?? 'Unknown';

                    $initials = collect(explode(' ', trim($name)))
                        ->filter()
                        ->take(2)
                        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                        ->implode('');
                @endphp

                <div class="request-card">

                    <div class="avatar">
                        {{ $initials }}
                    </div>

                    <div class="info">

                        <strong>{{ $name }}</strong>

                        <div class="details">
                            {{ $joinRequest->account->email ?? '-' }}

                            @if($joinRequest->account && $joinRequest->account->nim)
                                &nbsp;·&nbsp; NIM: {{ $joinRequest->account->nim }}
                            @endif
                        </div>

                        <div class="post-reference">
                            Post: {{ $joinRequest->post->title ?? '-' }}
                        </div>

                        <div class="meta">

                            @if($joinRequest->requirementFile)
                                <div class="meta-item">
                                    <span>⌁</span>
                                    <span class="file-name">
                                        {{ $joinRequest->requirementFile->file_name }}
                                    </span>
                                </div>
                            @endif

                            <div class="meta-item">
                                <span>▦</span>
                                <span>
                                    Applied
                                    {{ $joinRequest->request_date
                                        ? \Carbon\Carbon::parse($joinRequest->request_date)->format('Y-m-d')
                                        : '-'
                                    }}
                                </span>
                            </div>

                        </div>

                        <div class="actions">

                            <form
                                method="POST"
                                action="{{ route('join-requests.update-status', $joinRequest->request_id) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="request_status"
                                    value="declined"
                                >

                                <button type="submit" class="btn btn-decline">
                                    Decline
                                </button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('join-requests.update-status', $joinRequest->request_id) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="request_status"
                                    value="accepted"
                                >

                                <button type="submit" class="btn btn-accept">
                                    Accept
                                </button>
                            </form>

                        </div>

                    </div>

                    <span class="status pending">
                        Pending
                    </span>

                </div>

            @empty

                <div class="empty">
                    No pending join requests.
                </div>

            @endforelse


            <!-- REVIEWED -->
            <h2 class="section-title reviewed-heading">
                Reviewed
            </h2>

            @forelse($reviewedRequests as $joinRequest)

                @php
                    $name = $joinRequest->account->full_name ?? 'Unknown';

                    $initials = collect(explode(' ', trim($name)))
                        ->filter()
                        ->take(2)
                        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                        ->implode('');

                    $status = $joinRequest->request_status;
                @endphp

                <div class="request-card">

                    <div class="avatar reviewed">
                        {{ $initials }}
                    </div>

                    <div class="info">

                        <strong>{{ $name }}</strong>

                        <div class="details">
                            {{ $joinRequest->account->email ?? '-' }}

                            @if($joinRequest->account && $joinRequest->account->nim)
                                &nbsp;·&nbsp; NIM: {{ $joinRequest->account->nim }}
                            @endif
                        </div>

                        <div class="post-reference">
                            Post: {{ $joinRequest->post->title ?? '-' }}
                        </div>

                        <div class="meta">

                            @if($joinRequest->requirementFile)
                                <div class="meta-item">
                                    <span>⌁</span>
                                    <span class="file-name">
                                        {{ $joinRequest->requirementFile->file_name }}
                                    </span>
                                </div>
                            @endif

                            <div class="meta-item">
                                <span>▦</span>
                                <span>
                                    Applied
                                    {{ $joinRequest->request_date
                                        ? \Carbon\Carbon::parse($joinRequest->request_date)->format('Y-m-d')
                                        : '-'
                                    }}
                                </span>
                            </div>

                        </div>

                    </div>

                    <span class="status {{ $status }}">
                        {{ ucfirst($status) }}
                    </span>

                </div>

            @empty

                <div class="empty">
                    No reviewed requests yet.
                </div>

            @endforelse

        </div>

    </main>

</div>

</body>
</html>
