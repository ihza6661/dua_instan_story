<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'category',
        'file_path',
        'media_type',
    ];

    protected $appends = ['file_url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }

    protected static function booted(): void
    {
        static::deleting(function (GalleryItem $galleryItem) {
            if ($galleryItem->file_path) {
                Storage::disk('public')->delete($galleryItem->file_path);
            }
        });
    }
}
