@extends('layouts.app')

@section('title', 'Apply Join Request | TeamMate')

@section('content')
    @php
        $categoryName = $post->category?->category_name ?? 'Uncategorized';
        $authorName = $post->account?->full_name ?? 'TeamMate Student';
    @endphp

    <header class="page-header">
        <a href="{{ route('posts.show', $post->post_id) }}" class="text-xs font-semibold text-[#2160f5] hover:underline">
            &larr; Back to Post Details
        </a>
        <h1 class="mt-1">Join Request Form</h1>
        <p>Submit your application and requirement document (CV / portfolio) to join this team.</p>
    </header>

    <div class="page-body space-y-6">
        <x-form.alerts />

        <div class="card space-y-3 p-6">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="rounded-md bg-navy-900 px-2.5 py-1 text-xs font-semibold text-white">
                    {{ $categoryName }}
                </span>
                <span class="text-xs text-slate-500">Diposting oleh <strong class="text-navy-900">{{ $authorName }}</strong></span>
            </div>

            <h2 class="text-lg font-bold text-navy-900">{{ $post->title }}</h2>

            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-xs leading-relaxed text-slate-700">
                <strong class="font-semibold text-navy-900">Team Criteria:</strong>
                <p class="mt-1">{{ $post->member_criteria ?: 'Open to committed students from any major.' }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('join-requests.store', $post->post_id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <section class="form-card">
                <h2 class="mb-4 text-base font-bold text-navy-900">Applicant Identity</h2>

                <dl class="grid gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase">Full Name</dt>
                        <dd class="mt-1 font-semibold text-navy-900">{{ $account->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase">NIM</dt>
                        <dd class="mt-1 font-mono font-semibold text-navy-900">{{ $account->nim ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase">University</dt>
                        <dd class="mt-1 font-semibold text-navy-900">{{ $account->university ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase">Email</dt>
                        <dd class="mt-1 font-semibold text-navy-900">{{ $account->email }}</dd>
                    </div>
                </dl>
            </section>

            <section class="form-card">
                <div class="field-group">
                    <label class="field-label" for="requirement_file">
                        Requirement Document (CV / Portfolio) <span class="required">*</span>
                    </label>

                    <label class="upload-box" for="requirement_file">
                        <span class="upload-icon">&#128196;</span>
                        <span class="upload-main" id="requirement-upload-label">Click to upload requirement file</span>
                        <span class="upload-help">PDF, DOC, DOCX, JPG, or PNG &mdash; maximum 5 MB</span>
                        <input
                            class="file-input"
                            type="file"
                            id="requirement_file"
                            name="requirement_file"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            required
                        >
                    </label>

                    @error('requirement_file')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </section>

            <div class="form-actions">
                <a href="{{ route('home') }}" class="btn">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const fileInput = document.getElementById('requirement_file');
        const fileLabel = document.getElementById('requirement-upload-label');

        fileInput?.addEventListener('change', function () {
            const file = this.files && this.files[0];
            fileLabel.textContent = file ? file.name : 'Click to upload requirement file';
        });
    </script>
@endpush
