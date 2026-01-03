<?php

namespace App\Models;

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
}
