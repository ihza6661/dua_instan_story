<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ProductCategory\StoreRequest;
use App\Http\Requests\Api\V1\Admin\ProductCategory\UpdateRequest;
use App\Http\Resources\AdminProductCategoryResource;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return AdminProductCategoryResource::collection(ProductCategory::latest()->get());
    }

    public function store(StoreRequest $request, ProductCategoryService $categoryService): JsonResponse
    {
        $category = $categoryService->createCategory($request->validated());

        return response()->json([
            'message' => 'Kategori produk berhasil dibuat.',
            'data' => new AdminProductCategoryResource($category),
        ], 201);
    }

    public function show(ProductCategory $productCategory): AdminProductCategoryResource
    {
        return new AdminProductCategoryResource($productCategory);
    }

    public function update(UpdateRequest $request, ProductCategory $productCategory, ProductCategoryService $categoryService): JsonResponse
    {
        $updatedCategory = $categoryService->updateCategory($productCategory, $request->validated());

        return response()->json([
            'message' => 'Kategori produk berhasil diperbarui.',
            'data' => new AdminProductCategoryResource($updatedCategory),
        ]);
    }

    public function destroy(ProductCategory $productCategory): JsonResponse
    {
        if ($productCategory->products()->exists()) {
            return response()->json([
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.',
            ], 409);
        }

        $productCategory->delete();

        return response()->json([
            'message' => 'Kategori produk berhasil dihapus.',
        ]);
    }
}
