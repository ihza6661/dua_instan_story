<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::with('variants.images')->get();

        foreach ($products as $product) {
            if ($product->variants->flatMap->images->isEmpty()) {
                $productImages = $this->getProductImagesForProduct($product->name);

                foreach ($productImages as $image) {
                    ProductImage::create([
                        'product_variant_id' => $product->variants->first()->id,
                        'image' => 'product-images/' . $image,
                        'alt_text' => $product->name,
                        'is_featured' => true,
                    ]);
                }
            }
        }
    }

    private function getProductImagesForProduct(string $productName): array
    {
        $productImageMap = [
            'Blue on Blue' => ['blue-on-blue/1.jpg', 'blue-on-blue/2.jpg', 'blue-on-blue/3.jpg'],
            'Alice in Wonderland' => ['alice-wonderland-theme/1.jpg', 'alice-wonderland-theme/2.jpg', 'alice-wonderland-theme/3.jpg'],
            'Tema Jawa Modern' => ['tema-jawa-modern/1.jpg', 'tema-jawa-modern/2.jpg', 'tema-jawa-modern/3.jpg'],
            'Fairy Tale' => ['fairy-tale/1.jpg', 'fairy-tale/2.jpg', 'fairy-tale/3.jpg'],
        ];

        return $productImageMap[$productName] ?? [];
    }
}
