<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        ProductCategory::create([
            'name' => 'Undangan Pernikahan',
            'slug' => Str::slug('Undangan Pernikahan'),
        ]);

        ProductCategory::create([
            'name' => 'Buku Tamu',
            'slug' => Str::slug('Buku Tamu'),
        ]);
    }
}
