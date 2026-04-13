<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'nombre_gym', 'logo', 'telefono', 'direccion',
        'email', 'website', 'plantilla_credencial',
        'zonas_credencial', 'dias_aviso_vencimiento',
        'notificar_whatsapp', 'notificar_email',
        'asistencia_activa', 'sonido_bienvenida', 'sonido_vencida',
    ];

    protected $casts = [
        'zonas_credencial'      => 'array',
        'notificar_whatsapp'    => 'boolean',
        'notificar_email'       => 'boolean',
        'asistencia_activa'     => 'boolean',
    ];

    public static function instancia(): static
    {
        return static::firstOrCreate([], [
            'nombre_gym'             => 'Mi Gimnasio',
            'dias_aviso_vencimiento' => 7,
            'zonas_credencial'       => self::zonasDefault(),
        ]);
    }

    public static function zonasDefault(): array
    {
        return [
            'foto'        => ['x' => 520, 'y' => 45,  'w' => 180, 'h' => 180, 'forma' => 'circulo'],
            'nombre'      => ['x' => 50,  'y' => 310, 'size' => 26, 'color' => '#CCFF00', 'bold' => true],
            'folio'       => ['x' => 380, 'y' => 390, 'size' => 30, 'color' => '#CCFF00', 'bold' => true],
            'membresia'   => ['x' => 220, 'y' => 230, 'size' => 34, 'color' => '#CCFF00', 'bold' => true],
            'vencimiento' => ['x' => 50,  'y' => 430, 'size' => 16, 'color' => '#ffffff', 'bold' => false],
            'qr'          => ['x' => 480, 'y' => 310, 'w'  => 160, 'h'   => 160],
        ];
    }
}