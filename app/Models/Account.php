<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $table = 'accounts';

    protected $primaryKey = 'account_id';

    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'university',
        'nim',
        'ktm',
        'password',
        'role',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'account_id', 'account_id');
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(JoinRequest::class, 'account_id', 'account_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'account_id', 'account_id');
    }
}
