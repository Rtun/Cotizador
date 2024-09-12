<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $table = "conceptos";

    protected $primaryKey = 'idconcepto';

    public $timestamps = false;
}
