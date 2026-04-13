<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre'),
                TextEntry::make('codigo_barras')
                    ->placeholder('-'),
                TextEntry::make('categoria')
                    ->placeholder('-'),
                TextEntry::make('descripcion')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('precio_compra')
                    ->numeric(),
                TextEntry::make('precio_venta')
                    ->numeric(),
                TextEntry::make('stock')
                    ->numeric(),
                TextEntry::make('stock_minimo')
                    ->numeric(),
                TextEntry::make('imagen')
                    ->placeholder('-'),
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
