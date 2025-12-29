<?php

namespace App\Models;

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
        'is_available' 
    ];

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
}

