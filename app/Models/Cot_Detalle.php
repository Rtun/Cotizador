<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cot_Detalle extends Model
{
    protected $table = "cot_detalle";

    protected $primaryKey = 'idcot_detalle';

    public $timestamps = false;
}
