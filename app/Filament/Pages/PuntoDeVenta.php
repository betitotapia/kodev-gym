<?php

namespace App\Filament\Pages;

use App\Models\Producto;
use App\Models\Socio;
use App\Models\Venta;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;

class PuntoDeVenta extends Page
{
    protected static ?string $navigationLabel = 'Punto de venta';
    protected static ?string $title           = 'Punto de venta';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;
    protected static ?int $navigationSort = 99;
    protected string $view = 'filament.pages.punto-de-venta';

    public array   $carrito      = [];
    public float   $descuento    = 0;
    public string  $metodo_pago  = 'efectivo';
    public ?int    $socio_id     = null;
    public string  $busqueda     = '';
    public array   $resultados   = [];
    public float   $monto_pagado = 0;
    public string $referencia_pago = '';

    public function getSociosProperty(): Collection
    {
        return Socio::orderBy('nombre_completo')->get();
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->carrito)->sum('subtotal');
    }

    public function getTotalProperty(): float
    {
        return max(0, $this->subtotal - $this->descuento);
    }

    public function getCambioProperty(): float
    {
        if ($this->metodo_pago !== 'efectivo') return 0;
        return max(0, $this->monto_pagado - $this->total);
    }

    public function updatedBusqueda(): void
    {
        if (strlen($this->busqueda) < 2) {
            $this->resultados = [];
            return;
        }

        // Búsqueda exacta por código de barras — agrega directo
        $porCodigo = Producto::where('codigo_barras', $this->busqueda)
            ->where('activo', true)
            ->first();

        if ($porCodigo) {
            $this->agregarAlCarrito($porCodigo->id);
            $this->busqueda   = '';
            $this->resultados = [];
            return;
        }

        // Búsqueda por nombre — muestra lista para elegir
        $this->resultados = Producto::where('nombre', 'like', "%{$this->busqueda}%")
            ->where('activo', true)
            ->limit(8)
            ->get()
            ->map(fn ($p) => [
                'id'     => $p->id,
                'nombre' => $p->nombre,
                'precio' => (float) $p->precio_venta,
                'stock'  => $p->stock,
            ])
            ->toArray();
    }

    public function seleccionar(int $id): void
    {
        $this->agregarAlCarrito($id);
        $this->busqueda   = '';
        $this->resultados = [];
    }

    public function agregarAlCarrito(int $id): void
    {
        $producto = Producto::find($id);
        if (! $producto) return;

        if ($producto->stock <= 0) {
            Notification::make()->title('Sin stock disponible')->danger()->send();
            return;
        }

        if (isset($this->carrito[$id])) {
            $this->carrito[$id]['cantidad']++;
            $this->carrito[$id]['subtotal'] =
                $this->carrito[$id]['cantidad'] * $this->carrito[$id]['precio'];
        } else {
            $this->carrito[$id] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => (float) $producto->precio_venta,
                'cantidad' => 1,
                'subtotal' => (float) $producto->precio_venta,
            ];
        }
    }

    public function incrementar(int $id): void
    {
        if (isset($this->carrito[$id])) {
            $this->carrito[$id]['cantidad']++;
            $this->carrito[$id]['subtotal'] =
                $this->carrito[$id]['cantidad'] * $this->carrito[$id]['precio'];
        }
    }

    public function decrementar(int $id): void
    {
        if (isset($this->carrito[$id])) {
            if ($this->carrito[$id]['cantidad'] <= 1) {
                $this->eliminarDelCarrito($id);
            } else {
                $this->carrito[$id]['cantidad']--;
                $this->carrito[$id]['subtotal'] =
                    $this->carrito[$id]['cantidad'] * $this->carrito[$id]['precio'];
            }
        }
    }

    public function eliminarDelCarrito(int $id): void
    {
        unset($this->carrito[$id]);
    }

    public function cobrar(): void
    {
        if (empty($this->carrito)) {
            Notification::make()->title('El carrito está vacío')->warning()->send();
            return;
        }

        // if ($this->metodo_pago === 'efectivo' && $this->monto_pagado < $this->total) {
        //     Notification::make()->title('El monto recibido es menor al total')->danger()->send();
        //     return;
        // }

        Venta::create([
            'socio_id'    => $this->socio_id,
            'tipo'        => 'producto',
            'items'       => array_values($this->carrito),
            'subtotal'    => $this->subtotal,
            'descuento'   => $this->descuento,
            'total'       => $this->total,
            'metodo_pago' => $this->metodo_pago,
            'fecha_venta' => now(),
            'referencia'  => $this->referencia_pago ?: null,
        ]);

        foreach ($this->carrito as $item) {
            Producto::find($item['id'])?->decrement('stock', $item['cantidad']);
        }

        $cambio = $this->cambio;

        $this->dispatch('venta-registrada', [
            'items'       => array_values($this->carrito),
            'subtotal'    => $this->subtotal,
            'descuento'   => $this->descuento,
            'total'       => $this->total,
            'metodo_pago' => $this->metodo_pago,
            'referencia'  => $this->referencia_pago,
            'cambio'      => $this->cambio,
        ]);

        Notification::make()
    ->title('Venta registrada correctamente')
    ->success()
    ->send();
        
        $this->carrito      = [];
        $this->descuento    = 0;
        $this->monto_pagado = 0;
        $this->socio_id     = null;
        $this->metodo_pago  = 'efectivo';
        $this->referencia_pago = '';
    }
}