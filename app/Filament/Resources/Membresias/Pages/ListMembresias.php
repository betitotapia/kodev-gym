<?php

namespace App\Filament\Resources\Membresias\Pages;

use App\Filament\Resources\Membresias\MembresiaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMembresias extends ListRecords
{
    protected static string $resource = MembresiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
              ->label('Nueva membresía'),
        ];
    }
}
