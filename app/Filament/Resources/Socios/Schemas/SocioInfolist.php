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
                   \Filament\Infolists\Components\TextEntry::make('fotografia')
                        ->label('Fotografía')
                        ->formatStateUsing(function ($state) {
                            if (!$state) {
                                return '<div style="width:100px;height:100px;border-radius:50%;background:#f3f4f6;border:3px dashed #d1d5db;display:flex;align-items:center;justify-content:center;"><span style="color:#9ca3af;font-size:12px;">Sin foto</span></div>';
                            }

                            $path = storage_path('app/private/' . $state);

                            if (!file_exists($path)) {
                                return '<div style="color:#9ca3af;font-size:12px;">Foto no encontrada</div>';
                            }

                            $base64 = base64_encode(file_get_contents($path));
                            $ext    = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            $mime   = in_array($ext, ['jpg', 'jpeg']) ? 'image/jpeg' : 'image/png';
                            $src    = "data:{$mime};base64,{$base64}";

                            return '<div style="width:100px;height:100px;border-radius:50%;overflow:hidden;border:3px solid #f59e0b;">
                                <img src="' . $src . '" style="width:100%;height:100%;object-fit:cover;" alt="Foto del socio"/>
                            </div>';
                        })
                        ->html(),
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
