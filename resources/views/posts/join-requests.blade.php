@extends('layouts.app')

@section('title', 'Join Requests | TeamMate')

@push('styles')
<style>
    .requests-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .total-box {
        text-align: right;
    }

    .total-box strong {
        display: block;
        font-size: 26px;
        line-height: 1.1;
        color: #172033;
    }

    .total-box small {
        color: #64748b;
        font-size: 12px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 14px;
        font-weight: 700;
        margin: 4px 0 14px;
        color: #172033;
    }

    .count-badge {
        background: #fff0ca;
        color: #bd7a00;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
        font-weight: 700;
    }

    .request-card {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 14px;
    }

    .request-avatar {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #2160f5;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 13px;
        font-weight: 700;
    }

    .request-avatar.reviewed {
        background: #475569;
    }

    .request-info {
        flex: 1;
        min-width: 0;
    }

    .request-info strong {
        display: block;
        font-size: 14px;
        margin-bottom: 4px;
        color: #172033;
    }

    .request-details {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
        line-height: 1.5;
    }

    .post-reference {
        color: #2160f5;
        font-size: 12px;
        font-weight: 600;
        margin-top: 4px;
    }

    .request-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin: 10px 0 13px;
        font-size: 12px;
        color: #64748b;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .file-name {
        color: #2160f5;
        font-weight: 500;
    }

    .request-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-action {
        padding: 8px 16px;
        min-width: 80px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        font-family: inherit;
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

    .btn-action:hover {
        opacity: 0.9;
    }

    .status-pill {
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pill.pending {
        background: #fff7e5;
        color: #c77d00;
    }

    .status-pill.accepted {
        background: #e6f9ec;
        color: #139447;
    }

    .status-pill.declined {
        background: #fff1f1;
        color: #d23c47;
    }

    .reviewed-heading {
        margin-top: 32px;
    }

    .empty-box {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 28px;
        border-radius: 16px;
        color: #64748b;
        text-align: center;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
    <header class="page-header requests-header">
        <div>
            <h1>Join Requests</h1>
            <p>Review and manage incoming applications for your competition posts.</p>
        </div>

        <div class="total-box">
            <strong>{{ $totalApplicants }}</strong>
            <small>Total applicants</small>
        </div>
    </header>

    <div class="page-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <h2 class="section-title">
            <span class="count-badge">{{ $pendingRequests->count() }}</span>
            Pending Review
        </h2>

        @forelse ($pendingRequests as $joinRequest)
            @php
                $name = $joinRequest->account->full_name ?? 'Unknown';
                $initials = collect(explode(' ', trim($name)))
                    ->filter()
                    ->take(2)
                    ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                    ->implode('');
            @endphp

            <div class="request-card">
                <div class="request-avatar">
                    {{ $initials }}
                </div>

                <div class="request-info">
                    <strong>{{ $name }}</strong>

                    <div class="request-details">
                        {{ $joinRequest->account->email ?? '-' }}
                        @if ($joinRequest->account && $joinRequest->account->nim)
                            &nbsp;&middot;&nbsp; NIM: {{ $joinRequest->account->nim }}
                        @endif
                    </div>

                    <div class="post-reference">
                        Post: {{ $joinRequest->post->title ?? '-' }}
                    </div>

                    <div class="request-meta">
                        @if ($joinRequest->requirementFile)
                            <div class="meta-item">
                                <span class="file-name">{{ $joinRequest->requirementFile->file_name }}</span>
                            </div>
                        @endif

                        <div class="meta-item">
                            <span>
                                Applied {{ $joinRequest->request_date ? \Carbon\Carbon::parse($joinRequest->request_date)->format('Y-m-d') : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="request-actions">
                        <form method="POST" action="{{ route('join-requests.update-status', $joinRequest->request_id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="request_status" value="declined">
                            <button type="submit" class="btn-action btn-decline">Decline</button>
                        </form>

                        <form method="POST" action="{{ route('join-requests.update-status', $joinRequest->request_id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="request_status" value="accepted">
                            <button type="submit" class="btn-action btn-accept">Accept</button>
                        </form>
                    </div>
                </div>

                <span class="status-pill pending">Pending</span>
            </div>
        @empty
            <div class="empty-box">
                No pending join requests.
            </div>
        @endforelse

        <h2 class="section-title reviewed-heading">
            Reviewed
        </h2>

        @forelse ($reviewedRequests as $joinRequest)
            @php
                $name = $joinRequest->account->full_name ?? 'Unknown';
                $initials = collect(explode(' ', trim($name)))
                    ->filter()
                    ->take(2)
                    ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                    ->implode('');
                $status = $joinRequest->request_status;
            @endphp

            <div class="request-card">
                <div class="request-avatar reviewed">
                    {{ $initials }}
                </div>

                <div class="request-info">
                    <strong>{{ $name }}</strong>

                    <div class="request-details">
                        {{ $joinRequest->account->email ?? '-' }}
                        @if ($joinRequest->account && $joinRequest->account->nim)
                            &nbsp;&middot;&nbsp; NIM: {{ $joinRequest->account->nim }}
                        @endif
                    </div>

                    <div class="post-reference">
                        Post: {{ $joinRequest->post->title ?? '-' }}
                    </div>

                    <div class="request-meta">
                        @if ($joinRequest->requirementFile)
                            <div class="meta-item">
                                <span class="file-name">{{ $joinRequest->requirementFile->file_name }}</span>
                            </div>
                        @endif

                        <div class="meta-item">
                            <span>
                                Applied {{ $joinRequest->request_date ? \Carbon\Carbon::parse($joinRequest->request_date)->format('Y-m-d') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <span class="status-pill {{ $status }}">
                    {{ ucfirst($status) }}
                </span>
            </div>
        @empty
            <div class="empty-box">
                No reviewed requests yet.
            </div>
        @endforelse
    </div>
@endsection
