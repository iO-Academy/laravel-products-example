<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For loop to repeat the insert 1000 times
        for ($i = 0; $i < 100; $i++) {
            // Creating a randomized dummy product
            DB::table('products')->insert([
                'title' => Str::random(20),
                'description' => Str::random(100),
                'price' => rand(1, 1000),
                'qty' => rand(0, 100),
                'free_shipping' => rand(0, 1) == 1,
            ]);
        }
    }
}
