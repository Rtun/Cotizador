<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SqlServerModel extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'Producto'; // Nombre de la tabla en SQL Server
}
