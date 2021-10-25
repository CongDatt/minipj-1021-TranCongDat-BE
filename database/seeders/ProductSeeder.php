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
        for($i = 1; $i < 5; $i ++) {
            DB::table('products')->insert([
                'name' => "Viên Sủi Bổ Sung Vitamin C KINGDOMIN VITA C",
                'description' => "Viên sủi bổ sung vitamin C Kingdomin Vita C với hàm lượng Vitamin C cao, giúp bổ sung Vitamin C cho cơ thể,tăng cường sức đề kháng, tăng sức bền thành mạch máu. Sản phẩm tan nhanh trong 1 phút giúp dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => rand(1,3),
                'original_price' => 34500,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => 0,
                'discount' => 0
            ]);
        }
        for($i = 5; $i < 10; $i ++) {
            DB::table('products')->insert([
                'name' => "Viên Uống Hỗ Trợ Cải Thiện Đường Huyết DIALEVEL",
                'description' => "Viên sủi bổ sung vitamin C Kingdomin Vita C với hàm lượng Vitamin C cao, giúp bổ sung Vitamin C cho cơ thể,tăng cường sức đề kháng, tăng sức bền thành mạch máu. Sản phẩm tan nhanh trong 1 phút giúp dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => rand(1,3),
                'original_price' => 45600,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => 0,
                'discount' => rand(10,50)
            ]);
        }
        for($i = 10; $i < 19; $i ++) {
            DB::table('products')->insert([
                'name' => "Alipas Platinum",
                'description' => "Sâm Alipas Platinum hỗ trợ tăng cường chức năng sinh lý cho nam giới. Đồng thời, nâng cao sức đề kháng, phòng chống bệnh tật. dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => rand(1,3),
                'original_price' => 36500,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => 0,
                'discount' => rand(10,50)
            ]);
        }
        for($i = 19; $i < 30; $i ++) {
            DB::table('products')->insert([
                'name' => "Sữa rửa mặt cho nam siêu nổi bật",
                'description' => "Sâm Alipas Platinum hỗ trợ tăng cường chức năng sinh lý cho nam giới. Đồng thời, nâng cao sức đề kháng, phòng chống bệnh tật. dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => rand(1,3),
                'original_price' => 36500,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => 0,
                'discount' => rand(80,90)
            ]);
        }
        for($i = 30; $i < 39; $i ++) {
            DB::table('products')->insert([
                'name' => "Serium Sản phẩm hot",
                'description' => "Sâm Alipas Platinum hỗ trợ tăng cường chức năng sinh lý cho nam giới. Đồng thời, nâng cao sức đề kháng, phòng chống bệnh tật. dễ hấp thu vào cơ thể nhanh chóng, cho bạn một cơ thể khỏe mạnh.",
                'is_free_shipping' => rand(0,1),
                'category_id' => rand(1,3),
                'original_price' => 39900,
                'is_gift' => rand(0,1),
                'order_id' => rand(0,1),
                'is_hot' => 1,
                'discount' => rand(0,50)
            ]);
        }
    }
}
