<?php

namespace Database\Seeders;

use App\Models\AddOn;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data yang diperlukan dari seeder lain
        $undanganCategory = ProductCategory::where('slug', 'undangan-pernikahan')->first();
        $denah = AddOn::where('name', 'Denah Lokasi')->first();
        $kupon = AddOn::where('name', 'Kupon Souvenir')->first();

        $softcoverValue = AttributeValue::where('value', 'Softcover')->first();
        $hardcoverValue = AttributeValue::where('value', 'Hardcover')->first();
        $cleanCutValue = AttributeValue::where('value', 'Clean Cut')->first();
        $rawEdgesValue = AttributeValue::where('value', 'Raw Edges')->first();
        $ukuran10x19 = AttributeValue::where('value', '10 x 19 cm')->first();
        $ukuran14x20 = AttributeValue::where('value', '14 x 20 cm')->first();

        // Produk 1: SS.001 (dari PDF Hal. 5)
        $product1 = Product::create([
            'category_id' => $undanganCategory->id,
            'name' => 'Undangan Softcover SS.001',
            'description' => 'Undangan tipe softcover dengan bahan Linen white 200 gsm.',
            'base_price' => 3200, // Harga untuk 100-199 pcs
            'min_order_quantity' => 50,
        ]);

        // Menambahkan opsi ke Produk 1
        $product1->options()->create([
            'attribute_value_id' => $softcoverValue->id,
            'price_adjustment' => 0, // Harga dasar sudah softcover
        ]);
        $product1->options()->create([
            'attribute_value_id' => $hardcoverValue->id,
            'price_adjustment' => 1200, // Tambahan biaya hardcover
        ]);
        $product1->options()->create([
            'attribute_value_id' => $ukuran10x19->id,
            'price_adjustment' => 0,
        ]);

        // Menambahkan add-ons ke Produk 1
        $product1->addOns()->attach([$denah->id, $kupon->id]);


        // Produk 2: SS.002 (dari PDF Hal. 6)
        $product2 = Product::create([
            'category_id' => $undanganCategory->id,
            'name' => 'Undangan Elegan SS.002',
            'description' => 'Undangan dengan pilihan finishing Clean Cut atau Raw Edges.',
            'base_price' => 5000, // Harga Clean Cut untuk 100-199 pcs
            'min_order_quantity' => 50,
        ]);

        // Menambahkan opsi ke Produk 2
        $product2->options()->create([
            'attribute_value_id' => $cleanCutValue->id,
            'price_adjustment' => 0,
        ]);
        $product2->options()->create([
            'attribute_value_id' => $rawEdgesValue->id,
            'price_adjustment' => 200, // Tambahan biaya Raw Edges
        ]);
        $product2->options()->create([
            'attribute_value_id' => $ukuran14x20->id,
            'price_adjustment' => 0,
        ]);

        // Menambahkan add-ons ke Produk 2
        $product2->addOns()->attach([$denah->id, $kupon->id]);
    }
}
