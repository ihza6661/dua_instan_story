<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function getPaginatedActiveProducts(): LengthAwarePaginator
    {
        return Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->paginate(10);
    }

    public function findPubliclyVisibleProduct(int $productId): Product
    {
        return Product::with('category')
            ->where('is_active', true)
            ->findOrFail($productId);
    }
}
