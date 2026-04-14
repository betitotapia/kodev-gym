<?php

namespace App\Filament\Pages;

use App\Models\Asistencia as AsistenciaModel;
use App\Models\Socio;
use App\Models\Tenant;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Asistencia extends Page
{
    protected static ?string $navigationLabel = 'Control de asistencia';
    protected static ?string $title           = 'Control de asistencia';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;
    protected static ?int $navigationSort = 98;
    protected string $view = 'filament.pages.asistencia';

    public string $qrInput   = '';
    public ?array $resultado  = null;
    public string $estado     = ''; // 'activa', 'vencida', 'sin_membresia', 'no_encontrado'
    public array $historialHoy = [];

    public function buscarSocio(): void
    {
        $tenant = \App\Models\Tenant::first();
            if (!tenancy()->initialized) {
        $tenant = \App\Models\Tenant::first();
        if ($tenant) tenancy()->initialize($tenant);
    }

    $input = trim($this->qrInput);
    if (empty($input)) return;

        // Extraer ID del formato GYM-SOCIO-X o número directo
        $socioId = null;
        if (str_starts_with($input, 'GYM-SOCIO-')) {
            $socioId = (int) str_replace('GYM-SOCIO-', '', $input);
        } elseif (is_numeric($input)) {
            $socioId = (int) $input;
        }

        if (!$socioId) {
            $this->resultado = ['mensaje' => 'QR no reconocido'];
            $this->estado    = 'no_encontrado';
            $this->qrInput   = '';
            $this->dispatch('alerta-asistencia', estado: 'no_encontrado', datos: []);
            return;
        }

        $socio = Socio::find($socioId);

        if (!$socio) {
            $this->resultado = ['mensaje' => 'Socio no encontrado'];
            $this->estado    = 'no_encontrado';
            $this->qrInput   = '';
            $this->dispatch('alerta-asistencia', estado: 'no_encontrado', datos: []);
            return;
        }

        $membresia = $socio->membresiaActiva;
        $fotoBase64 = null;

        // Cargar foto
        if ($socio->fotografia) {
            $path = storage_path('app/private/' . $socio->fotografia);
            if (file_exists($path)) {
                $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime = in_array($ext, ['jpg','jpeg']) ? 'image/jpeg' : 'image/png';
                $fotoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        if (!$membresia) {
            $this->estado = 'sin_membresia';
            $datos = [
                'nombre'   => $socio->nombre_completo,
                'foto'     => $fotoBase64,
                'mensaje'  => 'Sin membresía activa',
            ];
        } elseif ($membresia->fecha_fin->isPast()) {
            $this->estado = 'vencida';
            $datos = [
                'nombre'      => $socio->nombre_completo,
                'foto'        => $fotoBase64,
                'plan'        => $membresia->plan->nombre,
                'vencio'      => $membresia->fecha_fin->format('d/m/Y'),
                'mensaje'     => 'Membresía vencida',
            ];
        } else {
            $this->estado = 'activa';
            $dias = now()->diffInDays($membresia->fecha_fin);
            $datos = [
                'nombre'      => $socio->nombre_completo,
                'foto'        => $fotoBase64,
                'plan'        => $membresia->plan->nombre,
                'vence'       => $membresia->fecha_fin->format('d/m/Y'),
                'dias'        => $dias,
                'mensaje'     => '¡Bienvenido!',
            ];
        }

        // Registrar asistencia
           $tenant = \App\Models\Tenant::first();
            tenancy()->run($tenant, function() use ($socio, $membresia) {
                \App\Models\Asistencia::create([
                    'socio_id'     => $socio->id,
                    'membresia_id' => $membresia?->id,
                    'estado'       => $this->estado,
                    'entrada_at'   => now(),
                ]);
            });
        $this->resultado = $datos;
        $this->qrInput   = '';
        $this->cargarHistorial();
        $this->dispatch('alerta-asistencia', estado: $this->estado, datos: $datos);
    }

   public function mount(): void
            {
                if (!tenancy()->initialized) {
                    $tenant = \App\Models\Tenant::first();
                    if ($tenant) tenancy()->initialize($tenant);
                }
                $this->cargarHistorial();
            }

    public function cargarHistorial(): void
                    {
                        
                   try {
                        $tenant = \App\Models\Tenant::first();
                        $resultado = [];

                        tenancy()->run($tenant, function() use (&$resultado) {
                            $resultado = AsistenciaModel::with('socio')
                                ->whereDate('created_at', today())
                                ->latest()
                                ->take(10)
                                ->get()
                                ->map(fn ($a) => [
                                    'nombre' => $a->socio->nombre_completo ?? 'Desconocido',
                                    'hora'   => $a->created_at->format('H:i'),
                                    'estado' => $a->estado,
                                ])
                                ->toArray();
                        });

                        $this->historialHoy = $resultado;
                    } catch (\Exception $e) {
                        $this->historialHoy = [];
                        \Illuminate\Support\Facades\Log::error('Historial: ' . $e->getMessage());
                    }
                }
}