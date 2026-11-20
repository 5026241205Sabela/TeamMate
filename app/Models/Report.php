<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasUuids;

    protected $table = 'reports';

    protected $primaryKey = 'report_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'reason',
        'description',
        'report_date',
        'report_status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'report_date' => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
