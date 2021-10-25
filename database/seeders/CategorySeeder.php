<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            "category_name" => "Thuốc Tây Ninh",
            "slug" => "thuoc-tay-ninh"
        ]);
        DB::table('categories')->insert([
            "category_name" => "Thuốc Tây",
            "slug" => "thuoc-tay"
        ]);
        DB::table('categories')->insert([
            "category_name" => "Thuốc Cảm Cúm",
            "slug" => "thuoc-cam-cum"
        ]);
    }
}
