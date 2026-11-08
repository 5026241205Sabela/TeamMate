<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $table = 'reports';

    protected $primaryKey = 'report_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

        protected $fillable = [
            'report_id',
            'post_id',
            'reason',
            'description',
            'report_date',
            'report_status',
         ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
