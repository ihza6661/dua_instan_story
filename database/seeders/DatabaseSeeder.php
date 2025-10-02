<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductCategorySeeder::class,
            AddOnSeeder::class,
            AttributeSeeder::class,
            ProductSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
