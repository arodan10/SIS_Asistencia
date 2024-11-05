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
            'name' => 'DTI',
            'mday' => 'Lunes',
            'mtime' => '7:30',
            'text' => 'Encargados de impulsar la innovación tecnológica en la organización, proporcionando herramientas y soluciones digitales que optimicen los procesos y faciliten el logro de objetivos',
        ]);
        Group::create([
            'name' => 'Soporte',
            'mday' => 'Martes',
            'mtime' => '9:30',
            'text' => 'Equipo dedicado a brindar asistencia técnica y resolver problemas para asegurar que los sistemas funcionen de manera óptima',
        ]);
        Group::create([
            'name' => 'Redes',
            'mday' => 'Miercoles',
            'mtime' => '14:30',
            'text' => 'Responsables de gestionar, mantener y asegurar las redes de comunicación, garantizando una conectividad estable y segura',
        ]);
        Group::create([
            'name' => 'Administracion',
            'mday' => 'Jueves',
            'mtime' => '10:30',
            'text' => 'Área encargada de gestionar recursos, supervisar operaciones y mantener el orden organizacional',
        ]);
    }
}
