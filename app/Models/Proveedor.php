<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = "prod_proveedor";

    protected $primaryKey = 'idproveedor';

    public $timestamps = false;
}
