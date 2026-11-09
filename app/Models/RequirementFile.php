<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequirementFile extends Model
{
    protected $table = 'requirement_files';

    protected $primaryKey = 'file_id';

    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'file_name',
        'file_type',
        'file_location',
        'upload_date',
    ];

    public function joinRequest(): BelongsTo
    {
        return $this->belongsTo(
            JoinRequest::class,
            'request_id',
            'request_id'
        );
    }
}
