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
        for($i = 0; $i < 5; $i ++) {
            DB::table('products')->insert([
                'name' => "Viên Sủi Bổ Sung Vitamin C KINGDOMIN VITA C",
                'description' => "Viên sủi bổ sung vitamin C Kingdomin Vita C với hàm lượng Vitamin C cao, giúp bổ sung Vitamin C cho cơ thể,
                 tăng cường sức đề kháng, tăng sức bền thành mạch máu. Sản phẩm tan nhanh trong 1 phút giúp dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => 1,
                'original_price' => 30000,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => rand(0,1),
                'discount' => rand(10,90)
            ]);
        }
        for($i = 5; $i < 10; $i ++) {
            DB::table('products')->insert([
                'name' => "Viên Uống Hỗ Trợ Cải Thiện Đường Huyết DIALEVEL",
                'description' => "Viên sủi bổ sung vitamin C Kingdomin Vita C với hàm lượng Vitamin C cao, giúp bổ sung Vitamin C cho cơ thể,
                 tăng cường sức đề kháng, tăng sức bền thành mạch máu. Sản phẩm tan nhanh trong 1 phút giúp dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => 1,
                'original_price' => 35000,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => rand(0,1),
                'discount' => rand(10,90)
            ]);
        }
        for($i = 10; $i < 20; $i ++) {
            DB::table('products')->insert([
                'name' => "Alipas Platinum",
                'description' => "Sâm Alipas Platinum hỗ trợ tăng cường chức năng sinh lý cho nam giới. Đồng thời, nâng cao sức đề kháng, phòng chống bệnh tật. dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => 1,
                'original_price' => 35000,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => rand(0,1),
                'discount' => rand(10,90)
            ]);
        }
    }
}
