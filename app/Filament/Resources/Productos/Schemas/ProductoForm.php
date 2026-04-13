<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required(),

                TextInput::make('codigo_barras')
                    ->label('Código de barras')
                    ->unique(ignoreRecord: true)
                    ->nullable(),

                Select::make('categoria')
                    ->label('Categoría')
                    ->options([
                        'suplementos' => 'Suplementos',
                        'ropa'        => 'Ropa',
                        'bebidas'     => 'Bebidas',
                        'accesorios'  => 'Accesorios',
                        'otros'       => 'Otros',
                    ])
                    ->nullable(),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->columnSpanFull()
                    ->nullable(),

                TextInput::make('precio_compra')
                    ->label('Precio compra')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),

                TextInput::make('precio_venta')
                    ->label('Precio venta')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock actual')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('stock_minimo')
                    ->label('Stock mínimo')
                    ->numeric()
                    ->default(5),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}