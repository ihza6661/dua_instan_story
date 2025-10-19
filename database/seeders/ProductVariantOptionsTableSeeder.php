<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductVariantOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('product_variant_options')->delete();
        
        \DB::table('product_variant_options')->insert(array (
            0 => 
            array (
                'product_variant_id' => 1,
                'attribute_value_id' => 1,
            ),
            1 => 
            array (
                'product_variant_id' => 2,
                'attribute_value_id' => 2,
            ),
            2 => 
            array (
                'product_variant_id' => 3,
                'attribute_value_id' => 3,
            ),
            3 => 
            array (
                'product_variant_id' => 5,
                'attribute_value_id' => 3,
            ),
            4 => 
            array (
                'product_variant_id' => 4,
                'attribute_value_id' => 4,
            ),
            5 => 
            array (
                'product_variant_id' => 6,
                'attribute_value_id' => 4,
            ),
            6 => 
            array (
                'product_variant_id' => 1,
                'attribute_value_id' => 5,
            ),
            7 => 
            array (
                'product_variant_id' => 2,
                'attribute_value_id' => 5,
            ),
            8 => 
            array (
                'product_variant_id' => 5,
                'attribute_value_id' => 5,
            ),
            9 => 
            array (
                'product_variant_id' => 6,
                'attribute_value_id' => 5,
            ),
            10 => 
            array (
                'product_variant_id' => 3,
                'attribute_value_id' => 6,
            ),
            11 => 
            array (
                'product_variant_id' => 4,
                'attribute_value_id' => 6,
            ),
        ));
        
        
    }
}