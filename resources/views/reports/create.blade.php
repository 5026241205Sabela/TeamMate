@extends('layouts.app')

@section('title', 'Report Form | TeamMate')

@section('content')
    <header class="page-header">
        <a href="{{ route('posts.show', $post->post_id) }}" class="text-xs font-semibold text-[#2160f5] hover:underline">
            &larr; Back to Post
        </a>
        <h1 class="mt-1">Report Form</h1>
        <p>Help keep TeamMate safe by reporting posts that violate our community guidelines.</p>
    </header>

    <div class="page-body">
        <div class="card mx-auto max-w-xl p-6 sm:p-8">
            <div class="mb-5 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                <span class="text-xs font-semibold text-slate-500 uppercase">Reporting post</span>
                <p class="mt-1 font-bold text-navy-900">{{ $post->title }}</p>
            </div>

            <x-form.alerts />

            <form action="{{ route('reports.store', $post->post_id) }}" method="POST" class="mt-4 space-y-5">
                @csrf

                <div>
                    <label for="reason" class="mb-1.5 block text-sm font-semibold text-navy-900">
                        Report Reason <span class="text-red-500">*</span>
                    </label>

                    <select name="reason" id="reason" required class="form-input">
                        <option value="">Select a reason...</option>
                        <option value="Spam or misleading" @selected(old('reason') == 'Spam or misleading')>
                            Spam or misleading
                        </option>
                        <option value="Inappropriate content" @selected(old('reason') == 'Inappropriate content')>
                            Inappropriate content
                        </option>
                        <option value="Harassment or bullying" @selected(old('reason') == 'Harassment or bullying')>
                            Harassment or bullying
                        </option>
                        <option value="Scam or fraud" @selected(old('reason') == 'Scam or fraud')>
                            Scam or fraud
                        </option>
                        <option value="Other" @selected(old('reason') == 'Other')>
                            Other
                        </option>
                    </select>

                    @error('reason')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-navy-900">
                        Description <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        placeholder="Explain why you are reporting this post..."
                        maxlength="2000"
                        required
                        class="form-input"
                    >{{ old('description') }}</textarea>

                    <p id="description-required-msg" class="mt-1.5 text-xs font-medium text-amber-700">
                        add a description first
                    </p>

                    @error('description')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <a href="{{ route('home') }}" class="btn">
                        Cancel
                    </a>

                    <button type="submit" id="submitBtn" disabled class="btn-primary disabled:cursor-not-allowed disabled:opacity-50">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const reasonInput = document.getElementById('reason');
        const descriptionInput = document.getElementById('description');
        const submitBtn = document.getElementById('submitBtn');
        const descriptionWarning = document.getElementById('description-required-msg');

        function syncReportFormState() {
            const hasDescription = descriptionInput.value.trim() !== '';
            const hasReason = reasonInput.value !== '';

            descriptionWarning.hidden = hasDescription;
            submitBtn.disabled = !(hasReason && hasDescription);
        }

        reasonInput?.addEventListener('change', syncReportFormState);
        descriptionInput?.addEventListener('input', syncReportFormState);

        syncReportFormState();
    </script>
@endpush
