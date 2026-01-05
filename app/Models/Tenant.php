<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'is_open'
    ];

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
}
