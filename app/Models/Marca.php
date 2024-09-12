<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = "prod_marca";

    protected $primaryKey = 'idmarca';

    public $timestamps = false;
}
