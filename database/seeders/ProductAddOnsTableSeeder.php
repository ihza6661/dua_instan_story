<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductAddOnsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('product_add_ons')->delete();
        
        \DB::table('product_add_ons')->insert(array (
            0 => 
            array (
                'product_id' => 1,
                'add_on_id' => 1,
            ),
            1 => 
            array (
                'product_id' => 2,
                'add_on_id' => 1,
            ),
            2 => 
            array (
                'product_id' => 1,
                'add_on_id' => 2,
            ),
            3 => 
            array (
                'product_id' => 2,
                'add_on_id' => 2,
            ),
        ));
        
        
    }
}