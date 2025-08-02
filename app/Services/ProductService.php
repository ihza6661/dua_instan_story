<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function createProduct(array $validatedData): Product
    {
        return Product::create($validatedData);
    }

    public function updateProduct(Product $product, array $validatedData): Product
    {
        $product->update($validatedData);
        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        // Nanti bisa ditambahkan pengecekan, misal:
        // if ($product->orderItems()->exists()) {
        //     throw new \Exception('Produk tidak dapat dihapus karena sudah ada dalam pesanan.');
        // }
        $product->delete();
    }
}
