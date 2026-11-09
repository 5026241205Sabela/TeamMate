<footer class="bg-navy-900 text-slate-300">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="flex size-9 items-center justify-center rounded-xl bg-sky-400 text-sm font-bold text-navy-900">TM</span>
                <span class="text-lg font-bold text-white">TeamMate</span>
            </div>
            <p class="mt-4 text-sm leading-relaxed">
                Platform yang membantu mahasiswa menemukan anggota tim untuk kompetisi, lomba, dan kegiatan akademik.
            </p>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Navigation</h3>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-sky-300">Home</a></li>
                <li><a href="{{ route('home') }}#about" class="hover:text-sky-300">About</a></li>
                <li><a href="{{ route('home') }}#features" class="hover:text-sky-300">Features</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Account</h3>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('login') }}" class="hover:text-sky-300">Login</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-sky-300">Register</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} TeamMate. All rights reserved.
    </div>
</footer>

