<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'socio_id', 'membresia_id', 'tipo', 'items',
        'subtotal', 'descuento', 'total',
        'metodo_pago', 'referencia', 'notas', 'fecha_venta',
    ];

    protected $casts = [
        'items'        => 'array',
        'subtotal'     => 'decimal:2',
        'descuento'    => 'decimal:2',
        'total'        => 'decimal:2',
        'fecha_venta'  => 'datetime',
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