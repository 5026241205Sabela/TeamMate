@extends('layouts.app')

@section('title', 'Home | TeamMate')

@section('content')
    <header class="page-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1>Find Your Competition Teammate</h1>
            <p>Telusuri tim kompetisi aktif yang sedang membuka rekrutmen anggota baru.</p>
        </div>
        <a href="{{ route('posts.create') }}" class="btn-primary self-start sm:self-auto">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
            </svg>
            Create Post
        </a>
    </header>

    <div class="page-body space-y-6">
        @if (session('success') || session('info'))
            <x-form.alerts />
        @endif

        @auth
            @unless (auth()->user()->hasVerifiedEmail())
                <div role="status" class="flex flex-col gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-semibold">Verifikasi email kamu</p>
                        <p class="mt-0.5 text-amber-800">Kami sudah mengirim link verifikasi ke <span class="font-semibold">{{ auth()->user()->email }}</span>. Klik link di email agar akunmu terverifikasi penuh.</p>
                    </div>
                    <a href="{{ route('verification.notice') }}" class="btn-secondary shrink-0 self-start sm:self-auto">
                        Cek Status Verifikasi
                    </a>
                </div>
            @endunless
        @endauth

        {{-- Search & Category Filter Command Bar --}}
        <form
            id="feed-filter-form"
            data-feed-filter
            action="{{ route('posts.latest') }}"
            method="GET"
            class="card space-y-4 p-5"
        >
            <div class="grid gap-4 sm:grid-cols-[1fr_15rem_auto] sm:items-end">
                <div>
                    <label for="filter-keyword" class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        Keyword Search
                    </label>
                    <input
                        id="filter-keyword"
                        name="q"
                        type="search"
                        placeholder="Cari nama lomba, topik, atau keahlian (mis. UI/UX, Laravel, Business)..."
                        class="form-input"
                    >
                </div>

                <div>
                    <label for="filter-category" class="mb-1.5 block text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        Competition Category
                    </label>
                    <select id="filter-category" name="category_id" class="form-input">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn-primary px-6 py-2.5">
                        Filter
                    </button>
                    <button type="reset" data-feed-reset class="btn-secondary px-4 py-2.5">
                        Reset
                    </button>
                </div>
            </div>

            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3.5">
                    <span class="text-xs font-medium text-slate-500">Kategori cepat:</span>
                    <button
                        type="button"
                        data-category-pill=""
                        class="cursor-pointer rounded-lg bg-navy-900 px-3 py-1 text-xs font-semibold text-white transition"
                    >
                        Semua
                    </button>
                    @foreach ($categories as $category)
                        <button
                            type="button"
                            data-category-pill="{{ $category->category_id }}"
                            class="cursor-pointer rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 transition hover:bg-navy-50 hover:text-navy-900"
                        >
                            {{ $category->category_name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </form>

        {{-- Active Recruitment Posts Feed with Shimmer Skeleton --}}
        <section aria-labelledby="active-posts-heading">
            <div class="mb-4 flex items-center justify-between">
                <h2 id="active-posts-heading" class="text-lg font-bold text-navy-900">Active Recruitment Posts</h2>
                <span data-feed-count class="text-xs font-semibold text-slate-500 tabular-nums">Memuat postingan...</span>
            </div>

            <div data-feed-url="{{ route('posts.latest') }}" aria-live="polite" aria-busy="true">
                <div data-feed-skeleton class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <x-post-skeleton />
                    <x-post-skeleton />
                    <x-post-skeleton />
                </div>

                <div data-feed-target hidden class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @include('posts.partials.latest', ['posts' => $posts])
                </div>

                <noscript>
                    <p class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-600">
                        Aktifkan JavaScript untuk memuat daftar lowongan tim.
                    </p>
                </noscript>
            </div>
        </section>
    </div>

    {{-- Post Details Modal --}}
    <div
        id="post-detail-modal"
        data-post-modal
        hidden
        class="fixed inset-0 z-50 flex items-center justify-center bg-navy-900/50 p-4 backdrop-blur-xs"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-post-title"
    >
        <div class="card w-full max-w-lg p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <span data-modal-category class="rounded-md bg-navy-900 px-3 py-1 text-xs font-semibold text-white"></span>
                <button
                    type="button"
                    data-modal-close
                    class="cursor-pointer rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-navy-900"
                    aria-label="Tutup detail"
                >
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <h3 id="modal-post-title" data-modal-title class="mt-3 text-xl font-bold text-navy-900"></h3>
            <p data-modal-meta class="mt-1 text-xs font-medium text-slate-500"></p>

            <div class="mt-5 space-y-4 border-t border-slate-100 pt-4 text-sm">
                <div>
                    <p class="font-semibold text-navy-900">Deskripsi Kompetisi</p>
                    <p data-modal-description class="mt-1 leading-relaxed text-slate-600"></p>
                </div>

                <div class="rounded-xl bg-navy-50 p-4">
                    <p class="font-semibold text-navy-900">Kriteria Anggota yang Dicari</p>
                    <p data-modal-criteria class="mt-1 leading-relaxed text-navy-800"></p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                <a data-modal-report href="#" class="text-xs font-semibold text-red-600 hover:underline">
                    Laporkan Postingan
                </a>
                <div class="flex gap-2">
                    <button type="button" data-modal-close class="btn-secondary px-4 py-2">
                        Tutup
                    </button>
                    <a href="{{ route('join-requests.index') }}" class="btn-primary px-5 py-2">
                        Lihat Join Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
