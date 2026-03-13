<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductosSeeder::class,
            ConfiguracionTableSeeder::class,
            PanelistasSeeder::class,
            MuestrasSeeder::class,
            CalificacionesSeeder::class,
            ResultadosSeeder::class,
        ]);
    }
}
