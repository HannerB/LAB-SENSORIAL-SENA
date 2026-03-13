<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PanelistasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('panelistas')->insert([
            ['nombres' => 'Carlos Andrés Melo',       'fecha' => '2024-11-15'],
            ['nombres' => 'Laura Camila Ríos',         'fecha' => '2024-11-15'],
            ['nombres' => 'Juan Pablo Soto',           'fecha' => '2024-11-15'],
            ['nombres' => 'Valentina Cruz Herrera',    'fecha' => '2024-11-15'],
            ['nombres' => 'Andrés Felipe Mora',        'fecha' => '2024-11-15'],
            ['nombres' => 'Daniela Ospina Torres',     'fecha' => '2024-11-15'],
            ['nombres' => 'Miguel Ángel Torres',       'fecha' => '2024-11-15'],
            ['nombres' => 'Sara Lucía Pinto',          'fecha' => '2024-11-16'],
            ['nombres' => 'Diego Alejandro Gómez',     'fecha' => '2024-11-16'],
            ['nombres' => 'Natalia Pérez Ruiz',        'fecha' => '2024-11-16'],
        ]);
    }
}
