<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 20; $i ++) {
            DB::table('products')->insert([
                'name' => Str::random(10),
                'description' => Str::random(10),
                'is_free_shipping' => rand(0,1),
                'category_id' => rand(0,1),
                'original_price' => rand(1000,10000),
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => rand(0,1),
                'discount' => rand(10,90)
            ]);
        }
    }
}
