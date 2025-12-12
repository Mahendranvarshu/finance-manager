<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Arealine;

class ArealineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Arealine::truncate();
        // Create 20 fake area route records
        Arealine::factory()->count(20)->create();
    }
}
