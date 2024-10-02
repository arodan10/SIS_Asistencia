<?php

namespace Database\Seeders;

use App\Models\Church;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChurchSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        Church::create([
            'name' => 'Iglesia Adventista Buenas Nuevas',
            'address' => 'Jr. Orizabal - San Miguel',
        ]);
    }
}
