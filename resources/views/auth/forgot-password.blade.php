@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <section class="px-4 py-12 sm:py-20">
        <div class="card mx-auto max-w-md p-6 text-center sm:p-10">
            <span class="mx-auto flex size-12 items-center justify-center rounded-xl bg-sky-100 text-navy-900">
                <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4" />
                </svg>
            </span>
            <h1 class="mt-5 text-2xl font-bold text-navy-900">Forgot Password</h1>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">
                Fitur reset password sedang dalam pengembangan dan akan tersedia setelah sistem akun TeamMate aktif.
            </p>
            <a href="{{ route('login') }}" class="btn-primary mt-8 w-full py-3">Back to Login</a>
        </div>
    </section>
@endsection

