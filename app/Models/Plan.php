<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';
    protected $connection = 'tenant';
    
    protected $fillable = [
        'nombre', 'slug', 'descripcion', 
        'duracion_dias', 'precio', 'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function membresias()
    {
        return $this->hasMany(Membresia::class);
    }
}