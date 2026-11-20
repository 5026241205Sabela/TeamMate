<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $table = 'posts';

    protected $primaryKey = 'post_id';

    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'category_id',
        'title',
        'description',
        'poster',
        'member_criteria',
        'post_status',
        'date_created',
        'status_change_time',
        'deletion_schedule',
    ];

    protected function casts(): array
    {
        return [
            'date_created' => 'datetime',
            'status_change_time' => 'datetime',
            'deletion_schedule' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(JoinRequest::class, 'post_id', 'post_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'post_id', 'post_id');
    }

    /**
     * Delete the post along with all related join requests, requirement files, reports, and stored files.
     */
    public function deleteWithRelations(): void
    {
        $filePaths = [];
        $posterPath = $this->poster;

        DB::transaction(function () use (&$filePaths, $posterPath): void {
            $joinRequests = $this->joinRequests()->with('requirementFile')->get();

            $filePaths = $joinRequests
                ->pluck('requirementFile.file_location')
                ->filter()
                ->values()
                ->all();

            if ($joinRequests->isNotEmpty()) {
                RequirementFile::query()
                    ->whereIn('request_id', $joinRequests->pluck('request_id'))
                    ->delete();
            }

            $this->joinRequests()->delete();
            $this->reports()->delete();
            $this->delete();

            DB::afterCommit(function () use ($filePaths, $posterPath): void {
                foreach ($filePaths as $path) {
                    Storage::disk('local')->delete($path);
                }

                if (filled($posterPath)) {
                    Storage::disk('public')->delete($posterPath);
                }
            });
        });
    }
}
