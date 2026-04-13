<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Services\GeneradorCredencial;
use Illuminate\Http\Request;

class CredencialController extends Controller
{
    public function descargar(Socio $socio)
    {
        $ruta = GeneradorCredencial::generar($socio);
        $path = storage_path('app/public/' . $ruta);

        return response()->download(
            $path,
            'credencial-' . str_pad($socio->id, 4, '0', STR_PAD_LEFT) . '.png',
            ['Content-Type' => 'image/png']
        );
    }
}