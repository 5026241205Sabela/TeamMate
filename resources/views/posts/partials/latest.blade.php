@forelse ($posts as $post)
    @php
        $categoryName = $post->category?->category_name ?? 'Umum';
        $authorName = $post->account?->full_name ?? 'Mahasiswa TeamMate';
        $universityName = $post->account?->university ?? 'Universitas tidak diketahui';
        $postedTime = $post->date_created ? $post->date_created->diffForHumans() : 'Baru saja';
        $posterUrl = $post->poster ? \Illuminate\Support\Facades\Storage::url($post->poster) : null;
    @endphp

    <article
        class="card card-interactive reveal-in flex cursor-pointer flex-col overflow-hidden p-0"
        style="--i: {{ $loop->index }}"
        data-post-card
        data-post-id="{{ $post->post_id }}"
        data-post-title="{{ $post->title }}"
        data-post-category="{{ $categoryName }}"
        data-post-author="{{ $authorName }}"
        data-post-university="{{ $universityName }}"
        data-post-time="{{ $postedTime }}"
        data-post-description="{{ $post->description ?? 'Tidak ada deskripsi tambahan.' }}"
        data-post-criteria="{{ $post->member_criteria ?? 'Terbuka untuk mahasiswa lintas jurusan yang berkomitmen.' }}"
        data-post-report-url="{{ route('reports.create', $post->post_id) }}"
    >
        <div class="relative flex h-32 items-end justify-between bg-gradient-to-br from-navy-900 via-navy-700 to-sky-700 p-4 text-white">
            @if ($posterUrl)
                <img src="{{ $posterUrl }}" alt="{{ $post->title }}" class="absolute inset-0 size-full object-cover opacity-35" loading="lazy">
            @endif
            <span class="relative inline-flex items-center gap-1.5 rounded-full bg-emerald-400/20 px-3 py-1 text-xs font-semibold text-emerald-100 ring-1 ring-emerald-300/40 backdrop-blur-xs">
                <span class="size-1.5 rounded-full bg-emerald-300"></span>
                Open Recruitment
            </span>
            <span class="relative rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-white backdrop-blur-xs">
                {{ $categoryName }}
            </span>
        </div>

        <div class="flex flex-1 flex-col p-5">
            <h3 class="text-lg font-bold text-navy-900">{{ $post->title }}</h3>

            @if ($post->description)
                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $post->description }}</p>
            @endif

            @if ($post->member_criteria)
                <div class="mt-3 rounded-xl bg-navy-50 px-3.5 py-2.5 text-xs leading-relaxed text-navy-800">
                    <span class="font-semibold text-navy-900">Kriteria Anggota:</span>
                    <p class="mt-0.5 line-clamp-2">{{ $post->member_criteria }}</p>
                </div>
            @endif

            <div class="mt-auto pt-4">
                <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-navy-900 text-xs font-bold text-white">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($authorName, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1 text-xs">
                        <p class="truncate font-semibold text-navy-900">{{ $authorName }}</p>
                        <p class="truncate text-slate-500">{{ $universityName }} &middot; {{ $postedTime }}</p>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="button" data-open-post-detail class="btn-secondary flex-1 py-2 text-xs">
                        View Details
                    </button>
                    <a
                        href="{{ route('reports.create', $post->post_id) }}"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                        title="Laporkan postingan ini"
                    >
                        Report
                    </a>
                </div>
            </div>
        </div>
    </article>
@empty
    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
        <p class="text-lg font-semibold text-navy-900">Tidak ada postingan kompetisi yang cocok</p>
        <p class="mx-auto mt-1 max-w-md text-sm text-slate-600">
            Coba ubah kata kunci pencarian atau kategori filter, atau buat postingan baru untuk merekrut anggota tim.
        </p>
        <a href="{{ route('posts.create') }}" class="btn-primary mt-6">
            + Create Post
        </a>
    </div>
@endforelse
