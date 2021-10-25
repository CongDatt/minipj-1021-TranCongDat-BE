<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 10; $i++){
            DB::table('users')->insert([
                'name' => "Cong Dat". Str::random(3),
                'email' => "user".$i."@mail.com",
                'password' => Hash::make(123456),
                'phone' => 18298,
            ]);
        }
    }
}
