@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <section class="px-4 py-12 sm:py-20">
        <div class="card mx-auto max-w-md p-6 sm:p-10">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-navy-900 sm:text-3xl">Welcome back</h1>
                <p class="mt-2 text-sm text-slate-600">Masuk untuk melanjutkan ke TeamMate.</p>
            </div>

            <x-form.alerts class="mt-6" />

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5" novalidate>
                @csrf

                <x-form.input name="email" label="Email Address" type="email" placeholder="you@example.com" autocomplete="email" required autofocus />
                <x-form.input name="password" label="Password" type="password" placeholder="Masukkan password" autocomplete="current-password" required />

                <div class="flex items-center justify-between text-sm">
                    <label for="remember" class="flex cursor-pointer items-center gap-2 text-slate-600">
                        <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember')) class="size-4 rounded border-slate-300 accent-navy-900">
                        Remember Me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-primary w-full py-3">Login</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-link">Register</a>
            </p>
        </div>
    </section>
@endsection

