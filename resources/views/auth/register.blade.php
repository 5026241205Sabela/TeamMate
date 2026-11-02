@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <section class="px-4 py-12 sm:py-16">
        <div class="card mx-auto max-w-2xl p-6 sm:p-10">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-navy-900 sm:text-3xl">Create your account</h1>
                <p class="mt-2 text-sm text-slate-600">Bergabung dengan TeamMate dan mulai temukan rekan timmu.</p>
            </div>

            <x-form.alerts class="mt-6" />

            <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data" class="mt-8 space-y-5" novalidate>
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.input name="name" label="Full Name" placeholder="John Doe" autocomplete="name" required />
                    <x-form.input name="email" label="Email Address" type="email" placeholder="you@example.com" autocomplete="email" required />
                    <x-form.input name="phone" label="Phone Number" type="tel" placeholder="081234567890" autocomplete="tel" required />
                    <x-form.input name="university" label="University" placeholder="Universitas Indonesia" required />
                    <x-form.input name="student_id" label="Student ID (NIM)" placeholder="2201234567" required />
                    <x-form.input name="ktm" label="KTM Upload" type="file" accept=".pdf,.jpg,.jpeg,.png" required>
                        <x-slot:hint>Format PDF, JPG, atau PNG. Maksimal 2 MB.</x-slot:hint>
                    </x-form.input>
                    <x-form.input name="password" label="Password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required />
                    <x-form.input name="password_confirmation" label="Confirm Password" type="password" placeholder="Ulangi password" autocomplete="new-password" required />
                </div>

                <button type="submit" class="btn-primary w-full py-3">Register</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-link">Login</a>
            </p>
        </div>
    </section>
@endsection

