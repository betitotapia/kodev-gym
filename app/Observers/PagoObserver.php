<?php

namespace App\Observers;

use App\Models\Pago;

class PagoObserver
{
    public function created(Pago $pago): void
    {
        if (! $pago->membresia_id) return;

        $membresia = $pago->membresia;
        $plan      = $membresia->plan;

        $base = $membresia->fecha_fin->isPast()
            ? now()
            : $membresia->fecha_fin;

        $membresia->update([
            'fecha_fin' => $base->addDays($plan->duracion_dias),
            'estado'    => 'activa',
        ]);
    }
}