<?php

namespace App\Filament\Resources\Socios\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class SocioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('fotografia')
                    ->label('Fotografía')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->maxSize(2048)
                    ->directory('socios/fotos')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

                Placeholder::make('Cámara')
                    ->label('')
                    ->content(new HtmlString('
                        <button type="button"
                            onclick="document.getElementById(\'modal-camara\').style.display=\'flex\'; abrirCamaraFilament()"
                            style="padding:9px 18px;border-radius:8px;background:#fef3c7;color:#92400e;border:1.5px solid #f59e0b;font-size:13px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Tomar foto con cámara
                        </button>
                    ')),

                TextInput::make('nombre_completo')
                    ->label('Nombre completo')
                    ->required(),

                DatePicker::make('fecha_nacimiento')
                    ->label('Fecha de nacimiento'),

                TextInput::make('edad')
                    ->label('Edad')
                    ->numeric(),

                Select::make('genero')
                    ->label('Género')
                    ->options(['Hombre' => 'Hombre', 'Mujer' => 'Mujer', 'Otro' => 'Otro']),

                TextInput::make('domicilio')
                    ->label('Domicilio'),

                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel(),

                TextInput::make('email')
                    ->label('Email')
                    ->email(),

                TextInput::make('identificacion')
                    ->label('CURP / INE'),

                Select::make('presion_arterial')
                    ->label('Presión arterial')
                    ->options([
                        'Alta'       => 'Alta',
                        'Baja'       => 'Baja',
                        'Normal'     => 'Normal',
                        'Desconozco' => 'Desconozco',
                    ]),

                Toggle::make('afeccion_cardiaca')
                    ->label('¿Afección cardiaca?'),

                TextInput::make('detalle_cardiaco')
                    ->label('Especifique (cardiaca)'),

                Toggle::make('afeccion_respiratoria')
                    ->label('¿Afección respiratoria?'),

                TextInput::make('detalle_respiratorio')
                    ->label('Especifique (respiratoria)'),

                Toggle::make('alergia')
                    ->label('¿Alergias?'),

                TextInput::make('detalle_alergia')
                    ->label('Especifique (alergia)'),

                Toggle::make('lesion')
                    ->label('¿Lesiones?'),

                TextInput::make('detalle_lesion')
                    ->label('Especifique (lesión)'),

                TextInput::make('contacto_emergencia')
                    ->label('Contacto de emergencia'),

                TextInput::make('telefono_emergencia')
                    ->label('Teléfono de emergencia')
                    ->tel(),

                Select::make('tipo_entrenamiento')
                    ->label('Tipo de entrenamiento')
                    ->options([
                        'GYM'       => 'GYM',
                        'CROSSFIT'  => 'Crossfit',
                        'COMBINADO' => 'Combinado',
                    ])
                    ->default('GYM')
                    ->required(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}