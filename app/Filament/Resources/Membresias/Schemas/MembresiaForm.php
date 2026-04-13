<?php

namespace App\Filament\Resources\Membresias\Schemas;

use App\Models\Plan;
use App\Models\Socio;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class MembresiaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('socio_id')
                    ->label('Socio')
                    ->options(Socio::pluck('nombre_completo', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('plan_id')
                    ->label('Plan')
                    ->options(Plan::pluck('nombre', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $plan = Plan::find($state);
                        if ($plan) {
                            $inicio = now();
                            $set('fecha_inicio', $inicio->toDateString());
                            $set('fecha_fin', $inicio->addDays($plan->duracion_dias)->toDateString());
                        }
                    }),

                DatePicker::make('fecha_inicio')
                    ->label('Fecha inicio')
                    ->required(),

                DatePicker::make('fecha_fin')
                    ->label('Fecha vencimiento')
                    ->required(),

                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'activa'    => 'Activa',
                        'vencida'   => 'Vencida',
                        'cancelada' => 'Cancelada',
                    ])
                    ->default('activa')
                    ->required(),
            ]);
    }
}