<footer class="border-t border-slate-200 bg-[#0b1528] text-slate-300">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-[1.4fr_1fr_1fr]">
        <div>
            <a href="{{ auth()->check() ? route('home') : route('landing') }}" aria-label="TeamMate Home">
                <x-logo theme="dark" />
            </a>
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-300">
                Platform kolaborasi terverifikasi KTM yang mempertemukan mahasiswa lintas jurusan untuk membentuk tim kompetisi dan riset akademik.
            </p>
        </div>

        <div>
            <h3 class="text-xs font-semibold tracking-wider text-sky-300 uppercase">Navigation</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('landing') }}" class="transition-colors hover:text-white">Home</a></li>
                <li><a href="{{ route('landing') }}#features" class="transition-colors hover:text-white">Features</a></li>
                <li><a href="{{ route('landing') }}#about" class="transition-colors hover:text-white">About</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-xs font-semibold tracking-wider text-sky-300 uppercase">Account</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                @auth
                    <li><a href="{{ route('home') }}" class="transition-colors hover:text-white">Home Page</a></li>
                    <li><a href="{{ route('posts.create') }}" class="transition-colors hover:text-white">Create Post</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="transition-colors hover:text-white">Login</a></li>
                    <li><a href="{{ route('register') }}" class="transition-colors hover:text-white">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} TeamMate. All rights reserved.
    </div>
</footer>
