<?php

namespace Database\Seeders;

use App\Models\Dimension;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DimensionSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        Dimension::create([
            'name' => 'COMUNIÓN',
        ]);
        Dimension::create([
            'name' => 'RELACIÓN',
        ]);
        Dimension::create([
            'name' => 'MISIÓN',
        ]);
    }
}
