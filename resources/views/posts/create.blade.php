@extends('layouts.app')

@section('title', 'Create Competition Post | TeamMate')

@section('content')
    <header class="page-header">
        <h1>Create Competition Post</h1>
        <p>Fill in the competition details to recruit teammates.</p>
    </header>

    <div class="page-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Please check your input:</strong>
                <ul style="margin: 8px 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <section class="form-card">
                <div class="field-group">
                    <label class="field-label" for="title">
                        Competition Title <span class="required">*</span>
                    </label>
                    <input
                        class="form-control"
                        type="text"
                        id="title"
                        name="title"
                        maxlength="255"
                        placeholder="e.g. National Business Case Competition 2025"
                        value="{{ old('title') }}"
                        required
                    >
                    @error('title') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="category_id">
                        Competition Category <span class="required">*</span>
                    </label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Select a category...</option>
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->category_id }}"
                                @selected(old('category_id') == $category->category_id)
                            >
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($categories->isEmpty())
                        <div class="error-text">Belum ada kategori di database. Tambahkan kategori terlebih dahulu.</div>
                    @endif
                    @error('category_id') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="description">
                        Competition Description <span class="required">*</span>
                    </label>
                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        placeholder="Describe the competition, what you're looking for, and any important details..."
                        required
                    >{{ old('description') }}</textarea>
                    @error('description') <div class="error-text">{{ $message }}</div> @enderror
                </div>
            </section>

            <section class="form-card">
                <div class="field-group">
                    <label class="field-label" for="poster">
                        Competition Poster <span class="required">*</span>
                    </label>

                    <label class="upload-box" for="poster">
                        <span class="upload-icon">▧</span>
                        <span class="upload-main" id="upload-label">Click to upload image</span>
                        <span class="upload-help">PNG, JPG, WEBP — maximum 2 MB</span>
                        <input
                            class="file-input"
                            type="file"
                            id="poster"
                            name="poster"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            required
                        >
                    </label>

                    <img id="poster-preview" class="poster-preview" alt="Competition poster preview">
                    @error('poster') <div class="error-text">{{ $message }}</div> @enderror
                </div>
            </section>

            <section class="form-card">
                <div class="field-group">
                    <label class="field-label" for="member_criteria">Team Member Criteria</label>
                    <textarea
                        class="form-control"
                        id="member_criteria"
                        name="member_criteria"
                        placeholder="Describe the skills, qualifications, and commitments you expect from teammates..."
                    >{{ old('member_criteria') }}</textarea>
                    @error('member_criteria') <div class="error-text">{{ $message }}</div> @enderror
                </div>
            </section>

            <div class="form-actions">
                <a href="{{ route('home') }}" class="btn">Cancel</a>
                <button type="submit" class="btn btn-primary">Publish Post</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    const posterInput = document.getElementById('poster');
    const posterPreview = document.getElementById('poster-preview');
    const uploadLabel = document.getElementById('upload-label');

    posterInput.addEventListener('change', function () {
        const file = this.files && this.files[0];

        if (!file) {
            posterPreview.style.display = 'none';
            posterPreview.removeAttribute('src');
            uploadLabel.textContent = 'Click to upload image';
            return;
        }

        uploadLabel.textContent = file.name;

        if (file.type.startsWith('image/')) {
            posterPreview.src = URL.createObjectURL(file);
            posterPreview.style.display = 'block';
        }
    });
</script>
@endpush
