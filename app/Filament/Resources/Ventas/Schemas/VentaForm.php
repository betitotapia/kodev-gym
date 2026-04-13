<?php

namespace App\Filament\Resources\Ventas\Schemas;

use App\Models\Socio;
use App\Models\Membresia;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('socio_id')
                    ->label('Socio')
                    ->options(Socio::pluck('nombre_completo', 'id'))
                    ->searchable()
                    ->nullable(),

                Select::make('tipo')
                    ->label('Tipo de venta')
                    ->options([
                        'producto'  => 'Producto',
                        'membresia' => 'Membresía',
                        'mixta'     => 'Mixta',
                    ])
                    ->default('producto')
                    ->required(),

                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                TextInput::make('descuento')
                    ->label('Descuento')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),

                TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                Select::make('metodo_pago')
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

                Textarea::make('notas')
                    ->label('Notas')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }
}