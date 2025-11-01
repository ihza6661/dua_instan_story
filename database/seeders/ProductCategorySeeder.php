<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        ProductCategory::updateOrCreate([
            'slug' => Str::slug('Undangan Pernikahan'),
        ], [
            'name' => 'Undangan Pernikahan',
            'image' => 'category-images/wedding.jpg',
        ]);

        ProductCategory::updateOrCreate([
            'slug' => Str::slug('Buku Tamu'),
        ], [
            'name' => 'Buku Tamu',
            'image' => 'category-images/guestbook.jpg',
        ]);
    }
}
