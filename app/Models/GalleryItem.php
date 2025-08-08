<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'title',
        'description',
        'category',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
