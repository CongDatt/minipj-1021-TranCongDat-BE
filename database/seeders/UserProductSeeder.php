<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i < 5; $i++){
            DB::table('product_user')->insert([
                "user_id" => 2,
                "product_id" => $i,
            ]);
        }
        for($i = 5; $i < 10; $i++){
            DB::table('product_user')->insert([
                "user_id" => 3,
                "product_id" => $i,
            ]);
        }
        for($i = 10; $i < 15; $i++){
            DB::table('product_user')->insert([
                "user_id" => 4,
                "product_id" => $i,
            ]);
        }
    }
}
