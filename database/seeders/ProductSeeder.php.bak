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
        $undanganCategory = ProductCategory::where('slug', 'undangan-pernikahan')->first();
        $denah = AddOn::where('name', 'Denah Lokasi')->first();
        $kupon = AddOn::where('name', 'Kupon Souvenir')->first();

        $softcoverValue = AttributeValue::where('value', 'Softcover')->first();
        $hardcoverValue = AttributeValue::where('value', 'Hardcover')->first();
        $cleanCutValue = AttributeValue::where('value', 'Clean Cut')->first();
        $rawEdgesValue = AttributeValue::where('value', 'Raw Edges')->first();
        $ukuran10x19 = AttributeValue::where('value', '10 x 19 cm')->first();
        $ukuran14x20 = AttributeValue::where('value', '14 x 20 cm')->first();

        // ===============================================================
        // Produk 1: SS.001
        // ===============================================================
        $product1 = Product::create([
            'category_id' => $undanganCategory->id,
            'name' => 'Undangan Softcover SS.001',
            'description' => 'Undangan tipe softcover dengan bahan Linen white 200 gsm.',
            'base_price' => 3200,
            'min_order_quantity' => 50,
        ]);

        // Varian 1.1: Softcover, Ukuran 10x19 cm
        $variant1_1 = $product1->variants()->create([
            'price' => 3200, // Harga dasar
        ]);
        $variant1_1->options()->attach([$softcoverValue->id, $ukuran10x19->id]);

        // Varian 1.2: Hardcover, Ukuran 10x19 cm
        $variant1_2 = $product1->variants()->create([
            'price' => 4400, // Harga dasar + 1200
        ]);
        $variant1_2->options()->attach([$hardcoverValue->id, $ukuran10x19->id]);

        // Menambahkan add-ons ke Produk 1
        $product1->addOns()->attach([$denah->id, $kupon->id]);


        // ===============================================================
        // Produk 2: SS.002
        // ===============================================================
        $product2 = Product::create([
            'category_id' => $undanganCategory->id,
            'name' => 'Undangan Elegan SS.002',
            'description' => 'Undangan dengan pilihan finishing Clean Cut atau Raw Edges.',
            'base_price' => 5000,
            'min_order_quantity' => 50,
        ]);

        // Varian 2.1: Clean Cut, Ukuran 14x20 cm
        $variant2_1 = $product2->variants()->create([
            'price' => 5000, // Harga dasar
        ]);
        $variant2_1->options()->attach([$cleanCutValue->id, $ukuran14x20->id]);

        // Varian 2.2: Raw Edges, Ukuran 14x20 cm
        $variant2_2 = $product2->variants()->create([
            'price' => 5200, // Harga dasar + 200
        ]);
        $variant2_2->options()->attach([$rawEdgesValue->id, $ukuran14x20->id]);

        // Menambahkan add-ons ke Produk 2
        $product2->addOns()->attach([$denah->id, $kupon->id]);
    }
}
