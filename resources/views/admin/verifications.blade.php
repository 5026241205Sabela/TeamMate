@extends('layouts.app')

@section('title', 'Verifikasi Pendaftaran & KTM | TeamMate Admin')

@section('content')
    <header class="page-header flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1>Verifikasi Pendaftaran &amp; KTM Mahasiswa</h1>
            <p>Periksa kecocokan identitas pendaftar dengan dokumen Kartu Tanda Mahasiswa (KTM) sebelum mengirim email aktivasi.</p>
        </div>

        <p class="text-xs font-medium text-slate-500 tabular-nums">
            Menampilkan <strong class="font-bold text-navy-900">{{ $accounts->count() }}</strong> dari <strong class="font-bold text-navy-900">{{ $totalStudents }}</strong> pendaftar mahasiswa
        </p>
    </header>

    <div class="page-body space-y-6">
        @if (session('success') || session('info') || session('error') || $errors->any())
            <x-form.alerts />
        @endif

        {{-- Unified Queue Filter Tabs + Search Bar --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                {{-- Status Queue Tabs --}}
                <nav aria-label="Filter status verifikasi" class="flex flex-wrap items-center gap-1.5 rounded-xl bg-slate-100 p-1.5">
                    @php
                        $tabs = [
                            ['key' => 'all', 'label' => 'Semua Pendaftar', 'count' => $totalStudents],
                            ['key' => 'pending', 'label' => 'Menunggu Review', 'count' => $pendingCount],
                            ['key' => 'approved', 'label' => 'Disetujui', 'count' => $approvedCount],
                            ['key' => 'rejected', 'label' => 'Ditolak', 'count' => $rejectedCount],
                        ];
                    @endphp

                    @foreach ($tabs as $tab)
                        @php
                            $isActive = $statusFilter === $tab['key'];
                        @endphp
                        <a
                            href="{{ route('admin.verifications.index', array_filter(['status' => $tab['key'], 'q' => $keyword])) }}"
                            class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-xs font-semibold transition {{ $isActive ? 'bg-white text-navy-900 shadow-2xs' : 'text-slate-600 hover:text-navy-900' }}"
                        >
                            <span>{{ $tab['label'] }}</span>
                            <span class="rounded-md px-1.5 py-0.5 font-mono text-[11px] font-bold tabular-nums {{ $isActive ? 'bg-[#2160f5] text-white' : 'bg-slate-200/80 text-slate-700' }}">
                                {{ $tab['count'] }}
                            </span>
                        </a>
                    @endforeach
                </nav>

                {{-- Search Form --}}
                <form method="GET" action="{{ route('admin.verifications.index') }}" class="flex flex-1 items-center gap-2 lg:max-w-md">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <label for="admin-search" class="sr-only">Cari nama, NIM, email, atau universitas</label>
                    <input
                        id="admin-search"
                        name="q"
                        type="search"
                        value="{{ $keyword }}"
                        placeholder="Cari nama, NIM, universitas, atau email..."
                        class="form-input py-2"
                    >
                    <button type="submit" class="btn btn-primary shrink-0 px-4 py-2 text-xs">
                        Cari
                    </button>
                    @if ($keyword !== '' || $statusFilter !== 'all')
                        <a href="{{ route('admin.verifications.index') }}" class="btn shrink-0 px-3.5 py-2 text-xs">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Applicant Verification Sheets --}}
        <div class="space-y-4">
            @forelse ($accounts as $student)
                @php
                    $status = $student->verification_status ?? 'approved';
                    $ktmUrl = route('admin.verifications.ktm', $student->account_id);
                    $isPdf = str_ends_with(strtolower((string) $student->ktm), '.pdf');
                @endphp

                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="grid lg:grid-cols-[17.5rem_1fr] lg:divide-x lg:divide-slate-200">
                        {{-- Left Column: KTM Document Surface --}}
                        <div class="flex flex-col justify-between bg-slate-50/70 p-5">
                            <div>
                                <div class="mb-2.5 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-600">Lampiran KTM</span>
                                    <span class="font-mono text-[11px] font-semibold text-slate-500 uppercase">
                                        {{ $isPdf ? 'PDF' : 'IMG' }}
                                    </span>
                                </div>

                                @if ($isPdf)
                                    <a
                                        href="{{ $ktmUrl }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="flex h-36 flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-slate-300 bg-white p-4 text-center transition hover:border-[#2160f5]"
                                    >
                                        <svg class="size-8 text-[#2160f5]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-navy-900">Buka Berkas PDF KTM</span>
                                    </a>
                                @else
                                    <a
                                        href="{{ $ktmUrl }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="group block h-36 overflow-hidden rounded-xl border border-slate-200 bg-navy-900"
                                    >
                                        <img
                                            src="{{ $ktmUrl }}"
                                            alt="KTM {{ $student->full_name }}"
                                            class="size-full object-cover transition duration-200 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                    </a>
                                @endif
                            </div>

                            <a
                                href="{{ $ktmUrl }}"
                                target="_blank"
                                rel="noopener"
                                class="mt-3 inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-[#2160f5] transition hover:border-sky-300 hover:bg-navy-50"
                            >
                                <span>Periksa Dokumen KTM Penuh</span>
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M9 7h8v8" />
                                </svg>
                            </a>
                        </div>

                        {{-- Right Column: Submitted Identity Ledger & Verification Controls --}}
                        <div class="flex flex-col justify-between p-5 sm:p-6">
                            <div>
                                {{-- Header Row: Name + Status Badges --}}
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <h2 class="text-lg font-bold text-navy-900">{{ $student->full_name }}</h2>
                                        <p class="mt-0.5 text-sm text-slate-600">{{ $student->university ?: 'Universitas belum diisi' }}</p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        @if ($status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-900">
                                                <span class="size-1.5 rounded-full bg-amber-600"></span>
                                                Menunggu Review Admin
                                            </span>
                                        @elseif ($status === 'rejected')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-800">
                                                <span class="size-1.5 rounded-full bg-red-600"></span>
                                                Ditolak (Data Tidak Valid)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-900">
                                                <span class="size-1.5 rounded-full bg-emerald-600"></span>
                                                Disetujui Admin
                                            </span>
                                        @endif

                                        @if ($student->hasVerifiedEmail())
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-900">
                                                Email Terverifikasi
                                            </span>
                                        @elseif ($status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                Menunggu Klik Link Email
                                            </span>
                                        @elseif ($status === 'rejected')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700">
                                                Email Penolakan Terkirim
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                Belum Dikirim Email
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Hairline Identity Data Comparison Strip --}}
                                <dl class="mt-4 grid gap-4 border-y border-slate-100 py-3.5 text-xs sm:grid-cols-3">
                                    <div>
                                        <dt class="font-semibold text-slate-500">NIM Mahasiswa</dt>
                                        <dd class="mt-1 font-mono text-sm font-bold text-navy-900 tabular-nums">{{ $student->nim ?: '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-semibold text-slate-500">Email Pendaftar</dt>
                                        <dd class="mt-1 font-semibold text-navy-900 break-all">{{ $student->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-semibold text-slate-500">Nomor Telepon</dt>
                                        <dd class="mt-1 font-mono font-semibold text-navy-900 tabular-nums">{{ $student->phone_number ?: '-' }}</dd>
                                    </div>
                                </dl>

                                @if ($student->rejection_reason)
                                    <p class="mt-3 text-xs leading-relaxed text-red-700">
                                        <strong class="font-semibold text-red-900">Catatan Penolakan Admin:</strong> {{ $student->rejection_reason }}
                                    </p>
                                @endif
                            </div>

                            {{-- Decision Action Bar --}}
                            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 pt-1">
                                <form method="POST" action="{{ route('admin.verifications.approve', $student->account_id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-primary gap-2 text-xs">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5" />
                                        </svg>
                                        <span>
                                            @if ($status === 'approved' && ! $student->hasVerifiedEmail())
                                                Kirim Ulang Email Verifikasi
                                            @else
                                                Valid &mdash; Setujui &amp; Kirim Email Verifikasi
                                            @endif
                                        </span>
                                    </button>
                                </form>

                                <details class="group">
                                    <summary class="btn cursor-pointer list-none gap-1.5 border-red-200 bg-red-50/70 text-xs font-semibold text-red-700 hover:bg-red-100">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12" />
                                        </svg>
                                        <span>Tidak Valid &mdash; Tolak &amp; Kirim Email Penolakan</span>
                                    </summary>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.verifications.reject', $student->account_id) }}"
                                        class="mt-3 space-y-3 border-t border-slate-200 pt-3 sm:w-96"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label for="reject-reason-{{ $student->account_id }}" class="block text-xs font-bold text-navy-900">
                                                Alasan Penolakan (Dikirim ke Email Pendaftar) *
                                            </label>
                                            <div class="mt-1.5 flex flex-wrap gap-1.5">
                                                @foreach ([
                                                    'Foto KTM buram atau tidak terbaca dengan jelas.',
                                                    'NIM pada formulir tidak sesuai dengan foto KTM.',
                                                    'Nama atau universitas tidak sesuai dengan dokumen KTM.',
                                                ] as $preset)
                                                    <button
                                                        type="button"
                                                        onclick="document.getElementById('reject-reason-{{ $student->account_id }}').value = @js($preset)"
                                                        class="cursor-pointer rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-medium text-slate-700 transition hover:border-red-300 hover:bg-red-50 hover:text-red-800"
                                                    >
                                                        {{ \Illuminate\Support\Str::limit($preset, 32) }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            <textarea
                                                id="reject-reason-{{ $student->account_id }}"
                                                name="rejection_reason"
                                                rows="3"
                                                required
                                                placeholder="Tuliskan alasan ketidakvalidan data atau KTM..."
                                                class="form-input mt-2 text-xs"
                                            >{{ old('rejection_reason', $student->rejection_reason ?: 'Data diri atau foto KTM tidak valid / tidak terbaca dengan jelas.') }}</textarea>
                                        </div>

                                        <div class="flex justify-end">
                                            <button
                                                type="submit"
                                                class="cursor-pointer rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700"
                                            >
                                                Kirim Email Penolakan
                                            </button>
                                        </div>
                                    </form>
                                </details>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
                    <p class="text-base font-bold text-navy-900">Tidak ada pendaftar pada antrean ini</p>
                    <p class="mt-1 text-sm text-slate-600">Pilih tab status lain atau ubah kata kunci pencarian untuk meninjau pendaftar lainnya.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
