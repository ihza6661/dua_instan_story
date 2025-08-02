<?php

namespace App\Services;

use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductCategoryService
{
    public function createCategory(array $data): ProductCategory
    {
        $data['slug'] = Str::slug($data['name']);

        return ProductCategory::create($data);
    }

    public function updateCategory(ProductCategory $category, array $data): ProductCategory
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        return $category;
    }
}
