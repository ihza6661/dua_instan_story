<?php

namespace App\Services\Customer;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoryService
{
    public function getAllCategories(): Collection
    {
        return ProductCategory::whereHas('products', function ($query) {
            $query->where('is_active', true);
        })->latest()->get();
    }

    public function findCategoryById(int $id): ProductCategory
    {
        return ProductCategory::findOrFail($id);
    }
}
