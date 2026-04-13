<?php

namespace App\Filament\Resources\Socios\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SocioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre_completo')
                    ->required(),
                DatePicker::make('fecha_nacimiento'),
                TextInput::make('edad')
                    ->numeric(),
                Select::make('genero')
                    ->options(['Hombre' => 'Hombre', 'Mujer' => 'Mujer', 'Otro' => 'Otro']),
                TextInput::make('domicilio'),
                TextInput::make('telefono')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('identificacion'),
                FileUpload::make('fotografia')
                        ->label('Fotografía')
                        ->image()
                        ->imageEditor()
                        ->circleCropper()          // recorte circular como en tu credencial
                        ->maxSize(2048)
                        ->directory('socios/fotos')
                        ->visibility('private'),
                Textarea::make('firma')
                    ->columnSpanFull(),
                Select::make('presion_arterial')
                    ->options(['Alta' => 'Alta', 'Baja' => 'Baja', 'Normal' => 'Normal', 'Desconozco' => 'Desconozco']),
                Toggle::make('afeccion_cardiaca')
                    ->required(),
                TextInput::make('detalle_cardiaco'),
                Toggle::make('afeccion_respiratoria')
                    ->required(),
                TextInput::make('detalle_respiratorio'),
                Toggle::make('alergia')
                    ->required(),
                TextInput::make('detalle_alergia'),
                Toggle::make('lesion')
                    ->required(),
                TextInput::make('detalle_lesion'),
                TextInput::make('contacto_emergencia'),
                TextInput::make('telefono_emergencia')
                    ->tel(),
                Select::make('tipo_entrenamiento')
                    ->options(['GYM' => 'G y m', 'CROSSFIT' => 'C r o s s f i t', 'COMBINADO' => 'C o m b i n a d o'])
                    ->default('GYM')
                    ->required(),
                Toggle::make('activo')
                    ->required(),
            ]);
    }
}
