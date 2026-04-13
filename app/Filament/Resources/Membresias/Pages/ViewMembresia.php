<?php

namespace App\Filament\Resources\Membresias\Pages;

use App\Filament\Resources\Membresias\MembresiaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMembresia extends ViewRecord
{
    protected static string $resource = MembresiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
