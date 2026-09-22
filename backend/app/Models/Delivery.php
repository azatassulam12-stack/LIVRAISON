<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    use HasUuids;

    protected $fillable = [
        'store_id', 'driver_id', 'customer_name', 'customer_phone', 'item_description', 'quantity',
        'amount_due', 'delivery_address', 'district', 'landmark', 'instructions', 'latitude', 'longitude',
        'scheduled_for', 'status', 'status_reason', 'collected_amount', 'payment_method', 'delivered_at',
        'tracking_token', 'tracking_expires_at', 'tracking_revoked_at',
    ];
    protected function casts(): array {
        return ['scheduled_for' => 'datetime', 'delivered_at' => 'datetime', 'tracking_expires_at' => 'datetime',
            'tracking_revoked_at' => 'datetime', 'amount_due' => 'decimal:2', 'collected_amount' => 'decimal:2'];
    }
    public function store(): BelongsTo { return $this->belongsTo(Store::class); }
    public function driver(): BelongsTo { return $this->belongsTo(User::class, 'driver_id'); }
    public function statusHistory(): HasMany { return $this->hasMany(DeliveryStatusHistory::class); }
    public function isTrackable(): bool { return ! $this->tracking_revoked_at && (! $this->tracking_expires_at || $this->tracking_expires_at->isFuture()); }
}
