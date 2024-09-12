<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adicionales extends Model
{
    protected $table = "cot_adicionales";

    protected $primaryKey = 'idcotadicionales';

    public $timestamps = false;
}
