<?php

namespace App\Filament\Resources\Membresias\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MembresiaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('socio_id')
                    ->numeric(),
                TextEntry::make('plan_id')
                    ->numeric(),
                TextEntry::make('fecha_inicio')
                    ->date(),
                TextEntry::make('fecha_fin')
                    ->date(),
                TextEntry::make('estado')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
