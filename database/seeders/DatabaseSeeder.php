<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminUserSeeder::class,
            ProductCategorySeeder::class,
            AddOnSeeder::class,
            AttributeSeeder::class,

            // Seeders generated from current database
            ProductsTableSeeder::class,
            ProductVariantsTableSeeder::class,
            ProductAddOnsTableSeeder::class,
            ProductVariantOptionsTableSeeder::class,
        ]);
        $this->call(ProductsTableSeeder::class);
        $this->call(ProductVariantsTableSeeder::class);
        $this->call(ProductAddOnsTableSeeder::class);
        $this->call(ProductVariantOptionsTableSeeder::class);
    }
}
