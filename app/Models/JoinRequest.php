<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JoinRequest extends Model
{
    protected $table = 'join_requests';

    protected $primaryKey = 'request_id';

    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'post_id',
        'request_date',
        'request_status',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function requirementFile(): HasOne
    {
        return $this->hasOne(RequirementFile::class, 'request_id', 'request_id');
    }
}
