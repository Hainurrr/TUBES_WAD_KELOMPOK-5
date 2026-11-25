<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'id_tenant',
        'id_category',
        'name',
        'price',
        'stock',
        'image',
        'description',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant', 'id_tenant');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_menu', 'id_menu');
    }

    public function scopeAvailableCatalog(Builder $query): Builder
    {
        return $query->with('tenant')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('is_available', true)
            ->whereHas('tenant', fn (Builder $q) => $q->where('is_open', true));
    }

    public function scopeFilterCatalog(Builder $query, array $filters): Builder
    {
        $tenantCategory = trim((string) ($filters['category'] ?? ''));
        $search = trim((string) ($filters['q'] ?? ''));
        $menuCategory = trim((string) ($filters['menu_category'] ?? ''));
        $sort = (string) ($filters['sort'] ?? 'newest');

        if ($tenantCategory !== '') {
            $query->whereHas('tenant', fn (Builder $q) => $q->where('category', $tenantCategory));
        }

        if ($search !== '') {
            $escaped = addcslashes($search, '%_\\');
            $query->where(function (Builder $sub) use ($escaped) {
                $sub->where('name', 'like', "%{$escaped}%")
                    ->orWhereHas('tenant', fn (Builder $tq) => $tq->where('name', 'like', "%{$escaped}%"));
            });
        }

        if ($menuCategory !== '') {
            $query->whereHas('category', fn (Builder $q) => $q->where('name', $menuCategory));
        }

        return match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderByDesc('reviews_avg_rating'),
            default => $query->latest(),
        };
    }
}
