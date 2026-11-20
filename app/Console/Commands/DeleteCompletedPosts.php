<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class DeleteCompletedPosts extends Command
{
    protected $signature = 'posts:delete-completed';

    protected $description = 'Delete completed posts after their scheduled deletion time';

    public function handle(): int
    {
        $posts = Post::where('post_status', 'completed')
            ->whereNotNull('deletion_schedule')
            ->where('deletion_schedule', '<=', now())
            ->get();

        $deleted = 0;

        foreach ($posts as $post) {
            $post->deleteWithRelations();
            $deleted++;
        }

        $this->info("{$deleted} completed post(s) deleted.");

        return self::SUCCESS;
    }
}
