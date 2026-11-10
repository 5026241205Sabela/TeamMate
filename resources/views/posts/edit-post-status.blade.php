
@extends('layouts.app')

@section('title', 'Edit Post')

@push('styles')
<style>
    .edit-content {
        max-width: 900px;
        margin: 34px auto;
        padding: 0 30px;
    }

    .post-card,
    .status-card {
        background: white;
        border: 1px solid #edf1f6;
        border-radius: 17px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .post-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .post-image {
        width: 84px;
        height: 84px;
        border-radius: 12px;
        object-fit: cover;
        background: #e5eaf1;
        flex-shrink: 0;
    }

    .post-category {
        color: #2563eb;
        font-size: 13px;
        margin-bottom: 7px;
    }

    .post-title {
        font-size: 17px;
        font-weight: bold;
        margin-bottom: 7px;
    }

    .post-date {
        color: #94a3b8;
        font-size: 13px;
    }

    .status-card h2 {
        font-size: 15px;
        margin: 4px 0 18px;
    }

    .status-option {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px;
        border: 2px solid #e5ebf4;
        border-radius: 13px;
        margin-bottom: 12px;
        cursor: pointer;
    }

    .status-option.selected {
        border-color: #4388ff;
        background: #eff6ff;
    }

    .status-option input {
        width: 17px;
        height: 17px;
        accent-color: #1680ff;
        flex-shrink: 0;
    }

    .status-name {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .status-description {
        color: #8190a8;
        font-size: 13px;
    }

    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 22px;
    }

    .button {
        padding: 13px 24px;
        border-radius: 12px;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
    }

    .button.primary {
        background: #195cf5;
        color: white;
        border-color: #195cf5;
        min-width: 148px;
    }

    @media (max-width: 760px) {
        .edit-content {
            padding: 0 16px;
            margin-top: 22px;
        }

        .post-card,
        .status-card {
            padding: 16px;
        }

        .post-image {
            width: 64px;
            height: 64px;
        }

        .post-title {
            font-size: 14px;
        }

        .status-option {
            padding: 14px;
        }
    }
</style>
@endpush

@section('content')

<header class="page-header">
    <h1>Edit Post</h1>
    <p>Update the recruitment status for your post.</p>
</header>

<section class="edit-content">

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="post-card">
        <div class="post-info">

            @if ($post->poster)
                <img
                    class="post-image"
                    src="{{ asset('storage/' . $post->poster) }}"
                    alt="Competition poster"
                >
            @else
                <div class="post-image"></div>
            @endif

            <div>
                <div class="post-category">
                    {{ $post->category?->category_name ?? 'Uncategorized' }}
                </div>

                <div class="post-title">
                    {{ $post->title }}
                </div>

                <div class="post-date">
                    Created {{ $post->date_created }}
                </div>
            </div>

        </div>
    </div>

    <form
        class="status-card"
        method="POST"
        action="{{ route('posts.update-status', $post->post_id) }}"
    >
        @csrf
        @method('PATCH')

        <h2>Post Status</h2>

        @php
            $currentStatus = old('status', $post->post_status);
        @endphp

        <label class="status-option {{ $currentStatus === 'active' ? 'selected' : '' }}">
            <input
                type="radio"
                name="status"
                value="active"
                {{ $currentStatus === 'active' ? 'checked' : '' }}
                required
            >
            <div>
                <div class="status-name">Still Looking</div>
                <div class="status-description">
                    Your post remains visible and open for applications.
                </div>
            </div>
        </label>

        <label class="status-option {{ $currentStatus === 'closed' ? 'selected' : '' }}">
            <input
                type="radio"
                name="status"
                value="closed"
                {{ $currentStatus === 'closed' ? 'checked' : '' }}
            >
            <div>
                <div class="status-name">Done Accepting</div>
                <div class="status-description">
                    Stop receiving new applications while keeping the post visible.
                </div>
            </div>
        </label>

        <label class="status-option {{ $currentStatus === 'completed' ? 'selected' : '' }}">
            <input
                type="radio"
                name="status"
                value="completed"
                {{ $currentStatus === 'completed' ? 'checked' : '' }}
            >
            <div>
                <div class="status-name">Found a TeamMate</div>
                <div class="status-description">
                    Mark your team as complete. The post will be archived.
                </div>
            </div>
        </label>

        @error('status')
            <p style="color: #dc2626; font-size: 13px;">
                {{ $message }}
            </p>
        @enderror

        <div class="actions">
            <a href="{{ route('posts.index') }}" class="button">
                Cancel
            </a>

            <button type="submit" class="button primary">
                Save Changes
            </button>
        </div>
    </form>

</section>

@endsection

@push('scripts')
<script>
    const statusOptions = document.querySelectorAll('.status-option');

    statusOptions.forEach(option => {
        option.querySelector('input').addEventListener('change', () => {
            statusOptions.forEach(item => item.classList.remove('selected'));
            option.classList.add('selected');
        });
    });
</script>
@endpush
