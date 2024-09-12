<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_Cambio extends Model
{
    protected $table = "tipo_cambio";

    protected $primaryKey = 'idtipocambio';

    public $timestamps = false;
}
