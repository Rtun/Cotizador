<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rol')->insert([
            ['idrol' => 1, 'nombre' => 'Administrador'],
            ['idrol' => 2, 'nombre' => 'Ventas'],
            ['idrol' => 3, 'nombre' => 'Obra'],
            ['idrol' => 4, 'nombre' => 'Usuario'],
        ]);
    }
}
