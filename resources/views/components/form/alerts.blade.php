<div {{ $attributes->class('space-y-3') }}>
    @if (session('success'))
        <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div role="status" class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-navy-800">
            {{ session('info') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            Terdapat {{ $errors->count() }} kesalahan pada form. Silakan periksa kembali input kamu.
        </div>
    @endif
</div>

