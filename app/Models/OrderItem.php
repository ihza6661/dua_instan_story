<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'sub_total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function meta()
    {
        return $this->hasMany(OrderItemMeta::class);
    }

    public function customData()
    {
        return $this->hasOne(OrderCustomData::class);
    }

    public function designProofs()
    {
        return $this->hasMany(DesignProof::class);
    }
}
