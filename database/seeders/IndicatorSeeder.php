<?php

namespace Database\Seeders;

use App\Models\Indicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        Indicator::create([
            'name' => 'Estudio diario de la Biblia y la lección',
            'dimension_id' => 1,
        ]);
        Indicator::create([
            'name' => 'Participación en grupos pequeños o unidad de acción en la semana',
            'dimension_id' => 2,
        ]);
        Indicator::create([
            'name' => 'Amigos de esperanza',
            'dimension_id' => 2,
        ]);
        Indicator::create([
            'name' => 'Estudios biblicos en la semana',
            'dimension_id' => 3,
        ]);
        Indicator::create([
            'name' => 'Visitas misioneras en el semana',
            'dimension_id' => 3,
        ]);
        Indicator::create([
            'name' => 'Entrega de publicaciones adventistas en el semana',
            'dimension_id' => 3,
        ]);
    }
}
