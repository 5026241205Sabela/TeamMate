@extends('layouts.app')

@section('title', $post->title.' | TeamMate')

@section('content')
    @php
        $categoryName = $post->category?->category_name ?? 'Uncategorized';
        $authorName = $post->account?->full_name ?? 'TeamMate Student';
        $universityName = $post->account?->university ?? 'University';
        $hasPosterFile = filled($post->poster) && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->poster);
        $posterUrl = $hasPosterFile ? \Illuminate\Support\Facades\Storage::url($post->poster) : null;
        $statusMeta = match ($post->post_status) {
            'active' => ['label' => 'Still Looking', 'bg' => 'bg-[#e6f9ec] text-[#139447]', 'dot' => 'bg-[#139447]'],
            'closed' => ['label' => 'Done Accepting', 'bg' => 'bg-amber-50 text-amber-800', 'dot' => 'bg-amber-600'],
            'completed' => ['label' => 'Found a TeamMate', 'bg' => 'bg-sky-50 text-sky-900', 'dot' => 'bg-[#2160f5]'],
            default => ['label' => ucfirst($post->post_status), 'bg' => 'bg-slate-100 text-slate-700', 'dot' => 'bg-slate-500'],
        };
    @endphp

    <header class="page-header flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ $isOwner ? route('posts.index') : route('home') }}" class="text-xs font-semibold text-[#2160f5] hover:underline">
                &larr; {{ $isOwner ? 'Back to My Posts' : 'Back to Home' }}
            </a>
            <h1 class="mt-1">{{ $post->title }}</h1>
            <p>{{ $categoryName }} &middot; Diposting oleh {{ $authorName }} ({{ $universityName }})</p>
        </div>

        <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold {{ $statusMeta['bg'] }}">
            <span class="size-2 rounded-full {{ $statusMeta['dot'] }}"></span>
            {{ $statusMeta['label'] }}
        </span>
    </header>

    <div class="page-body space-y-6">
        <x-form.alerts />

        @if ($post->post_status === 'completed' && $post->deletion_schedule)
            <div role="status" class="rounded-2xl border border-sky-200 bg-sky-50 px-5 py-3.5 text-sm text-navy-900">
                Status postingan adalah <strong>Found a TeamMate</strong>. Postingan ini dijadwalkan terhapus otomatis pada
                <strong>{{ $post->deletion_schedule->format('d M Y, H:i') }}</strong>.
            </div>
        @endif

        <article class="card overflow-hidden p-0">
            @if ($posterUrl)
                <div class="border-b border-slate-200 bg-slate-100">
                    <img src="{{ $posterUrl }}" alt="Poster {{ $post->title }}" class="mx-auto max-h-96 w-full object-contain bg-slate-900/5">
                </div>
            @endif

            <div class="space-y-6 p-6 sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Pembuat Postingan</p>
                        <p class="mt-1 text-base font-bold text-navy-900">{{ $authorName }}</p>
                        <p class="text-xs text-slate-600">{{ $universityName }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Tanggal Dibuat</p>
                        <p class="mt-1 text-sm font-semibold text-navy-900">
                            {{ $post->date_created ? $post->date_created->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <h2 class="text-sm font-bold tracking-wide text-slate-500 uppercase">Deskripsi Kompetisi</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                        {{ $post->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <h2 class="text-sm font-bold text-navy-900">Kriteria Calon Anggota Tim</h2>
                    <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                        {{ $post->member_criteria ?: 'Terbuka untuk mahasiswa berkomitmen dari berbagai jurusan.' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                    @if ($isOwner)
                        <div class="flex flex-wrap items-center gap-2.5">
                            <a href="{{ route('join-requests.index', ['post_id' => $post->post_id]) }}" class="btn-secondary">
                                Requests ({{ $post->join_requests_count }})
                            </a>
                            <a href="{{ route('posts.edit', $post->post_id) }}" class="btn-primary">
                                Edit Status
                            </a>
                        </div>

                        <button
                            type="button"
                            data-open-delete-dialog
                            class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100"
                        >
                            Delete
                        </button>
                    @else
                        <div class="flex flex-wrap items-center gap-3">
                            @if ($existingRequest)
                                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700">
                                    Application Submitted ({{ ucfirst($existingRequest->request_status) }})
                                </span>
                            @elseif ($post->post_status === 'active')
                                <a href="{{ route('join-requests.create', $post->post_id) }}" class="btn-primary bg-emerald-600 hover:bg-emerald-700">
                                    Apply
                                </a>
                            @else
                                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500">
                                    Pendaftaran Ditutup
                                </span>
                            @endif
                        </div>

                        <a
                            href="{{ route('reports.create', $post->post_id) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                        >
                            Report
                        </a>
                    @endif
                </div>
            </div>
        </article>
    </div>

    @if ($isOwner)
        <dialog id="delete-post-dialog" class="m-auto w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl backdrop:bg-navy-900/50">
            <h2 class="text-lg font-bold text-navy-900">Hapus Postingan?</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Postingan <strong>{{ $post->title }}</strong> beserta seluruh permintaan bergabung dan laporannya akan dihapus permanen.
            </p>

            <div class="mt-6 flex items-center justify-end gap-2.5">
                <button type="button" data-close-delete-dialog class="btn-secondary">
                    Cancel
                </button>
                <form method="POST" action="{{ route('posts.destroy', $post->post_id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                        Confirm Delete
                    </button>
                </form>
            </div>
        </dialog>

        @push('scripts')
            <script>
                const deleteDialog = document.getElementById('delete-post-dialog');
                document.querySelector('[data-open-delete-dialog]')?.addEventListener('click', () => {
                    deleteDialog?.showModal();
                });
                document.querySelector('[data-close-delete-dialog]')?.addEventListener('click', () => {
                    deleteDialog?.close();
                });
            </script>
        @endpush
    @endif
@endsection
