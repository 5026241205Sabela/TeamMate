<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 backdrop-blur-md">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6">
        <a href="{{ auth()->check() ? route('home') : route('landing') }}" aria-label="TeamMate Home">
            <x-logo />
        </a>

        <input type="checkbox" id="nav-toggle" class="peer hidden">
        <label for="nav-toggle" class="cursor-pointer rounded-lg p-2 text-navy-900 hover:bg-slate-100 md:hidden" aria-label="Toggle navigation">
            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </label>

        <div class="absolute inset-x-0 top-full hidden flex-col gap-1 border-b border-slate-200 bg-white px-4 pb-4 peer-checked:flex md:static md:flex md:flex-row md:items-center md:gap-7 md:border-0 md:bg-transparent md:p-0">
            <a href="{{ route('landing') }}" class="nav-link">Home</a>
            <a href="{{ route('landing') }}#features" class="nav-link">Features</a>
            <a href="{{ route('landing') }}#about" class="nav-link">About</a>
            @auth
                <a href="{{ route('home') }}" class="nav-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2 md:mt-0">
                    @csrf
                    <button type="submit" class="btn-secondary w-full md:w-auto">Logout</button>
                </form>
            @else
                <span class="hidden h-4 w-px bg-slate-200 md:inline-block" aria-hidden="true"></span>
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="btn-primary mt-2 md:mt-0">Register</a>
            @endauth
        </div>
    </nav>
</header>
