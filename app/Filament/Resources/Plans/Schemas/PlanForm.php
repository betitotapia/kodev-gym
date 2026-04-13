<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required(),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->columnSpanFull(),

                TextInput::make('duracion_dias')
                    ->label('Duración (días)')
                    ->numeric()
                    ->required(),

                TextInput::make('precio')
                    ->label('Precio')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}