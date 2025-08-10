<?php

namespace App\Services\Customer;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

class ProductCategoryService
{
    public function getAllCategories(): Collection
    {
        return ProductCategory::all();
    }

    public function findCategoryById(int $id): ProductCategory
    {
        return ProductCategory::findOrFail($id);
    }
}
