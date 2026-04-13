<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre', 'codigo_barras', 'categoria',
        'descripcion', 'precio_compra', 'precio_venta',
        'stock', 'stock_minimo', 'imagen', 'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta'  => 'decimal:2',
        'activo'        => 'boolean',
    ];
}