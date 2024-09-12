<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    use HasFactory;
    protected $table = 'prueba';

    // Especificar las columnas que se pueden asignar de forma masiva
    protected $fillable = ['Nombre', 'Apellido', 'Telefono', 'Correo'];

    // Si la tabla no usa las columnas created_at y updated_at, establece la propiedad a false
    public $timestamps = false;

    // Si el nombre de la columna primary key no es 'id', especificarlo
    protected $primaryKey = 'id_prueba';
}
