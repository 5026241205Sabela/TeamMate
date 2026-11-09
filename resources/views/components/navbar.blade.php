<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="flex size-9 items-center justify-center rounded-xl bg-navy-900 text-sm font-bold text-white">TM</span>
            <span class="text-lg font-bold text-navy-900">TeamMate</span>
        </a>

        <input type="checkbox" id="nav-toggle" class="peer hidden">
        <label for="nav-toggle" class="cursor-pointer rounded-lg p-2 text-navy-900 hover:bg-slate-100 md:hidden" aria-label="Toggle navigation">
            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>

        <div class="absolute inset-x-0 top-full hidden flex-col gap-1 border-b border-slate-200 bg-white px-4 pb-4 peer-checked:flex md:static md:flex md:flex-row md:items-center md:gap-8 md:border-0 md:bg-transparent md:p-0">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('home') }}#about" class="nav-link">About</a>
            <a href="{{ route('home') }}#features" class="nav-link">Features</a>
            <a href="{{ route('login') }}" class="nav-link">Login</a>
            <a href="{{ route('register') }}" class="btn-primary mt-2 md:mt-0">Register</a>
        </div>
    </nav>
</header>

