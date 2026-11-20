@extends('layouts.app')

@section('title', 'Home | TeamMate')

@section('content')
    <header class="page-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1>Find Your Competition Teammate</h1>
            <p>Browse active competition posts and connect with students looking for teammates.</p>
        </div>
        <div class="sm:text-right">
            <strong class="block text-2xl leading-tight font-bold text-navy-900 tabular-nums">{{ $posts->total() }}</strong>
            <small class="text-xs text-slate-500">Active posts</small>
        </div>
    </header>

    <div class="page-body space-y-6">
        @if (session('success') || session('info'))
            <x-form.alerts />
        @endif

        {{-- Search & Category Filter Command Bar --}}
        <form
            id="feed-filter-form"
            data-feed-filter
            action="{{ route('posts.latest') }}"
            method="GET"
            class="card space-y-4 p-5"
        >
            <div class="grid gap-4 lg:grid-cols-[1fr_15rem_auto] lg:items-end">
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
                        Clear Filter
                    </button>
                </div>
            </div>

            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3.5">
                    <span class="text-xs font-medium text-slate-500">Quick filter:</span>
                    <button
                        type="button"
                        data-category-pill=""
                        class="cursor-pointer rounded-lg bg-[#2160f5] px-3 py-1 text-xs font-semibold text-white transition"
                    >
                        All
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
                <div data-feed-skeleton class="grid items-stretch gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <x-post-skeleton />
                    <x-post-skeleton />
                    <x-post-skeleton />
                </div>

                <div data-feed-target hidden class="grid items-stretch gap-6 md:grid-cols-2 xl:grid-cols-3">
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
@endsection
