@extends('layouts.guest')

@section('content')
    @php
        $openRegisterTab = request()->query('tab') === 'register'
            || $errors->hasAny(['name', 'phone', 'university', 'student_id', 'ktm', 'password_confirmation']);
    @endphp

    {{-- Hero + One-Page Login & Register Command Dock --}}
    <section id="home" class="relative border-b border-slate-200/80 bg-white">
        <div class="mx-auto grid max-w-6xl items-start gap-12 px-4 py-14 sm:px-6 lg:grid-cols-[1.08fr_0.92fr] lg:gap-14 lg:py-20">
            <div class="lg:sticky lg:top-24">
                <h1 class="max-w-xl text-4xl font-bold tracking-tight text-balance text-navy-900 sm:text-5xl lg:text-[3.25rem] lg:leading-[1.08]">
                    Find Your Perfect Teammates
                </h1>

                <p class="mt-5 max-w-[54ch] text-base leading-relaxed text-slate-600 sm:text-lg">
                    TeamMate membantu mahasiswa menemukan anggota tim yang tepat untuk mengikuti kompetisi,
                    lomba, dan kegiatan akademik berdasarkan minat dan keahlian.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" data-auth-switch="register" class="btn-primary px-6 py-3">
                        Get Started
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                    <a href="#features" class="btn-secondary px-6 py-3">Explore Opportunities</a>
                </div>

                {{-- Live Team Roster Blueprint --}}
                <div class="mt-10 rounded-2xl border border-slate-200 bg-[#f6f8fc] p-6">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200/80 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="size-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-semibold text-navy-900"> Contoh Lowongan Tim Aktif</span>
                        </div>
                        <span class="rounded-md bg-navy-900 px-2.5 py-1 text-[11px] font-semibold text-white">Teknologi</span>
                    </div>

                    <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                        <div>
                            <h2 class="text-lg font-bold text-navy-900">Hackathon Nasional 2026</h2>
                            <p class="mt-1 text-sm text-slate-600">Mencari 2 anggota: UI/UX Designer &amp; Backend Developer.</p>
                        </div>
                        <span class="shrink-0 font-mono text-xs font-semibold text-sky-700 tabular-nums">3 / 5 slot terisi</span>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach (['Laravel', 'Figma', 'System Design', 'Pitching'] as $skill)
                            <span class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-navy-800">{{ $skill }}</span>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center -space-x-2">
                                @foreach ([['SD', 'bg-navy-900'], ['BS', 'bg-sky-600'], ['RA', 'bg-navy-700']] as [$code, $bg])
                                    <span class="slot-pop flex size-8 items-center justify-center rounded-full border-2 border-white {{ $bg }} text-[11px] font-bold text-white" style="--i: {{ $loop->index }}">
                                        {{ $code }}
                                    </span>
                                @endforeach
                                @foreach (range(1, 2) as $open)
                                    <span class="slot-pop flex size-8 items-center justify-center rounded-full border-2 border-dashed border-sky-400 bg-white text-[11px] font-semibold text-sky-700" style="--i: {{ 2 + $open }}">
                                        +
                                    </span>
                                @endforeach
                            </div>

                            <button type="button" data-auth-switch="login" class="text-link cursor-pointer text-xs">
                                Masuk untuk melihat semua lowongan &rarr;
                            </button>
                        </div>

                        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-200">
                            <div class="slot-fill h-full rounded-full bg-sky-500" style="--fill: 0.6"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- One-page Login & Register Panel --}}
            <div id="auth-panel" class="card border-slate-200/90 p-6 sm:p-8" data-auth-tabs data-default-tab="{{ $openRegisterTab ? 'register' : 'login' }}">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <x-logo size="sm" />
                    <span class="rounded-md bg-navy-50 px-2.5 py-1 text-xs font-semibold text-navy-800">
                        Student Portal
                    </span>
                </div>

                <div role="tablist" aria-label="Pilih Masuk atau Daftar" class="mt-5 grid grid-cols-2 gap-1.5 rounded-xl bg-slate-100 p-1.5">
                    <button
                        type="button"
                        role="tab"
                        id="tab-login"
                        aria-controls="panel-login"
                        aria-selected="{{ $openRegisterTab ? 'false' : 'true' }}"
                        data-auth-tab="login"
                        class="cursor-pointer rounded-lg px-4 py-2.5 text-sm font-semibold transition {{ $openRegisterTab ? 'text-slate-600 hover:text-navy-900' : 'bg-white text-navy-900 shadow-sm' }}"
                    >
                        Login
                    </button>
                    <button
                        type="button"
                        role="tab"
                        id="tab-register"
                        aria-controls="panel-register"
                        aria-selected="{{ $openRegisterTab ? 'true' : 'false' }}"
                        data-auth-tab="register"
                        class="cursor-pointer rounded-lg px-4 py-2.5 text-sm font-semibold transition {{ $openRegisterTab ? 'bg-white text-navy-900 shadow-sm' : 'text-slate-600 hover:text-navy-900' }}"
                    >
                        Register
                    </button>
                </div>

                <x-form.alerts class="mt-5" />

                {{-- Login Form Panel --}}
                <div
                    id="panel-login"
                    role="tabpanel"
                    aria-labelledby="tab-login"
                    data-auth-panel="login"
                    @if ($openRegisterTab) hidden @endif
                    class="mt-6"
                >
                    <div>
                        <h2 class="text-xl font-bold text-navy-900">Masuk ke Akunmu</h2>
                        <p class="mt-1 text-sm text-slate-600">Masuk untuk langsung membuka Home Page dan mencari tim kompetisi.</p>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="mt-5 space-y-4" novalidate>
                        @csrf

                        <x-form.input name="email" label="Email Address" type="email" placeholder="sinta@test.com" autocomplete="email" required />
                        <x-form.input name="password" label="Password" type="password" placeholder="Masukkan password" autocomplete="current-password" required />

                        <div class="flex items-center justify-between text-sm">
                            <label for="remember" class="flex cursor-pointer items-center gap-2 text-slate-600">
                                <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember')) class="size-4 rounded border-slate-300 accent-navy-900">
                                Remember Me
                            </label>
                            <a href="{{ route('password.request') }}" class="text-link">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn-primary w-full py-3">
                            Masuk ke Home Page
                        </button>
                    </form>

                    <p class="mt-5 text-center text-sm text-slate-600">
                        Belum punya akun mahasiswa?
                        <button type="button" data-auth-switch="register" class="text-link cursor-pointer">Buat akun baru</button>
                    </p>
                </div>

                {{-- Register Form Panel --}}
                <div
                    id="panel-register"
                    role="tabpanel"
                    aria-labelledby="tab-register"
                    data-auth-panel="register"
                    @unless ($openRegisterTab) hidden @endunless
                    class="mt-6"
                >
                    <div>
                        <h2 class="text-xl font-bold text-navy-900">Buat Akun Mahasiswa</h2>
                        <p class="mt-1 text-sm text-slate-600">Lengkapi identitas kampus dan unggah KTM untuk mulai berkolaborasi.</p>
                    </div>

                    <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4" novalidate>
                        @csrf

                        <div class="grid gap-4 sm:grid-cols-2">
                            <x-form.input name="name" label="Full Name" placeholder="Budi Santoso" autocomplete="name" required />
                            <x-form.input name="email" label="Email Address" type="email" placeholder="you@example.com" autocomplete="email" required />
                            <x-form.input name="phone" label="Phone Number" type="tel" placeholder="081234567890" autocomplete="tel" required />
                            <x-form.input name="university" label="University" placeholder="Universitas Indonesia" required />
                            <x-form.input name="student_id" label="Student ID (NIM)" placeholder="2201234567" required />
                            <x-form.input name="ktm" label="KTM Upload" type="file" accept=".pdf,.jpg,.jpeg,.png" required>
                                <x-slot:hint>Format PDF, JPG, atau PNG. Maks. 2 MB.</x-slot:hint>
                            </x-form.input>
                            <x-form.input name="password" label="Password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required />
                            <x-form.input name="password_confirmation" label="Confirm Password" type="password" placeholder="Ulangi password" autocomplete="new-password" required />
                        </div>

                        <button type="submit" class="btn-primary w-full py-3">
                            Daftar &amp; Masuk ke Home Page
                        </button>
                    </form>

                    <p class="mt-5 text-center text-sm text-slate-600">
                        Sudah punya akun?
                        <button type="button" data-auth-switch="login" class="text-link cursor-pointer">Masuk sekarang</button>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Features (Editorial Hairline Ledger — no generic icon card grid) --}}
    <section id="features" class="scroll-mt-20 py-20">
        <div class="mx-auto grid max-w-6xl gap-12 px-4 sm:px-6 lg:grid-cols-[0.9fr_1.5fr]">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-navy-900">Features</h2>
                <p class="mt-3 max-w-sm leading-relaxed text-slate-600">
                    Dirancang spesifik untuk alur rekrutmen tim lomba mahasiswa, dari pencarian keahlian hingga seleksi berkas.
                </p>
            </div>

            @php
                $features = [
                    ['title' => 'Find Teammates', 'description' => 'Cari rekan satu tim berdasarkan keahlian spesifik, universitas, dan peran yang dibutuhkan dalam perlombaan.'],
                    ['title' => 'Join Competitions', 'description' => 'Telusuri lowongan kompetisi aktif di bidang Teknologi, Bisnis, Desain, dan Sosial yang siap diikuti.'],
                    ['title' => 'Build Your Team', 'description' => 'Publikasikan lowongan tim lengkap dengan poster, kriteria anggota, dan kelola status rekrutmen secara langsung.'],
                    ['title' => 'Connect with Students', 'description' => 'Berkolaborasi dengan mahasiswa terverifikasi KTM dari berbagai jurusan dan perguruan tinggi.'],
                ];
            @endphp

            <ul class="divide-y divide-slate-200 border-y border-slate-200 bg-white">
                @foreach ($features as $feature)
                    <li class="feature-row grid gap-2 px-5 py-6 sm:grid-cols-[13rem_1fr] sm:items-baseline sm:gap-8">
                        <h3 class="text-base font-bold text-navy-900">{{ $feature['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-slate-600">{{ $feature['description'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- About TeamMate --}}
    <section id="about" class="scroll-mt-20 border-t border-slate-200/80 bg-white py-20">
        <div class="mx-auto grid max-w-6xl items-start gap-12 px-4 sm:px-6 lg:grid-cols-[1.15fr_0.85fr]">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-navy-900">About TeamMate</h2>
                <p class="mt-4 max-w-[62ch] leading-relaxed text-slate-600">
                    Banyak mahasiswa memiliki ide dan semangat untuk berkompetisi, namun kesulitan menemukan rekan tim
                    dengan keahlian yang saling melengkapi. TeamMate hadir sebagai wadah untuk mempertemukan mahasiswa
                    lintas jurusan dan universitas agar dapat membentuk tim yang solid.
                </p>
                <p class="mt-4 max-w-[62ch] leading-relaxed text-slate-600">
                    Tujuan kami adalah mendorong kolaborasi, meningkatkan partisipasi mahasiswa dalam kompetisi, dan
                    membantu setiap mahasiswa berkembang bersama tim yang tepat.
                </p>
            </div>

            <dl class="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-[#f6f8fc]">
                @foreach ([
                    ['Kolaborasi Lintas Disiplin', 'Mempertemukan mahasiswa teknik, bisnis, desain, dan riset dalam satu tim.'],
                    ['Verifikasi Kartu Mahasiswa', 'Setiap pendaftar melampirkan NIM dan KTM untuk menjaga keamanan komunitas.'],
                    ['Manajemen Status Lowongan', 'Ketua tim dapat mengatur status Active, Closed, atau Completed kapan saja.'],
                ] as [$term, $detail])
                    <div class="p-5">
                        <dt class="text-sm font-bold text-navy-900">{{ $term }}</dt>
                        <dd class="mt-1 text-sm leading-relaxed text-slate-600">{{ $detail }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@endsection
