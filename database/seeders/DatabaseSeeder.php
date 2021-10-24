<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            UserProductSeeder::class,
            FileSeeder::class,
            CategorySeeder::class,
            SlideSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
