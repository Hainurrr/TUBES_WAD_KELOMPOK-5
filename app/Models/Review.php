<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_user',
        'id_menu',
        'id_order',
        'rating',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function scopeForTenant(Builder $query, int $tenantId, ?string $menuName = null): Builder
    {
        $menuName = trim((string) $menuName);

        return $query->whereHas('menu', fn (Builder $q) => $q->where('id_tenant', $tenantId))
            ->with(['user', 'menu'])
            ->when($menuName !== '', function (Builder $q) use ($menuName) {
                $escaped = addcslashes($menuName, '%_\\');
                $q->whereHas('menu', fn (Builder $sq) => $sq->where('name', 'like', "%{$escaped}%"));
            })
            ->orderBy('created_at', 'desc');
    }

    public function scopeSearchAdmin(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);

        return $query->with(['user', 'menu.tenant'])
            ->when($search !== '', function (Builder $q) use ($search) {
                $escaped = addcslashes($search, '%_\\');
                $q->where(function (Builder $sub) use ($escaped) {
                    $sub->whereHas('menu', fn (Builder $sq) => $sq->where('name', 'like', "%{$escaped}%"))
                        ->orWhereHas('user', fn (Builder $uq) => $uq->where('name', 'like', "%{$escaped}%"));
                });
            })
            ->orderBy('created_at', 'desc');
    }
}
