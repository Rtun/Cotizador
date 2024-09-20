<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('api_tokens')->insert([
            'idtoken' => 2,
            'key' => 'syscom',
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNjOGUzMjdiMTNjNDAyNjRhMWI0YTE5ODE0YzI0NzIzNmQyMzM1NGFhZWI0ZTM0ZTg0ZTRkYzViOTBjMzVlODc1MzcwNjVlYWEzZjRkNjI5In0.eyJhdWQiOiJhUFlMNU9wSjFSakpSdGtnZzdwd0ZPV1VkU2E0VzBZZCIsImp0aSI6ImNjOGUzMjdiMTNjNDAyNjRhMWI0YTE5ODE0YzI0NzIzNmQyMzM1NGFhZWI0ZTM0ZTg0ZTRkYzViOTBjMzVlODc1MzcwNjVlYWEzZjRkNjI5IiwiaWF0IjoxNzI2Njg0Mzk4LCJuYmYiOjE3MjY2ODQzOTgsImV4cCI6MTc1ODIyMDM5OCwic3ViIjoiIiwic2NvcGVzIjpbXX0.H5e9kmqgxdUp6x5bTXqGe_qr3CiTvopARk5-z9YfJjMLJ95dXMuaxeBWk1l1F1WJ6CDXvILxAdPDdoemqGkr2984vGEeD6gvniwKVr25a0Rw7lmeBqet43SgFJ4HusAbsSVtp-cEgZHgO9HQ1gPRe1cvo80a54wh2XYCywhWc0EYCAfuCylOnjlgHTMOSLtFcagLip5KDV6dUOKDAPfu5LIe--ShvJ25CNPfMJ5bdrrnT03OnkpMVVTqSAlXpkLVfmdI-T-0ZkiilSUK1aLj3QjSgiV97p_RF65_-ivDgb3WjN0NkgtNwh3ojeDAjDQdTwsL0AHW4Yms0PefWXATzQQWZ3hsYq1NWpjc76nQXQ6tMBmPsAvVWh2emk4IobEW1KaaPg0IP-alJtD7Bg8OLgPoYSeCO_2gaRMftkChgfvBsl4GgtNRe_HmleJTWcbagO3dP9nPtguDrJCRWSwgtzu7bV6rWzlekRVvnA19oUE1BVJAnTljxINJE1Lhlq6EaBwfWvjNFl23UZxnlpfqvggaDB5kQgP3H9PD3H-eUMmtMhHiKtTVuLBiPqx5RBxPbH4r52i19bdc-FiKo4ej-UMkENjAQnolbLFoQzyLPoNNQCzSP5VqZLOBw2eP_xAT5UR6hQgOMEuzwYAvAeOzNBJOxR75mHB3Jcnvcb2RZB0',
            'expiration' => '2025-09-19 00:33:18'
        ]);
    }
}
