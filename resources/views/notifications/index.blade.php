@extends('layouts.app')

@section('title', 'Notifications | TeamMate')

@section('content')
    <header class="page-header flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1>Notifications</h1>
            <p>Updates regarding your competition posts and join requests.</p>
        </div>

        <p class="text-xs font-semibold text-slate-500 tabular-nums">
            <strong class="font-bold text-navy-900">{{ $notifications->count() }}</strong> notifications
        </p>
    </header>

    <div class="page-body space-y-3">
        @forelse ($notifications as $notification)
            <article class="card flex flex-col justify-between gap-2 p-5 sm:flex-row sm:items-center">
                <p class="text-sm leading-relaxed text-navy-900">{{ $notification->message }}</p>
                <span class="shrink-0 text-xs text-slate-500">
                    {{ $notification->time_created ? $notification->time_created->format('d M Y, H:i') : '-' }}
                </span>
            </article>
        @empty
            <div class="card p-10 text-center">
                <p class="text-base font-bold text-navy-900">Belum ada notifikasi</p>
                <p class="mt-1 text-sm text-slate-600">Informasi penting seputar tim dan postingan kamu akan tampil di sini.</p>
            </div>
        @endforelse
    </div>
@endsection
