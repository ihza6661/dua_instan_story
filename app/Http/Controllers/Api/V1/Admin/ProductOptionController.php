<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ProductOption\StoreRequest;
use App\Http\Requests\Api\V1\Admin\ProductOption\UpdateRequest;
use App\Http\Resources\ProductOptionResource;
use App\Models\Product;
use App\Models\ProductOption;
use App\Services\ProductOptionService;
use Illuminate\Http\JsonResponse;

class ProductOptionController extends Controller
{
    protected $productOptionService;

    public function __construct(ProductOptionService $productOptionService)
    {
        $this->productOptionService = $productOptionService;
    }

    public function store(StoreRequest $request, Product $product): JsonResponse
    {
        $option = $this->productOptionService->createOption($product, $request->validated());
        return response()->json([
            'message' => 'Opsi produk berhasil ditambahkan.',
            'data' => new ProductOptionResource($option->load('attributeValue')),
        ], 201);
    }

    public function update(UpdateRequest $request, ProductOption $option): JsonResponse
    {
        $option = $this->productOptionService->updateOption($option, $request->validated());
        return response()->json([
            'message' => 'Opsi produk berhasil diperbarui.',
            'data' => new ProductOptionResource($option->load('attributeValue')),
        ]);
    }

    public function destroy(ProductOption $option): JsonResponse
    {
        $this->productOptionService->deleteOption($option);
        return response()->json(['message' => 'Opsi produk berhasil dihapus.']);
    }
}
