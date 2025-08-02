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

            $imageData = [
                'alt_text' => $altText,
                'is_featured' => isset($validated['is_featured_index']) && $validated['is_featured_index'] == $index,
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
