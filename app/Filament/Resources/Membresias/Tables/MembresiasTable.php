<?php

namespace App\Filament\Resources\Membresias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembresiasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('socio.nombre_completo')
                    ->label('Socio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('plan.nombre')
                    ->label('Plan')
                    ->badge()
                    ->sortable(),

                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date()
                    ->sortable(),

                TextColumn::make('fecha_fin')
                    ->label('Vencimiento')
                    ->date()
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activa'    => 'success',
                        'vencida'   => 'danger',
                        'cancelada' => 'warning',
                    }),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}