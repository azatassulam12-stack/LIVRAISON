<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'address', 'is_active'];

    public function users(): HasMany { return $this->hasMany(User::class); }
    public function deliveries(): HasMany { return $this->hasMany(Delivery::class); }
}
