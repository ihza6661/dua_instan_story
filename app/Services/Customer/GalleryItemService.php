<?php

namespace App\Services\Customer;

use App\Models\GalleryItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GalleryItemService
{
    public function getPaginatedItems(?string $category = null): LengthAwarePaginator
    {
        return GalleryItem::with('product')
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(12);
    }

    public function findItemById(int $id): GalleryItem
    {
        return GalleryItem::findOrFail($id);
    }
}
