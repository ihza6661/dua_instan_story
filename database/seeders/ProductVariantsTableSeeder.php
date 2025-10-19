<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductVariantsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('product_variants')->delete();
        
        \DB::table('product_variants')->insert(array (
            0 => 
            array (
                'id' => 1,
                'product_id' => 1,
                'price' => 1500,
                'stock' => 0,
                'created_at' => '2025-10-10 12:00:47',
                'updated_at' => '2025-10-11 13:19:02',
            ),
            1 => 
            array (
                'id' => 2,
                'product_id' => 1,
                'price' => 2000,
                'stock' => 0,
                'created_at' => '2025-10-10 12:00:47',
                'updated_at' => '2025-10-11 13:19:30',
            ),
            2 => 
            array (
                'id' => 3,
                'product_id' => 2,
                'price' => 2000,
                'stock' => 500,
                'created_at' => '2025-10-10 12:00:47',
                'updated_at' => '2025-10-11 13:23:18',
            ),
            3 => 
            array (
                'id' => 4,
                'product_id' => 2,
                'price' => 2500,
                'stock' => 500,
                'created_at' => '2025-10-10 12:00:48',
                'updated_at' => '2025-10-11 13:23:37',
            ),
            4 => 
            array (
                'id' => 5,
                'product_id' => 3,
                'price' => 2500,
                'stock' => 0,
                'created_at' => '2025-10-11 13:28:21',
                'updated_at' => '2025-10-11 13:28:21',
            ),
            5 => 
            array (
                'id' => 6,
                'product_id' => 4,
                'price' => 2000,
                'stock' => 0,
                'created_at' => '2025-10-11 13:29:48',
                'updated_at' => '2025-10-11 13:29:48',
            ),
        ));
        
        
    }
}