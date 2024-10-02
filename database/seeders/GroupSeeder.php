<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        Group::create([
            'name' => 'BETANIA',
            'text' => 'Filipenses 4:13',
            'motto' => 'Lema 1',
            'song' => 'cancion 1',
            'church_id' => 1,
        ]);
        Group::create([
            'name' => 'EMANUEL',
            'text' => 'Mateo 4:13',
            'motto' => 'Lema 2',
            'song' => 'cancion 2',
            'church_id' => 1,
        ]);
        Group::create([
            'name' => 'EFESO',
            'text' => 'Marcos 4:13',
            'motto' => 'Lema 3',
            'song' => 'cancion 3',
            'church_id' => 1,
        ]);
        Group::create([
            'name' => 'HEME AQUI',
            'text' => 'Lucas 4:13',
            'motto' => 'Lema 3',
            'song' => 'cancion 3',
            'church_id' => 1,
        ]);
        Group::create([
            'name' => 'JOVENES',
            'text' => 'Apocalipsis 4:13',
            'motto' => 'Lema 3',
            'song' => 'cancion 3',
            'church_id' => 1,
        ]);
        Group::create([
            'name' => 'MARANATHA',
            'text' => 'Salmos 4:13',
            'motto' => 'Lema 3',
            'song' => 'cancion 3',
            'church_id' => 1,
        ]);
    }
}
