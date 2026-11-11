@forelse ($posts as $post)
    @php
        $categoryName = $post->category?->category_name ?? 'Uncategorized';
        $authorName = $post->account?->full_name ?? 'TeamMate Student';
        $universityName = $post->account?->university ?? 'University';
        $postedTime = $post->date_created ? $post->date_created->diffForHumans() : 'Just now';
        $hasPosterFile = filled($post->poster) && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->poster);
        $posterUrl = $hasPosterFile ? \Illuminate\Support\Facades\Storage::url($post->poster) : null;
        $initials = collect(explode(' ', trim($authorName)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('') ?: 'TM';
    @endphp

    <article
        class="card card-interactive reveal-in flex h-full cursor-pointer flex-col overflow-hidden p-0"
        style="--i: {{ $loop->index }}"
        data-post-card
        data-post-id="{{ $post->post_id }}"
        data-post-title="{{ $post->title }}"
        data-post-category="{{ $categoryName }}"
        data-post-author="{{ $authorName }}"
        data-post-university="{{ $universityName }}"
        data-post-time="{{ $postedTime }}"
        data-post-description="{{ $post->description ?? 'No additional description provided.' }}"
        data-post-criteria="{{ $post->member_criteria ?? 'Open to committed students from any major.' }}"
        data-post-report-url="{{ route('reports.create', $post->post_id) }}"
    >
        {{-- Uniform 16:9 / h-40 Poster / Cover Header --}}
        <div class="relative flex h-40 w-full shrink-0 flex-col justify-between overflow-hidden border-b border-slate-100 bg-slate-100 p-4">
            @if ($posterUrl)
                <img
                    src="{{ $posterUrl }}"
                    alt="{{ $post->title }}"
                    class="absolute inset-0 size-full object-cover"
                    loading="lazy"
                    onerror="this.style.display='none'"
                >
                <div class="absolute inset-0 bg-slate-900/25"></div>
            @else
                <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                    <div class="flex flex-col items-center gap-1.5 text-slate-400">
                        <svg class="size-8 stroke-[1.6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="16" rx="2.5" />
                            <circle cx="8.5" cy="9.5" r="1.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 15-4.5-4.5L7 20" />
                        </svg>
                        <span class="text-[11px] font-semibold tracking-wide uppercase">{{ $categoryName }}</span>
                    </div>
                </div>
            @endif

            <div class="relative z-10 flex items-center justify-between gap-2">
                <span class="rounded-lg bg-white/95 px-2.5 py-1 text-xs font-semibold text-[#2160f5] shadow-2xs backdrop-blur-xs">
                    {{ $categoryName }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e6f9ec] px-2.5 py-1 text-[11px] font-bold text-[#139447] shadow-2xs">
                    <span class="size-1.5 rounded-full bg-[#139447]"></span>
                    Still Looking
                </span>
            </div>
        </div>

        {{-- Uniform Card Body --}}
        <div class="flex flex-1 flex-col justify-between p-5">
            <div>
                <h3 class="line-clamp-2 min-h-[2.75rem] text-base leading-snug font-bold text-navy-900" title="{{ $post->title }}">
                    {{ $post->title }}
                </h3>

                <p class="mt-1.5 line-clamp-2 min-h-[2.5rem] text-sm leading-relaxed text-slate-600">
                    {{ $post->description ?: 'No additional description provided.' }}
                </p>

                <div class="mt-3.5 min-h-[4.5rem] rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-2.5 text-xs leading-relaxed text-slate-700">
                    <span class="font-semibold text-navy-900">Team Criteria:</span>
                    <p class="mt-0.5 line-clamp-2">
                        {{ $post->member_criteria ?: 'Open to committed students from any major.' }}
                    </p>
                </div>
            </div>

            <div class="mt-4 pt-4">
                <div class="flex items-center gap-3 border-t border-slate-100 pt-3.5">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-[#079b72] text-xs font-bold text-white">
                        {{ $initials }}
                    </span>
                    <div class="min-w-0 flex-1 text-xs">
                        <p class="truncate font-semibold text-navy-900">{{ $authorName }}</p>
                        <p class="truncate text-slate-500">{{ $universityName }} &middot; {{ $postedTime }}</p>
                    </div>
                </div>


                <div class="mt-3.5 grid grid-cols-2 gap-2">

                    {{-- VIEW DETAILS --}}
                    <button
                        type="button"
                        data-open-post-detail
                        class="btn btn-primary w-full px-2 py-2 text-xs"
                    >
                        View Details
                    </button>

                    {{-- APPLY / MY POST --}}
                    @if ((int) auth()->id() === (int) $post->account_id)

                        <span
                            class="btn flex w-full items-center justify-center
                                bg-slate-100 px-2 py-2 text-xs
                                font-semibold text-slate-500"
                            title="This is your post"
                        >
                            My Post
                        </span>

                    @elseif (\Illuminate\Support\Facades\Route::has('join-requests.create'))

                        <a
                            href="{{ route('join-requests.create', $post->post_id) }}"
                            class="btn flex w-full items-center justify-center
                                bg-emerald-600 px-2 py-2 text-xs
                                font-semibold text-white hover:bg-emerald-700"
                        >
                            Apply
                        </a>

                    @else

                        <button
                            type="button"
                            disabled
                            title="Apply feature is not available yet"
                            class="btn w-full cursor-not-allowed
                                bg-emerald-600 px-2 py-2 text-xs
                                text-white opacity-50"
                        >
                            Apply
                        </button>

                    @endif

                </div>

            </div>
        </div>
    </article>
@empty
    <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-10 text-center">
        <p class="text-lg font-semibold text-navy-900">Tidak ada postingan kompetisi yang cocok</p>
        <p class="mx-auto mt-1 max-w-md text-sm text-slate-600">
            Coba ubah kata kunci pencarian atau kategori filter, atau buat postingan baru untuk merekrut anggota tim.
        </p>
        <a href="{{ route('posts.create') }}" class="btn btn-primary mt-5">
            Create Post
        </a>
    </div>
@endforelse


<div class="col-span-full mt-4" data-feed-total="{{ $posts->total() }}">
    @if ($posts->hasPages())
        <nav aria-label="Pagination postingan"
             class="flex flex-wrap items-center justify-between gap-4">

            <p class="text-sm text-slate-500">
                Menampilkan {{ $posts->firstItem() }}
                - {{ $posts->lastItem() }}
                dari {{ $posts->total() }} postingan
            </p>

            <div class="flex flex-wrap items-center gap-2">

                {{-- Previous --}}
                <button
                    type="button"
                    data-feed-page="{{ $posts->currentPage() - 1 }}"
                    @disabled($posts->onFirstPage())
                    class="btn-secondary px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                >
                    Previous
                </button>

                {{-- Nomor halaman --}}
                @for ($page = 1; $page <= $posts->lastPage(); $page++)
                    <button
                        type="button"
                        data-feed-page="{{ $page }}"
                        aria-current="{{ $page === $posts->currentPage() ? 'page' : 'false' }}"
                        class="rounded-lg border px-4 py-2 text-sm font-semibold transition
                            {{ $page === $posts->currentPage()
                                ? 'border-blue-600 bg-blue-600 text-white'
                                : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-100' }}"
                    >
                        {{ $page }}
                    </button>
                @endfor

                {{-- Next --}}
                <button
                    type="button"
                    data-feed-page="{{ $posts->currentPage() + 1 }}"
                    @disabled(! $posts->hasMorePages())
                    class="btn-secondary px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                >
                    Next
                </button>

            </div>
        </nav>
    @endif
</div>
