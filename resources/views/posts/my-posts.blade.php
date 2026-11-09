
@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
<header class="page-header">
    <h1>My Posts</h1>
    <p>Manage and update your competition posts.</p>
</header>

<section class="page-body">

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if ($posts->isEmpty())
        <div class="form-card" style="text-align: center;">
            <h3>No Posts Yet</h3>
            <p>You haven't created any competition posts.</p>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                Create Post
            </a>
        </div>
    @else

        @foreach ($posts as $post)
            <div class="form-card" style="display: flex; gap: 20px; align-items: center;">

                @if ($post->poster)
                    <img
                        src="{{ asset('storage/' . $post->poster) }}"
                        alt="Competition poster"
                        style="width: 110px; height: 110px; object-fit: cover; border-radius: 12px;"
                    >
                @else
                    <div style="width: 110px; height: 110px; background: #edf1f6; border-radius: 12px; display: grid; place-items: center;">
                        No Poster
                    </div>
                @endif

                <div style="flex: 1;">
                    <div style="color: #2563eb; font-size: 13px; margin-bottom: 7px;">
                        {{ $post->category?->category_name ?? 'Uncategorized' }}
                    </div>

                    <h3 style="margin: 0 0 8px;">
                        {{ $post->title }}
                    </h3>

                    <p style="margin: 0 0 8px; color: #71819b; font-size: 13px;">
                        {{ $post->date_created }}
                    </p>

                    <span style="font-size: 13px;">
                        Status:
                        <strong>
                            {{ match ($post->post_status) {
                                'active' => 'Still Looking',
                                'closed' => 'Done Accepting',
                                'completed' => 'Found a TeamMate',
                                default => ucfirst($post->post_status),
                            } }}
                        </strong>
                    </span>
                </div>

                <a
                    href="{{ route('posts.edit', $post->post_id) }}"
                    class="btn btn-primary"
                >
                    Edit
                </a>

            </div>
        @endforeach

    @endif

</section>
@endsection
