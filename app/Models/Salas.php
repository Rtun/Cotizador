<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salas extends Model
{
    protected $table = "salas";

    protected $primaryKey = 'idsala';

    public $timestamps = false;
}

