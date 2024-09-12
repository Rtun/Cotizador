<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reuniones extends Model
{
    protected $table = "sa_reunion";

    protected $primaryKey = 'idreunion';

    public $timestamps = false;
}

