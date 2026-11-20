@extends('layouts.app')

@section('title', 'Report List | TeamMate Admin')

@section('content')
    <header class="page-header flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1>Report List &amp; Moderasi Postingan</h1>
            <p>Tinjau laporan pelanggaran dari pengguna, hapus postingan bermasalah beserta notifikasi pemilik, atau pertahankan postingan.</p>
        </div>

        <p class="text-xs font-medium text-slate-500 tabular-nums">
            <strong class="font-bold text-navy-900">{{ $pendingPostsCount }}</strong> postingan menunggu moderasi
        </p>
    </header>

    <div class="page-body space-y-6">
        @if (session('success') || session('info') || session('error') || $errors->any())
            <x-form.alerts />
        @endif

        {{-- Queue Tabs --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
            <nav aria-label="Filter status laporan" class="inline-flex flex-wrap items-center gap-1.5 rounded-xl bg-slate-100 p-1.5">
                <a
                    href="{{ route('admin.reports.index', ['tab' => 'pending']) }}"
                    class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-xs font-semibold transition {{ $tab === 'pending' ? 'bg-white text-navy-900 shadow-2xs' : 'text-slate-600 hover:text-navy-900' }}"
                >
                    <span>Pending Reports</span>
                    <span class="rounded-md px-1.5 py-0.5 font-mono text-[11px] font-bold tabular-nums {{ $tab === 'pending' ? 'bg-[#2160f5] text-white' : 'bg-slate-200/80 text-slate-700' }}">
                        {{ $pendingPostsCount }}
                    </span>
                </a>

                <a
                    href="{{ route('admin.reports.index', ['tab' => 'history']) }}"
                    class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-xs font-semibold transition {{ $tab === 'history' ? 'bg-white text-navy-900 shadow-2xs' : 'text-slate-600 hover:text-navy-900' }}"
                >
                    <span>Kept / Dismissed History</span>
                    <span class="rounded-md px-1.5 py-0.5 font-mono text-[11px] font-bold tabular-nums {{ $tab === 'history' ? 'bg-[#2160f5] text-white' : 'bg-slate-200/80 text-slate-700' }}">
                        {{ $dismissedReportsCount }}
                    </span>
                </a>
            </nav>
        </div>

        @if ($tab === 'pending')
            <div class="space-y-4">
                @forelse ($reportedPosts as $post)
                    @php
                        $hasPoster = filled($post->poster) && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->poster);
                    @endphp

                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                        <div class="grid lg:grid-cols-[16rem_1fr] lg:divide-x lg:divide-slate-200">
                            <div class="flex flex-col justify-between bg-slate-50/70 p-5">
                                @if ($hasPoster)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($post->poster) }}"
                                        alt="Poster {{ $post->title }}"
                                        class="h-36 w-full rounded-xl border border-slate-200 object-cover"
                                    >
                                @else
                                    <div class="flex h-36 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-white text-xs font-semibold text-slate-400 uppercase">
                                        No Poster
                                    </div>
                                @endif

                                <div class="mt-4 space-y-1 text-xs text-slate-600">
                                    <p class="font-semibold text-navy-900">{{ $post->account?->full_name ?? '-' }}</p>
                                    <p>{{ $post->account?->university ?? '-' }}</p>
                                    <p class="font-mono">{{ $post->account?->nim ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col justify-between p-5 sm:p-6">
                                <div>
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div>
                                            <span class="rounded-md bg-navy-900 px-2.5 py-1 text-[11px] font-semibold text-white">
                                                {{ $post->category?->category_name ?? 'Uncategorized' }}
                                            </span>
                                            <h2 class="mt-2 text-lg font-bold text-navy-900">{{ $post->title }}</h2>
                                        </div>

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-800">
                                            {{ $post->reports->count() }} Laporan Masuk
                                        </span>
                                    </div>

                                    <p class="mt-2 line-clamp-2 text-sm text-slate-600">
                                        {{ $post->description ?: 'Tidak ada deskripsi tambahan.' }}
                                    </p>

                                    <div class="mt-4 space-y-2.5 border-t border-slate-100 pt-4">
                                        <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Rincian Laporan</p>
                                        @foreach ($post->reports as $report)
                                            <div class="rounded-xl border border-red-100 bg-red-50/50 p-3 text-xs">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <span class="font-bold text-red-900">{{ $report->reason }}</span>
                                                    <span class="text-slate-500">
                                                        {{ $report->report_date ? $report->report_date->format('d M Y, H:i') : '-' }}
                                                    </span>
                                                </div>
                                                <p class="mt-1 text-slate-700">{{ $report->description ?: '-' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-4">
                                    <form method="POST" action="{{ route('admin.reports.keep-post', $post->post_id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-secondary text-xs">
                                            Keep Post / Dismiss Report
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.reports.remove-post', $post->post_id) }}"
                                        onsubmit="return confirm('Yakin hapus postingan ini? Pemilik akan menerima notifikasi penghapusan.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-red-700"
                                        >
                                            Remove Post
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
                        <p class="text-lg font-semibold text-navy-900">Tidak ada laporan postingan yang menunggu moderasi</p>
                        <p class="mt-1 text-sm text-slate-600">Semua laporan sudah ditinjau dan diputuskan.</p>
                    </div>
                @endforelse
            </div>
        @else
            <div class="space-y-3">
                @forelse ($dismissedReports as $report)
                    <div class="card flex flex-wrap items-start justify-between gap-4 p-5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    Kept / Dismissed
                                </span>
                                <span class="text-xs font-bold text-navy-900">{{ $report->reason }}</span>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-navy-900">
                                Post: {{ $report->post?->title ?? 'Postingan telah dihapus' }}
                            </p>
                            <p class="mt-1 text-xs text-slate-600">{{ $report->description ?: '-' }}</p>
                        </div>

                        <span class="text-xs text-slate-500">
                            {{ $report->report_date ? $report->report_date->format('d M Y, H:i') : '-' }}
                        </span>
                    </div>
                @empty
                    <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
                        <p class="text-lg font-semibold text-navy-900">Belum ada riwayat laporan yang dipertahankan</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
@endsection
