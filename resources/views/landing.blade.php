@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section id="home" class="relative overflow-hidden bg-gradient-to-b from-white to-sky-50">
        <div class="pointer-events-none absolute -top-24 -right-24 size-96 rounded-full bg-sky-200/40 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-6xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:py-28">
            <div>
                <span class="inline-block rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-navy-800">
                    Platform kolaborasi mahasiswa
                </span>
                <h1 class="mt-5 text-4xl font-bold tracking-tight text-navy-900 sm:text-5xl">
                    Find Your Perfect Teammates
                </h1>
                <p class="mt-5 max-w-xl text-lg leading-relaxed text-slate-600">
                    TeamMate membantu mahasiswa menemukan anggota tim yang tepat untuk mengikuti kompetisi,
                    lomba, dan kegiatan akademik berdasarkan minat dan keahlian.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="btn-primary px-6 py-3">Get Started</a>
                    <a href="#features" class="btn-secondary px-6 py-3">Explore Opportunities</a>
                </div>
            </div>

            <div class="card hidden p-8 lg:block">
                <p class="text-sm font-semibold text-slate-500">Open team</p>
                <h3 class="mt-1 text-xl font-bold text-navy-900">Hackathon Nasional 2026</h3>
                <p class="mt-2 text-sm text-slate-600">Mencari 2 anggota: UI/UX Designer &amp; Backend Developer.</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach (['Laravel', 'Figma', 'Public Speaking'] as $skill)
                        <span class="rounded-full bg-navy-50 px-3 py-1 text-xs font-medium text-navy-800">{{ $skill }}</span>
                    @endforeach
                </div>
                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
                    <div class="flex -space-x-2">
                        @foreach (['bg-navy-900', 'bg-sky-400', 'bg-navy-600'] as $color)
                            <span class="size-9 rounded-full border-2 border-white {{ $color }}"></span>
                        @endforeach
                    </div>
                    <span class="text-xs font-medium text-sky-600">3/5 members</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="scroll-mt-20 py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold text-navy-900">Features</h2>
                <p class="mt-3 text-slate-600">Semua yang kamu butuhkan untuk membangun tim yang solid.</p>
            </div>

            @php
                $features = [
                    ['title' => 'Find Teammates', 'description' => 'Cari mahasiswa dengan keahlian yang sesuai kebutuhan timmu.', 'icon' => 'M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z'],
                    ['title' => 'Join Competitions', 'description' => 'Temukan dan ikuti berbagai kompetisi serta lomba akademik.', 'icon' => 'M8 21h8M12 17v4M7 4h10v5a5 5 0 0 1-10 0V4z'],
                    ['title' => 'Build Your Team', 'description' => 'Bentuk tim, atur peran anggota, dan mulai berkolaborasi.', 'icon' => 'M17 20h5v-2a4 4 0 0 0-5-3.87M9 20H2v-2a4 4 0 0 1 7-2.65M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z'],
                    ['title' => 'Connect with Students', 'description' => 'Perluas jaringan dengan mahasiswa dari berbagai universitas.', 'icon' => 'M13.8 10.2a4 4 0 0 0-5.6 0l-3 3a4 4 0 0 0 5.6 5.6l1-1M10.2 13.8a4 4 0 0 0 5.6 0l3-3a4 4 0 0 0-5.6-5.6l-1 1'],
                ];
            @endphp

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($features as $feature)
                    <div class="card transition hover:-translate-y-1 hover:border-sky-200 hover:shadow-md">
                        <span class="flex size-12 items-center justify-center rounded-xl bg-sky-100 text-navy-900">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                            </svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-navy-900">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- About --}}
    <section id="about" class="scroll-mt-20 bg-white py-20">
        <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2">
            <div>
                <h2 class="text-3xl font-bold text-navy-900">About TeamMate</h2>
                <p class="mt-4 leading-relaxed text-slate-600">
                    Banyak mahasiswa memiliki ide dan semangat untuk berkompetisi, namun kesulitan menemukan rekan tim
                    dengan keahlian yang saling melengkapi. TeamMate hadir sebagai wadah untuk mempertemukan mahasiswa
                    lintas jurusan dan universitas agar dapat membentuk tim yang solid.
                </p>
                <p class="mt-4 leading-relaxed text-slate-600">
                    Tujuan kami adalah mendorong kolaborasi, meningkatkan partisipasi mahasiswa dalam kompetisi, dan
                    membantu setiap mahasiswa berkembang bersama tim yang tepat.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach ([['Kolaborasi', 'Lintas jurusan'], ['Kompetisi', 'Akademik & non-akademik'], ['Terverifikasi', 'Akun dengan KTM'], ['Mudah', 'Dirancang untuk mahasiswa']] as [$heading, $caption])
                    <div class="rounded-2xl bg-navy-50 p-6">
                        <p class="text-lg font-bold text-navy-900">{{ $heading }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $caption }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

