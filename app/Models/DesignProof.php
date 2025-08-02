<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'version',
        'file_url',
        'status',
        'customer_feedback',
        'admin_notes',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
