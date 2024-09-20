<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolxPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rolxpermiso')->insert([
            ['idrol' => 2, 'idpermiso' => 21],
            ['idrol' => 2, 'idpermiso' => 22],
            ['idrol' => 1, 'idpermiso' => 1],
            ['idrol' => 1, 'idpermiso' => 2],
            ['idrol' => 1, 'idpermiso' => 3],
            ['idrol' => 1, 'idpermiso' => 4],
            ['idrol' => 1, 'idpermiso' => 5],
            ['idrol' => 1, 'idpermiso' => 6],
            ['idrol' => 1, 'idpermiso' => 7],
            ['idrol' => 1, 'idpermiso' => 8],
            ['idrol' => 1, 'idpermiso' => 9],
            ['idrol' => 1, 'idpermiso' => 10],
            ['idrol' => 1, 'idpermiso' => 11],
            ['idrol' => 1, 'idpermiso' => 12],
            ['idrol' => 1, 'idpermiso' => 13],
            ['idrol' => 1, 'idpermiso' => 14],
            ['idrol' => 1, 'idpermiso' => 15],
            ['idrol' => 1, 'idpermiso' => 16],
            ['idrol' => 1, 'idpermiso' => 17],
            ['idrol' => 1, 'idpermiso' => 18],
            ['idrol' => 1, 'idpermiso' => 19],
            ['idrol' => 1, 'idpermiso' => 20],
            ['idrol' => 1, 'idpermiso' => 21],
            ['idrol' => 1, 'idpermiso' => 22],
            ['idrol' => 1, 'idpermiso' => 23],
            ['idrol' => 4, 'idpermiso' => 21],
            ['idrol' => 4, 'idpermiso' => 22],
        ]);
    }
}
