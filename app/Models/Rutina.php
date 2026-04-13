<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rutina extends Model
{
    protected $fillable = [
        'socio_id', 'nombre', 'dia', 'ejercicios', 'activo',
    ];

    protected $casts = [
        'ejercicios' => 'array',  // JSON automáticamente a array de PHP
        'activo'     => 'boolean',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }
}