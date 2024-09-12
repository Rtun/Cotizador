<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cot_Encabezado extends Model
{
    protected $table = "cot_encabezado";

    protected $primaryKey = 'idcotizacion';

    public $timestamps = false;
}
