<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_order';
    // MANPROSI_TELYUCAN_IMPROVEMENT - Menambahkan kolom Midtrans ke fillable
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
}
