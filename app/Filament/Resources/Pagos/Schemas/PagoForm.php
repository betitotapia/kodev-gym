<?php

namespace App\Filament\Resources\Pagos\Schemas;

use App\Models\Socio;
use App\Models\Membresia;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PagoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('socio_id')
                    ->label('Socio')
                    ->options(Socio::pluck('nombre_completo', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('membresia_id', null)),

                Select::make('membresia_id')
                    ->label('Membresía')
                    ->options(function (callable $get) {
                        $socioId = $get('socio_id');
                        if (! $socioId) return [];

                        return Membresia::with('plan')
                            ->where('socio_id', $socioId)
                            ->get()
                            ->mapWithKeys(fn ($m) => [
                                $m->id => "{$m->plan->nombre} — vence {$m->fecha_fin->format('d/m/Y')}"
                            ]);
                    })
                    ->searchable()
                    ->nullable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) {
                            $set('monto', null);
                            return;
                        }

                        $membresia = Membresia::with('plan')->find($state);
                        if ($membresia) {
                            $set('monto', $membresia->plan->precio);
                        }
                    }),

                TextInput::make('monto')
                    ->label('Monto')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                Select::make('metodo')
                    ->label('Método de pago')
                    ->options([
                        'efectivo'      => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'tarjeta'       => 'Tarjeta',
                    ])
                    ->default('efectivo')
                    ->required(),

                TextInput::make('referencia')
                    ->label('Referencia')
                    ->nullable(),

                DatePicker::make('fecha_pago')
                    ->label('Fecha de pago')
                    ->default(now())
                    ->required(),

                Textarea::make('notas')
                    ->label('Notas')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }
}