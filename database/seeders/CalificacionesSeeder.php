<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalificacionesSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // PRODUCTO 3 — Arepa de Choclo | Prueba Triangular | 2024-11-16
        // Cada panelista envía 1 fila con el código de la muestra que considera diferente.
        // AC-T02 es la diferente (fórmula con menor contenido de sal).
        // Cabina 1: panelistas 1-4 | Cabina 2: panelistas 5-7
        // =====================================================================
        DB::table('calificaciones')->insert([
            // Cabina 1
            ['idpane' => 1, 'producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T02',
             'es_diferente' => true, 'es_igual_referencia' => null,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 2, 'producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T02',
             'es_diferente' => true, 'es_igual_referencia' => null,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 3, 'producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T01',
             'es_diferente' => true, 'es_igual_referencia' => null,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 4, 'producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T02',
             'es_diferente' => true, 'es_igual_referencia' => null,
             'fecha' => '2024-11-16', 'cabina' => 1],
            // Cabina 2
            ['idpane' => 5, 'producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T02',
             'es_diferente' => true, 'es_igual_referencia' => null,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 6, 'producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T03',
             'es_diferente' => true, 'es_igual_referencia' => null,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 7, 'producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T02',
             'es_diferente' => true, 'es_igual_referencia' => null,
             'fecha' => '2024-11-16', 'cabina' => 2],
        ]);

        // =====================================================================
        // PRODUCTO 3 — Arepa de Choclo | Prueba Duo-Trío | 2024-11-16
        // Cada panelista indica cuál muestra es igual a la referencia AC-DR.
        // AC-D01 = igual a referencia | AC-D02 = diferente
        // Cabina 1: panelistas 1-4 | Cabina 2: panelistas 5-7
        // =====================================================================
        DB::table('calificaciones')->insert([
            // Cabina 1
            ['idpane' => 1, 'producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D01',
             'es_diferente' => null, 'es_igual_referencia' => true,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 2, 'producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D01',
             'es_diferente' => null, 'es_igual_referencia' => true,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 3, 'producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D02',
             'es_diferente' => null, 'es_igual_referencia' => true,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 4, 'producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D01',
             'es_diferente' => null, 'es_igual_referencia' => true,
             'fecha' => '2024-11-16', 'cabina' => 1],
            // Cabina 2
            ['idpane' => 5, 'producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D01',
             'es_diferente' => null, 'es_igual_referencia' => true,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 6, 'producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D02',
             'es_diferente' => null, 'es_igual_referencia' => true,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 7, 'producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D01',
             'es_diferente' => null, 'es_igual_referencia' => true,
             'fecha' => '2024-11-16', 'cabina' => 2],
        ]);

        // =====================================================================
        // PRODUCTO 3 — Arepa de Choclo | Prueba Ordenamiento | 2024-11-16
        // Cada panelista envía 1 fila por MUESTRA con puntajes 1-9 por atributo.
        // Atributos: sabor, olor, textura, apariencia (color deshabilitado)
        // Cabina 1: panelistas 1-4 | Cabina 2: panelistas 5-7 | Cabina 3: panelistas 8-10
        // =====================================================================
        DB::table('calificaciones')->insert([
            // --- Cabina 1 ---
            ['idpane' => 1, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 8, 'valor_olor' => 7, 'valor_textura' => 8, 'valor_apariencia' => 7,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 1, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 6, 'valor_olor' => 5, 'valor_textura' => 6, 'valor_apariencia' => 6,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 1, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 4, 'valor_olor' => 4, 'valor_textura' => 3, 'valor_apariencia' => 5,
             'fecha' => '2024-11-16', 'cabina' => 1],

            ['idpane' => 2, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 7, 'valor_olor' => 8, 'valor_textura' => 7, 'valor_apariencia' => 8,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 2, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 5, 'valor_olor' => 6, 'valor_textura' => 5, 'valor_apariencia' => 5,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 2, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 3, 'valor_olor' => 3, 'valor_textura' => 4, 'valor_apariencia' => 4,
             'fecha' => '2024-11-16', 'cabina' => 1],

            ['idpane' => 3, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 9, 'valor_olor' => 8, 'valor_textura' => 9, 'valor_apariencia' => 8,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 3, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 7, 'valor_olor' => 6, 'valor_textura' => 6, 'valor_apariencia' => 7,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 3, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 4, 'valor_olor' => 5, 'valor_textura' => 5, 'valor_apariencia' => 4,
             'fecha' => '2024-11-16', 'cabina' => 1],

            ['idpane' => 4, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 8, 'valor_olor' => 7, 'valor_textura' => 7, 'valor_apariencia' => 9,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 4, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 5, 'valor_olor' => 5, 'valor_textura' => 6, 'valor_apariencia' => 5,
             'fecha' => '2024-11-16', 'cabina' => 1],
            ['idpane' => 4, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 3, 'valor_olor' => 4, 'valor_textura' => 4, 'valor_apariencia' => 3,
             'fecha' => '2024-11-16', 'cabina' => 1],

            // --- Cabina 2 ---
            ['idpane' => 5, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 7, 'valor_olor' => 6, 'valor_textura' => 8, 'valor_apariencia' => 7,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 5, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 6, 'valor_olor' => 7, 'valor_textura' => 5, 'valor_apariencia' => 6,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 5, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 5, 'valor_olor' => 4, 'valor_textura' => 4, 'valor_apariencia' => 5,
             'fecha' => '2024-11-16', 'cabina' => 2],

            ['idpane' => 6, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 9, 'valor_olor' => 9, 'valor_textura' => 8, 'valor_apariencia' => 9,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 6, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 6, 'valor_olor' => 5, 'valor_textura' => 7, 'valor_apariencia' => 6,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 6, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 3, 'valor_olor' => 4, 'valor_textura' => 3, 'valor_apariencia' => 4,
             'fecha' => '2024-11-16', 'cabina' => 2],

            ['idpane' => 7, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 8, 'valor_olor' => 7, 'valor_textura' => 8, 'valor_apariencia' => 7,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 7, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 5, 'valor_olor' => 6, 'valor_textura' => 6, 'valor_apariencia' => 5,
             'fecha' => '2024-11-16', 'cabina' => 2],
            ['idpane' => 7, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 4, 'valor_olor' => 3, 'valor_textura' => 5, 'valor_apariencia' => 4,
             'fecha' => '2024-11-16', 'cabina' => 2],

            // --- Cabina 3 ---
            ['idpane' => 8, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 7, 'valor_olor' => 8, 'valor_textura' => 7, 'valor_apariencia' => 8,
             'fecha' => '2024-11-16', 'cabina' => 3],
            ['idpane' => 8, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 6, 'valor_olor' => 5, 'valor_textura' => 6, 'valor_apariencia' => 7,
             'fecha' => '2024-11-16', 'cabina' => 3],
            ['idpane' => 8, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 4, 'valor_olor' => 4, 'valor_textura' => 4, 'valor_apariencia' => 3,
             'fecha' => '2024-11-16', 'cabina' => 3],

            ['idpane' => 9, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 8, 'valor_olor' => 7, 'valor_textura' => 9, 'valor_apariencia' => 8,
             'fecha' => '2024-11-16', 'cabina' => 3],
            ['idpane' => 9, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 5, 'valor_olor' => 6, 'valor_textura' => 6, 'valor_apariencia' => 5,
             'fecha' => '2024-11-16', 'cabina' => 3],
            ['idpane' => 9, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 3, 'valor_olor' => 5, 'valor_textura' => 3, 'valor_apariencia' => 4,
             'fecha' => '2024-11-16', 'cabina' => 3],

            ['idpane' => 10, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301',
             'valor_sabor' => 9, 'valor_olor' => 8, 'valor_textura' => 8, 'valor_apariencia' => 9,
             'fecha' => '2024-11-16', 'cabina' => 3],
            ['idpane' => 10, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302',
             'valor_sabor' => 6, 'valor_olor' => 7, 'valor_textura' => 5, 'valor_apariencia' => 6,
             'fecha' => '2024-11-16', 'cabina' => 3],
            ['idpane' => 10, 'producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303',
             'valor_sabor' => 4, 'valor_olor' => 3, 'valor_textura' => 4, 'valor_apariencia' => 4,
             'fecha' => '2024-11-16', 'cabina' => 3],
        ]);
    }
}
