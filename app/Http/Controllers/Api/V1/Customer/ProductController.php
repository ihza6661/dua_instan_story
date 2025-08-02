<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): AnonymousResourceCollection
    {
        $products = $this->productService->getPaginatedActiveProducts();
        return ProductResource::collection($products);
    }

    public function show(string $id): ProductResource
    {
        $product = $this->productService->findPubliclyVisibleProduct((int)$id);
        return new ProductResource($product);
    }
}
