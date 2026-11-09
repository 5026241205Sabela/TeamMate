<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Authenticatable
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
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

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
