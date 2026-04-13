<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'socio_id', 'membresia_id', 'monto', 
        'metodo', 'referencia', 'recibo_pdf', 
        'fecha_pago', 'notas',
    ];

    protected $casts = [
        'monto'      => 'decimal:2',
        'fecha_pago' => 'date',
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