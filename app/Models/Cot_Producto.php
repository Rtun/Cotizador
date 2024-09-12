<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cot_Producto extends Model
{
    protected $table = "cot_productos";

    protected $primaryKey = 'idproductos';

    public $timestamps = false;
}
