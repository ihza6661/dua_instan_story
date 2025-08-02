<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCustomData extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_item_id',
        'form_data',
    ];

    protected function casts(): array
    {
        return [
            'form_data' => 'array',
        ];
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
