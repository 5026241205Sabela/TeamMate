@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
    <section class="px-4 py-12 sm:py-20">
        <div class="card mx-auto max-w-md p-6 text-center sm:p-10">
            <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-sky-100 text-navy-900">
                <svg class="size-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                </svg>
            </span>

            <h1 class="mt-5 text-2xl font-bold text-navy-900 sm:text-3xl">Cek email kamu</h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Kami mengirim link verifikasi ke
                <span class="font-semibold text-navy-900">{{ auth()->user()->email }}</span>.
                Klik link tersebut untuk mengaktifkan akun.
            </p>

            <x-form.alerts class="mt-6 text-left" />

            <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
                @csrf
                <button type="submit" class="btn-primary w-full py-3">Kirim ulang link verifikasi</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="text-link text-sm">Gunakan akun lain</button>
            </form>
        </div>
    </section>
@endsection

