<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_gateway',
        'amount',
        'payment_type',
        'status',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'raw_response' => 'array',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
