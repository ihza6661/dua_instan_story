<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemMeta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_item_id',
        'meta_key',
        'meta_value',
        'meta_price',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
