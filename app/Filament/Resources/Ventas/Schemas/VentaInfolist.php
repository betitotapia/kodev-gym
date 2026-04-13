<?php

namespace App\Filament\Resources\Ventas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VentaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('socio_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('membresia_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tipo')
                    ->badge(),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('descuento')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('metodo_pago')
                    ->badge(),
                TextEntry::make('referencia')
                    ->placeholder('-'),
                TextEntry::make('notas')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('fecha_venta')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
