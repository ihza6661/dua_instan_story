<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant as ModelProductVariant;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

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
        if ($product->orderItems()->exists() || $product->cartItems()->exists()) {
            throw new Exception('Produk tidak dapat dihapus karena sudah ada dalam pesanan atau keranjang belanja pelanggan.');
        }

        $product->load('variants.images');

        foreach ($product->variants as $variant) {
            foreach ($variant->images as $image) {
                $this->deleteProductImage($image);
            }
        }

        $product->delete();
    }

    public function addImageToVariant(ModelProductVariant $variant, UploadedFile $imageFile, array $data): ProductImage
    {
        // Ensure the product-images directory exists
        $directory = 'product-images';
        $disk = Storage::disk('public');
        
        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory, 0777, true);
        }
        
        $path = $imageFile->store($directory, 'public');

        if (!empty($data['is_featured'])) {
            $variant->images()->where('is_featured', true)->update(['is_featured' => false]);
        }

        return $variant->images()->create([
            'image' => $path,
            'alt_text' => $data['alt_text'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
        ]);
    }

    public function deleteProductImage(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();
    }

    public function getPaginatedActiveProducts(?string $searchTerm = null, ?string $categorySlug = null, ?string $minPrice = null, ?string $maxPrice = null, ?string $sort = 'latest'): LengthAwarePaginator
    {
        $query = Product::with(['category', 'variants.images'])
            ->where('is_active', true)
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            })
            ->when($categorySlug, function ($query, $categorySlug) {
                $query->whereHas('category', function ($subQuery) use ($categorySlug) {
                    $subQuery->where('slug', $categorySlug);
                });
            })
            ->when($minPrice, function ($query, $minPrice) {
                $query->whereHas('variants', function ($subQuery) use ($minPrice) {
                    $subQuery->where('price', '>=', $minPrice);
                });
            })
            ->when($maxPrice, function ($query, $maxPrice) {
                $query->whereHas('variants', function ($subQuery) use ($maxPrice) {
                    $subQuery->where('price', '<=', $maxPrice);
                });
            });

        switch ($sort) {
            case 'price_asc':
            case 'price_desc':
                $query->addSelect([
                    'min_price' => ModelProductVariant::selectRaw('MIN(price)')
                        ->whereColumn('product_id', 'products.id')
                        ->take(1)
                ])->orderBy('min_price', $sort === 'price_asc' ? 'asc' : 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(10);
    }

    public function findPubliclyVisibleProduct(int $productId): Product
    {
        return Product::with(['category', 'variants.images'])
            ->where('is_active', true)
            ->findOrFail($productId);
    }
}
