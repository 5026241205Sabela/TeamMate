@forelse ($posts as $post)
    @php
        $categoryName = $post->category?->category_name ?? 'Uncategorized';
        $authorName = $post->account?->full_name ?? 'TeamMate Student';
        $universityName = $post->account?->university ?? 'University';
        $postedTime = $post->date_created ? $post->date_created->diffForHumans() : 'Just now';
        $posterUrl = $post->poster ? \Illuminate\Support\Facades\Storage::url($post->poster) : null;
        $initials = collect(explode(' ', trim($authorName)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('') ?: 'TM';
    @endphp

    <article
        class="card card-interactive reveal-in flex cursor-pointer flex-col justify-between p-5"
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
        <div>
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-semibold text-[#2160f5]">
                    {{ $categoryName }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#e6f9ec] px-2.5 py-0.5 text-[11px] font-bold text-[#139447]">
                    Still Looking
                </span>
            </div>

            @if ($posterUrl)
                <img
                    src="{{ $posterUrl }}"
                    alt="{{ $post->title }}"
                    class="mt-3 h-36 w-full rounded-xl border border-slate-100 object-cover"
                    loading="lazy"
                >
            @endif

            <h3 class="mt-3 text-base font-bold text-navy-900">{{ $post->title }}</h3>

            @if ($post->description)
                <p class="mt-1.5 line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $post->description }}</p>
            @endif

            @if ($post->member_criteria)
                <div class="mt-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-2.5 text-xs leading-relaxed text-slate-700">
                    <span class="font-semibold text-navy-900">Team Criteria:</span>
                    <p class="mt-0.5 line-clamp-2">{{ $post->member_criteria }}</p>
                </div>
            @endif
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

            <div class="mt-3.5 flex items-center gap-2">
                <button type="button" data-open-post-detail class="btn btn-primary flex-1 py-2 text-xs">
                    View Details
                </button>
                <a
                    href="{{ route('reports.create', $post->post_id) }}"
                    class="btn px-3 py-2 text-xs text-slate-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                    title="Report this post"
                >
                    Report
                </a>
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
