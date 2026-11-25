<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Tenant extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tenant';

    protected $fillable = [
        'id_user',
        'name',
        'category',
        'description',
        'owner_name',
        'phone',
        'address',
        'city',
        'banner_image',
        'is_active',
        'is_open',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_open' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_tenant', 'id_tenant');
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Menu::class, 'id_tenant', 'id_menu', 'id_tenant', 'id_menu');
    }

    public function scopeWithReviewStats(Builder $query): Builder
    {
        return $query->withAvg('reviews', 'rating')->withCount('reviews');
    }

    public function scopeFilterDirectory(Builder $query, ?string $category = null, ?string $location = null): Builder
    {
        $category = trim((string) $category);
        $location = trim((string) $location);

        if ($category !== '') {
            return $query->where('category', $category);
        }

        if ($location !== '') {
            $escaped = addcslashes($location, '%_\\');
            return $query->where(function (Builder $q) use ($escaped) {
                $q->where('address', 'like', "%{$escaped}%")
                    ->orWhere('name', 'like', "%{$escaped}%")
                    ->orWhere('city', 'like', "%{$escaped}%")
                    ->orWhere('category', 'like', "%{$escaped}%");
            });
        }

        return $query;
    }

    public static function distinctCategories(): Collection
    {
        return static::whereNotNull('category')->distinct()->pluck('category');
    }
}
