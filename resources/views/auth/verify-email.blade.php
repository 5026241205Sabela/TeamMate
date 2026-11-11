@extends('layouts.guest')

@section('title', 'Status Verifikasi Akun')

@section('content')
    @php
        /** @var \App\Models\Account $account */
        $account = auth()->user();
    @endphp

    <section class="px-4 py-12 sm:py-16">
        <div class="card mx-auto max-w-xl p-6 sm:p-10">
            @if ($account->isPendingAdminVerification())
                <div class="text-center">
                    <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                        <svg class="size-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>

                    <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800 ring-1 ring-amber-200">
                        <span class="size-1.5 rounded-full bg-amber-500"></span>
                        Tahap 2 dari 3 &middot; Menunggu Verifikasi Admin
                    </span>

                    <h1 class="mt-3 text-2xl font-bold text-navy-900 sm:text-3xl">Menunggu Verifikasi Admin</h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Data pendaftaran dan foto KTM kamu sudah kami terima. Saat ini Admin sedang memeriksa keabsahan data kamu sebelum mengirimkan link aktivasi ke
                        <span class="font-semibold text-navy-900">{{ $account->email }}</span>.
                    </p>
                </div>

                <x-form.alerts class="mt-6 text-left" />

                {{-- 3-Step Verification Progress --}}
                <ol class="mt-6 space-y-3 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">&#10003;</span>
                        <div>
                            <p class="font-semibold text-navy-900">1. Pengisian Data Diri &amp; Unggah KTM</p>
                            <p class="text-xs text-slate-600">{{ $account->full_name }} &middot; {{ $account->university }} (NIM: {{ $account->nim }})</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-[#2160f5] text-xs font-bold text-white">2</span>
                        <div>
                            <p class="font-semibold text-navy-900">2. Pemeriksaan Data &amp; Foto KTM oleh Admin</p>
                            <p class="text-xs text-slate-600">Admin memvalidasi kecocokan identitas dan KTM. Jika valid, link verifikasi dikirim ke email; jika tidak valid, pesan penolakan dikirim ke email.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 opacity-60">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-slate-300 text-xs font-bold text-slate-700">3</span>
                        <div>
                            <p class="font-semibold text-navy-900">3. Klik Link Verifikasi di Email &amp; Masuk ke Homepage</p>
                            <p class="text-xs text-slate-600">Setelah menerima email verifikasi dari Admin, klik link di dalamnya untuk langsung masuk ke Home Page.</p>
                        </div>
                    </li>
                </ol>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('verification.notice') }}" class="btn-secondary w-full py-2.5 text-center sm:w-auto">
                        Perbarui Status
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-link text-sm">Keluar / Gunakan akun lain</button>
                    </form>
                </div>
            @elseif ($account->isRejectedByAdmin())
                <div class="text-center">
                    <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-red-100 text-red-700">
                        <svg class="size-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                        </svg>
                    </span>

                    <h1 class="mt-4 text-2xl font-bold text-navy-900 sm:text-3xl">Pendaftaran Ditolak</h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Maaf, hasil pemeriksaan Admin menyatakan data pendaftaran atau dokumen KTM untuk
                        <span class="font-semibold text-navy-900">{{ $account->email }}</span> belum valid.
                    </p>
                </div>

                @if ($account->rejection_reason)
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-900">
                        <p class="font-semibold">Alasan Penolakan dari Admin:</p>
                        <p class="mt-1 leading-relaxed">{{ $account->rejection_reason }}</p>
                    </div>
                @endif

                <div class="mt-6 space-y-3 text-center">
                    <a href="{{ route('landing', ['tab' => 'register']) }}" class="btn-primary block w-full py-3">
                        Daftar Ulang dengan Data yang Benar
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-link text-sm">Keluar ke Halaman Utama</button>
                    </form>
                </div>
            @else
                <div class="text-center">
                    <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-sky-100 text-navy-900">
                        <svg class="size-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                        </svg>
                    </span>

                    <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-200">
                        <span class="size-1.5 rounded-full bg-emerald-500"></span>
                        Data Disetujui Admin &middot; Tahap Aktivasi Email
                    </span>

                    <h1 class="mt-3 text-2xl font-bold text-navy-900 sm:text-3xl">Cek email kamu</h1>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Admin telah memverifikasi data dan KTM kamu! Kami mengirim link verifikasi ke
                        <span class="font-semibold text-navy-900">{{ $account->email }}</span>.
                        Klik link tersebut untuk mengaktifkan akun dan langsung masuk ke Home Page.
                    </p>
                </div>

                <x-form.alerts class="mt-6 text-left" />

                <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="btn-primary w-full py-3">Kirim ulang link verifikasi</button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
                    @csrf
                    <button type="submit" class="text-link text-sm">Gunakan akun lain</button>
                </form>
            @endif
        </div>
    </section>
@endsection
