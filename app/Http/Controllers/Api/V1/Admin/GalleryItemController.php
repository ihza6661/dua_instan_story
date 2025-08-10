<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\GalleryItem\StoreRequest;
use App\Http\Requests\Api\V1\Admin\GalleryItem\UpdateRequest;
use App\Http\Resources\GalleryItemResource;
use App\Models\GalleryItem;
use App\Services\GalleryItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GalleryItemController extends Controller
{
    protected $galleryItemService;

    public function __construct(GalleryItemService $galleryItemService)
    {
        $this->galleryItemService = $galleryItemService;
    }

    public function index(): AnonymousResourceCollection
    {
        return GalleryItemResource::collection(GalleryItem::with('product')->latest()->get());
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $galleryItem = $this->galleryItemService->createItem($request->validated());

        return response()->json([
            'message' => 'Item galeri berhasil dibuat.',
            'data' => new GalleryItemResource($galleryItem),
        ], 201);
    }

    public function show(GalleryItem $galleryItem): GalleryItemResource
    {
        return new GalleryItemResource($galleryItem->load('product'));
    }

    public function update(UpdateRequest $request, GalleryItem $galleryItem): JsonResponse
    {
        $galleryItem = $this->galleryItemService->updateItem($galleryItem, $request->validated());

        return response()->json([
            'message' => 'Item galeri berhasil diperbarui.',
            'data' => new GalleryItemResource($galleryItem),
        ]);
    }

    public function destroy(GalleryItem $galleryItem): JsonResponse
    {
        $this->galleryItemService->deleteItem($galleryItem);

        return response()->json(['message' => 'Item galeri berhasil dihapus.']);
    }
}
