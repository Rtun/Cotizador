<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'administrador@comsitec.com.mx',
                'password' => '$2y$12$2b3l4y3mkyxrNZwHOV4UpOBfnfIz8k7oqXW6CgfW3dIRRI6kAUA5y', // Cambia esto por el hash real
                'telefono' => '99999999',
                'empresa' => 'COMSITEC SA DE CV',
                'web' => 'https://comsitec.com.mx',
                'created_at' => now(),
                'updated_at' => now(),
                'idrol' => 1,
                'status' => 'AC',
            ]
            // Inserta los demás usuarios de manera similar
        ]);
    }
}
