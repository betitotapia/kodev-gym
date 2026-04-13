<?php

namespace App\Filament\Resources\Pagos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PagoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('socio_id')
                    ->numeric(),
                TextEntry::make('membresia_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('monto')
                    ->numeric(),
                TextEntry::make('metodo')
                    ->badge(),
                TextEntry::make('referencia')
                    ->placeholder('-'),
                TextEntry::make('recibo_pdf')
                    ->placeholder('-'),
                TextEntry::make('fecha_pago')
                    ->date(),
                TextEntry::make('notas')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
