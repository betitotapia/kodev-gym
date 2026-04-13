<?php

namespace App\Filament\Resources\Socios\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SocioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre_completo'),
                TextEntry::make('fecha_nacimiento')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('edad')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('genero')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('domicilio')
                    ->placeholder('-'),
                TextEntry::make('telefono')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('identificacion')
                    ->placeholder('-'),
                TextEntry::make('fotografia')
                    ->placeholder('-'),
                TextEntry::make('firma')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('presion_arterial')
                    ->badge()
                    ->placeholder('-'),
                IconEntry::make('afeccion_cardiaca')
                    ->boolean(),
                TextEntry::make('detalle_cardiaco')
                    ->placeholder('-'),
                IconEntry::make('afeccion_respiratoria')
                    ->boolean(),
                TextEntry::make('detalle_respiratorio')
                    ->placeholder('-'),
                IconEntry::make('alergia')
                    ->boolean(),
                TextEntry::make('detalle_alergia')
                    ->placeholder('-'),
                IconEntry::make('lesion')
                    ->boolean(),
                TextEntry::make('detalle_lesion')
                    ->placeholder('-'),
                TextEntry::make('contacto_emergencia')
                    ->placeholder('-'),
                TextEntry::make('telefono_emergencia')
                    ->placeholder('-'),
                TextEntry::make('tipo_entrenamiento')
                    ->badge(),
                IconEntry::make('activo')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
