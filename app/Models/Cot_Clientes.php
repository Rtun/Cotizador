<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cot_Clientes extends Model
{
    protected $table = "cot_clientes";

    protected $primaryKey = 'idclientes';

    public $timestamps = false;
}
