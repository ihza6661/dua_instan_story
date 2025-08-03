<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductOption;

class ProductOptionService
{
    public function createOption(Product $product, array $data): ProductOption
    {
        return $product->options()->create($data);
    }

    public function updateOption(ProductOption $option, array $data): ProductOption
    {
        $option->update($data);
        return $option;
    }

    public function deleteOption(ProductOption $option): void
    {
        $option->delete();
    }
}
