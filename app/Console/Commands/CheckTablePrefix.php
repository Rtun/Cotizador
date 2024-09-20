<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class CheckTablePrefix extends Command
{
    protected $signature = 'check:table-prefix';
    protected $description = 'Check the table prefix';

    public function handle()
    {
        // Registrar el prefijo de la tabla en los logs
        $prefix = Schema::connection('mysql')->getConnection()->getTablePrefix();
        Log::info('Table Prefix:', [$prefix]);
        $this->info("Table Prefix: $prefix");
    }
}
