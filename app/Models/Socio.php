<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $fillable = [
        'nombre_completo', 'fecha_nacimiento', 'edad', 'genero',
        'domicilio', 'telefono', 'email', 'identificacion',
        'fotografia', 'firma', 'presion_arterial',
        'afeccion_cardiaca', 'detalle_cardiaco',
        'afeccion_respiratoria', 'detalle_respiratorio',
        'alergia', 'detalle_alergia',
        'lesion', 'detalle_lesion',
        'contacto_emergencia', 'telefono_emergencia',
        'tipo_entrenamiento', 'declaracion_salud',
        'acepta_reglamento', 'activo',
    ];

    protected $casts = [
        'fecha_nacimiento'      => 'date',
        'afeccion_cardiaca'     => 'boolean',
        'afeccion_respiratoria' => 'boolean',
        'alergia'               => 'boolean',
        'lesion'                => 'boolean',
        'declaracion_salud'     => 'boolean',
        'acepta_reglamento'     => 'boolean',
        'activo'                => 'boolean',
    ];

    public function membresias()
    {
        return $this->hasMany(Membresia::class);
    }

    public function membresiaActiva()
    {
        return $this->hasOne(Membresia::class)
                    ->where('estado', 'activa')
                    ->latest();
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function rutinas()
    {
        return $this->hasMany(Rutina::class);
    }
}