<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    public const PLATFORM_ADMIN = 'platform_admin';
    public const STORE_ADMIN = 'store_admin';
    public const DRIVER = 'driver';

    protected $fillable = ['store_id', 'name', 'phone', 'email', 'password', 'role', 'is_active'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['password' => 'hashed', 'is_active' => 'boolean']; }

    public function store() { return $this->belongsTo(Store::class); }
    public function isStoreAdmin(): bool { return $this->role === self::STORE_ADMIN; }
    public function isDriver(): bool { return $this->role === self::DRIVER; }
}
