<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $table = "cot_utilidad";

    protected $primaryKey = 'idutilidad';

    public $timestamps = false;

}
