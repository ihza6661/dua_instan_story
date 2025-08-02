<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    public function addImageToProduct(Product $product, UploadedFile $imageFile, array $data): ProductImage
    {
        $path = $imageFile->store('product-images', 'public');

        return $product->images()->create([
            'image_url' => $path,
            'alt_text' => $data['alt_text'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
        ]);
    }

    public function deleteProductImage(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->image_url);
        $image->delete();
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
