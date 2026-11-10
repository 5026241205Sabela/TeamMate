<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\RoutesNotifications;

class Account extends Authenticatable implements MustVerifyEmail
{
    use RoutesNotifications;

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

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
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
