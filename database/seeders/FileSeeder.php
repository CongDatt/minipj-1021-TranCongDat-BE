<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i < 5; $i++){
            DB::table('files')->insert([
                "file_name" => 'img of product',
                "file_path" => 'https://phanolink.s3.ap-southeast-1.amazonaws.com/images_dat/zDTAgrdb4gXi3JBu0MYZLW0qf1lXER8KfTx7Il0G.png',
                'disk' => 's3',
                'file_size' => 3942,
                'fileable_type' => 'App\Models\Product',
                'fileable_id' => $i,
            ]);
        }
        for($i = 5; $i < 10; $i++){
            DB::table('files')->insert([
                "file_name" => 'img of product',
                "file_path" => 'https://phanolink.s3.ap-southeast-1.amazonaws.com/images_dat/DrOidx9PlZlNPeCdYzZ1QOfnaA7Mc5FAPKIBZvab.png',
                'disk' => 's3',
                'file_size' => 3941,
                'fileable_type' => 'App\Models\Product',
                'fileable_id' => $i,
            ]);
        }
        for($i = 10; $i < 15; $i++){
            DB::table('files')->insert([
                "file_name" => 'img of product',
                "file_path" => 'https://phanolink.s3.ap-southeast-1.amazonaws.com/images_dat/r7eCutMCC17i4rV8WVRk7mO9jBsTb9KqG1B1oDrk.png',
                'disk' => 's3',
                'file_size' => 3942,
                'fileable_type' => 'App\Models\Product',
                'fileable_id' => $i,
            ]);
        }
        for($i = 15; $i < 20; $i++){
            DB::table('files')->insert([
                "file_name" => 'img of product',
                "file_path" => 'https://phanolink.s3.ap-southeast-1.amazonaws.com/images_dat/yWIsTDKVl6RjyNCVziXU5c7k6YxvBFDzB5sa63Oi.jpg',
                'disk' => 's3',
                'file_size' => 3942,
                'fileable_type' => 'App\Models\Product',
                'fileable_id' => $i,
            ]);
        }
        for($i = 1; $i <= 5; $i++){
            DB::table('files')->insert([
                "file_name" => 'img of slide',
                "file_path" => 'https://phanolink.s3.ap-southeast-1.amazonaws.com/images_dat/nsvd5YJ4NDjxHbDEGYq6GHRcbyXcrotqdOPO7790.png',
                'disk' => 's3',
                'file_size' => 3942,
                'fileable_type' => 'App\Models\Slide',
                'fileable_id' => $i,
            ]);
        }
    }
}
