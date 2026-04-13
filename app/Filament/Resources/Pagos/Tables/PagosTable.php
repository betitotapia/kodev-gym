<?php

namespace App\Filament\Resources\Pagos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('socio.nombre_completo')
                    ->label('Socio')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('monto')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('metodo')
                    ->label('Método')
                    ->badge(),

                TextColumn::make('fecha_pago')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                TextColumn::make('referencia')
                    ->label('Referencia')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('fecha_pago', 'desc')
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