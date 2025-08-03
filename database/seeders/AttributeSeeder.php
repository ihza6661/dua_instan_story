<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Atribut untuk Tipe Undangan (Softcover, Hardcover, dll)
        $tipeCetak = Attribute::create(['name' => 'Tipe Cetak']);
        $tipeCetak->attributeValues()->createMany([
            ['value' => 'Softcover'],
            ['value' => 'Hardcover'],
            ['value' => 'Clean Cut'],
            ['value' => 'Raw Edges'],
        ]);

        // Atribut untuk Ukuran
        $ukuran = Attribute::create(['name' => 'Ukuran']);
        $ukuran->attributeValues()->createMany([
            ['value' => '10 x 19 cm'],
            ['value' => '14 x 20 cm'],
            ['value' => '13 x 19 cm'],
        ]);
    }
}
