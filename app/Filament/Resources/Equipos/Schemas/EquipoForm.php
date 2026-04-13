<?php

namespace App\Filament\Resources\Equipos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EquipoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required(),

                Select::make('categoria')
                    ->label('Categoría')
                    ->options([
                        'cardio'     => 'Cardio',
                        'fuerza'     => 'Fuerza',
                        'funcional'  => 'Funcional',
                    ])
                    ->nullable(),

                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'libre'         => 'Libre',
                        'ocupado'       => 'Ocupado',
                        'mantenimiento' => 'En mantenimiento',
                    ])
                    ->default('libre')
                    ->required(),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->columnSpanFull()
                    ->nullable(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}