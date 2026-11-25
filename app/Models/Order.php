<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_COOKING = 'cooking';
    public const STATUS_READY = 'ready';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const ACTIVE_STATUSES = [
        self::STATUS_PENDING,
        'process',
        self::STATUS_COOKING,
        self::STATUS_READY,
        'shipping',
    ];

    public const PAST_STATUSES = [
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    public const KITCHEN_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_COOKING,
        self::STATUS_READY,
    ];

    public const PAYMENT_UNPAID = 'unpaid';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_EXPIRED = 'expired';
    public const PAYMENT_CANCELLED = 'cancelled';

    protected $primaryKey = 'id_order';

    protected $fillable = [
        'id_user',
        'id_tenant',
        'transaction_group',
        'total_price',
        'status',
        'snap_token',
        'midtrans_transaction_id',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant', 'id_tenant');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_order', 'id_order');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, self::PAST_STATUSES, true);
    }

    public function scopeOwnedBy(Builder $query, int $userId): Builder
    {
        return $query->where('id_user', $userId);
    }

    public function scopeForKitchen(Builder $query, int $tenantId): Builder
    {
        return $query->with(['user', 'orderDetails.menu'])
            ->where('id_tenant', $tenantId)
            ->whereIn('status', self::KITCHEN_STATUSES)
            ->where('payment_status', self::PAYMENT_PAID)
            ->orderBy('created_at', 'asc');
    }

    public function scopeCompletedForTenant(
        Builder $query,
        int $tenantId,
        ?string $startDate = null,
        ?string $endDate = null
    ): Builder {
        return $query->with(['user', 'orderDetails.menu'])
            ->where('id_tenant', $tenantId)
            ->where('status', self::STATUS_COMPLETED)
            ->when($startDate, fn (Builder $q, string $date) => $q->whereDate('created_at', '>=', $date))
            ->when($endDate, fn (Builder $q, string $date) => $q->whereDate('created_at', '<=', $date))
            ->orderBy('created_at', 'desc');
    }
}
