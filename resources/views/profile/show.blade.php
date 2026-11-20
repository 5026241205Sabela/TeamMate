@extends('layouts.app')

@section('title', 'Profile | TeamMate')

@section('content')
    @php
        $account = auth()->user();
    @endphp

    <header class="page-header">
        <h1>Student Profile</h1>
        <p>Verified academic identity and account status.</p>
    </header>

    <div class="page-body">
        <div class="card max-w-2xl space-y-6 p-6 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h2 class="text-xl font-bold text-navy-900">{{ $account->full_name }}</h2>
                    <p class="text-sm text-slate-600">{{ $account->university ?: 'Universitas belum diisi' }}</p>
                </div>

                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-900">
                    <span class="size-2 rounded-full bg-emerald-600"></span>
                    Akun Aktif &amp; Terverifikasi
                </span>
            </div>

            <dl class="grid gap-5 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Email Address</dt>
                    <dd class="mt-1 font-semibold text-navy-900">{{ $account->email }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold tracking-wide text-slate-500 uppercase">NIM Mahasiswa</dt>
                    <dd class="mt-1 font-mono font-semibold text-navy-900">{{ $account->nim ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Phone Number</dt>
                    <dd class="mt-1 font-mono font-semibold text-navy-900">{{ $account->phone_number ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Email Verified At</dt>
                    <dd class="mt-1 font-semibold text-navy-900">
                        {{ $account->email_verified_at ? $account->email_verified_at->format('d M Y, H:i') : '-' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
