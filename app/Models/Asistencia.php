<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table      = 'asistencias';
    
    protected $fillable = [
        'socio_id', 'membresia_id', 'estado', 'entrada_at',
    ];

    protected $casts = [
        'entrada_at' => 'datetime',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }
}