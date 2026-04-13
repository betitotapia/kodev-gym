<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'nombre', 'categoria', 'estado', 
        'descripcion', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}