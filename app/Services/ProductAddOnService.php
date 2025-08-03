<?php

namespace App\Services;

use App\Models\AddOn;
use App\Models\Product;

class ProductAddOnService
{
    public function attachAddOn(Product $product, array $data): void
    {
        $product->addOns()->attach($data['add_on_id']);
    }

    public function detachAddOn(Product $product, AddOn $addOn): void
    {
        $product->addOns()->detach($addOn->id);
    }
}
