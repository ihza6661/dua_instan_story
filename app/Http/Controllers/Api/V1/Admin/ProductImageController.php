<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ProductImage\StoreRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductImageController extends Controller
{
    public function store(StoreRequest $request, Product $product, ProductService $productService): JsonResponse
    {
        $validated = $request->validated();
        $uploadedImages = [];

        $existingImageCount = $product->images()->count();
        $existingHasFeatured = $product->images()->where('is_featured', true)->exists();

        if (isset($validated['is_featured_index'])) {
            $featuredIndex = (int) $validated['is_featured_index'];
        } else {
            $featuredIndex = $existingHasFeatured ? null : 0;
        }

        foreach ($validated['images'] as $index => $imageFile) {
            $providedAltText = $validated['alt_texts'][$index] ?? null;

            if (empty($providedAltText)) {
                $imageNumber = $existingImageCount + $index + 1;
                $altText = ($imageNumber === 1)
                    ? $product->name
                    : "{$product->name} - Gambar {$imageNumber}";
            } else {
                $altText = $providedAltText;
            }

            $isFeaturedForThisImage = ($featuredIndex !== null && $featuredIndex == $index);

            $imageData = [
                'alt_text' => $altText,
                'is_featured' => $isFeaturedForThisImage,
            ];

            $uploadedImages[] = $productService->addImageToProduct(
                $product,
                $imageFile,
                $imageData
            );
        }

        return response()->json([
            'message' => count($uploadedImages) . ' gambar berhasil diunggah.',
            'data' => ProductImageResource::collection($uploadedImages),
        ], 201);
    }

    public function destroy(ProductImage $image, ProductService $productService): JsonResponse
    {
        $productService->deleteProductImage($image);

        return response()->json([
            'message' => 'Gambar berhasil dihapus.',
        ]);
    }
}
